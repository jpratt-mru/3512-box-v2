<?php

require 'DatabaseConnection.php';

class DatabaseQueries {
    private $db_helper;

    public function __construct() {
        $config = require 'config.php';
        $this->db_helper = new DatabaseConnection($config);
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function disconnect() {
        $this->db_helper->close_connection();
    }

    /**
     * Fetch all photos with associated user details (including deleted if specified).
     */
    public function getAllPhotos($orderBy = 'ImageID', $includeDeleted = false) {
        // Define available sort columns
        $sortColumns = [
            'ImageID' => 'i.ImageID',
            'Title' => 'i.Title',
            'UserID' => 'u.FirstName'
        ];

        // Set the default sort column (ImageID) if none is provided
        $orderBy = $sortColumns[$orderBy] ?? 'i.ImageID';
        // Determine the sorting direction (ASC or DESC)
        $orderDirection = isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'DESC' : 'ASC';

        // Determine whether to include deleted photos
        $deletedCondition = $includeDeleted ? '' : "AND i.ImageID NOT IN (SELECT ImageID FROM deleted)";

        // Construct SQL query for fetching photos
        $sql = "SELECT 
                    i.ImageID, 
                    i.Title, 
                    i.Path, 
                    i.UserID, 
                    u.FirstName AS first_name, 
                    u.LastName AS last_name,
                    EXISTS (
                        SELECT 1 FROM deleted WHERE deleted.ImageID = i.ImageID
                    ) AS deleted,
                    EXISTS (
                        SELECT 1 FROM flagged WHERE flagged.ImageID = i.ImageID
                    ) AS flagged
                FROM 
                    imagedetails i
                LEFT JOIN 
                    users u ON i.UserID = u.UserID
                WHERE 
                    1=1 $deletedCondition
                ORDER BY $orderBy $orderDirection";

        return $this->fetchAll($sql);
    }




    public function restoreAllPhotos() {
        $sql = "DELETE FROM deleted";
        $this->run($sql);
    }


    /**
     * Flag a photo by its ID.
     */
    public function flagPhoto($photoId) {
        $sql = "INSERT INTO flagged (ImageID) VALUES (:id)
                ON DUPLICATE KEY UPDATE FlaggedAt = CURRENT_TIMESTAMP";
        $this->run($sql, ['id' => $photoId]);
    }

    /**
     * Unflag a photo by its ID.
     */
    public function unflagPhoto($photoId) {
        $sql = "DELETE FROM flagged WHERE ImageID = :id";
        $this->run($sql, ['id' => $photoId]);
    }

    /**
     * Delete a photo (mark as deleted) by its ID.
     */
    public function deletePhoto($photoId) {
        $sql = "INSERT INTO deleted (ImageID) VALUES (:id)
                ON DUPLICATE KEY UPDATE DeletedAt = CURRENT_TIMESTAMP";
        $this->run($sql, ['id' => $photoId]);
    }

    /**
     * Restore a photo by its ID.
     */
    public function restorePhoto($photoId) {
        $sql = "DELETE FROM deleted WHERE ImageID = :id";
        $this->run($sql, ['id' => $photoId]);
    }

    /**
     * Fetch the total number of non-deleted photos.
     */
    public function getTotalPhotos() {
        $sql = "SELECT COUNT(*) as count 
                FROM imagedetails 
                WHERE ImageID NOT IN (SELECT ImageID FROM deleted)";
        $result = $this->fetchSingle($sql);
        return $result ? $result['count'] : 0;
    }

    /**
     * Fetch the most popular city based on photo count.
     */
    public function getMostPopularCity() {
        $sql = "SELECT c.AsciiName AS city_name, COUNT(i.ImageID) AS photo_count
                FROM cities c
                INNER JOIN imagedetails i ON c.CityCode = i.CityCode
                WHERE i.ImageID NOT IN (SELECT ImageID FROM deleted)
                GROUP BY c.CityCode
                ORDER BY photo_count DESC
                LIMIT 1";
        $result = $this->fetchSingle($sql);
        return $result ? $result['city_name'] : 'N/A';
    }

    /**
     * Fetch users whose photos are flagged.
     */
    public function getFlaggedUsers() {
        $sql = "SELECT DISTINCT u.FirstName AS first_name, u.LastName AS last_name
                FROM users u
                INNER JOIN imagedetails i ON u.UserID = i.UserID
                INNER JOIN flagged f ON i.ImageID = f.ImageID";
        return $this->fetchAll($sql);
    }

    /**
     * Fetch a single record from the database.
     */
    public function fetchSingle($sql, $params = []) {
        return $this->db_helper->run($sql, $params)->fetch();
    }

    /**
     * Fetch all records from the database.
     */
    public function fetchAll($sql, $params = []) {
        return $this->db_helper->run($sql, $params)->fetchAll();
    }

    /**
     * Run a general SQL query (for inserts, updates, deletes).
     */
    public function run($sql, $params = []) {
        $this->db_helper->run($sql, $params);
    }


    // Search locations by continent, country, or city
    public function searchLocations($query) {
        $sql = "
            SELECT c.ContinentName AS name, COUNT(i.ImageID) AS count
            FROM continents c
            LEFT JOIN imagedetails i ON c.ContinentCode = i.ContinentCode
            LEFT JOIN deleted d ON i.ImageID = d.ImageID
            WHERE c.ContinentName LIKE :query
            AND d.ImageID IS NULL
            GROUP BY c.ContinentCode
            HAVING count > 0
    
            UNION
    
            SELECT co.CountryName AS name, COUNT(i.ImageID) AS count
            FROM countries co
            LEFT JOIN imagedetails i ON co.ISO = i.CountryCodeISO
            LEFT JOIN deleted d ON i.ImageID = d.ImageID
            WHERE co.CountryName LIKE :query
            AND d.ImageID IS NULL
            GROUP BY co.ISO
            HAVING count > 0
    
            UNION
    
            SELECT ci.AsciiName AS name, COUNT(i.ImageID) AS count
            FROM cities ci
            LEFT JOIN imagedetails i ON ci.CityCode = i.CityCode
            LEFT JOIN deleted d ON i.ImageID = d.ImageID
            WHERE ci.AsciiName LIKE :query
            AND d.ImageID IS NULL
            GROUP BY ci.CityCode
            HAVING count > 0
        ";

        return $this->fetchAll($sql, ['query' => "$query%"]); // Match beginning of the string
    }



    // Fetch photos by location (continent, country, city)
    public function getPhotosByLocation($searchTerm) {
        // Updated SQL query to search for the location (city, country, or continent)
        $sql = "
            SELECT i.ImageID, i.Title, i.Path, ci.AsciiName AS city, co.CountryName AS country, u.FirstName AS UserFirstName, u.LastName AS UserLastName,
                CASE 
                    WHEN f.ImageID IS NOT NULL THEN 1
                    ELSE 0
                END AS flagged
            FROM imagedetails i
            LEFT JOIN cities ci ON i.CityCode = ci.CityCode
            LEFT JOIN countries co ON i.CountryCodeISO = co.ISO
            LEFT JOIN users u ON i.UserID = u.UserID
            LEFT JOIN flagged f ON i.ImageID = f.ImageID
            LEFT JOIN continents c ON i.ContinentCode = c.ContinentCode
            LEFT JOIN deleted d ON i.ImageID = d.ImageID
            WHERE (ci.AsciiName = :searchTerm OR co.CountryName = :searchTerm OR c.ContinentName = :searchTerm)
            AND d.ImageID IS NULL
        "; // Exclude images that are in the deleted table

        return $this->fetchAll($sql, ['searchTerm' => $searchTerm]);
    }





    // Fetch a specific photo's details

    public function getPhotoDetails($photoId) {
        $sql = "SELECT 
                    i.ImageID, 
                    i.Title, 
                    i.Description, 
                    i.Latitude, 
                    i.Longitude, 
                    i.Exif, 
                    i.ActualCreator, 
                    i.Path, 
                    ci.AsciiName AS city, 
                    co.CountryName AS country, 
                    i.CreatorURL,
                    AVG(ir.Rating) AS AverageRating,  -- Get the average rating from the imagerating table
                    IF(f.ImageID IS NOT NULL, 1, 0) AS Flagged  -- Check if the photo is flagged
                FROM 
                    imagedetails i
                LEFT JOIN 
                    cities ci ON i.CityCode = ci.CityCode
                LEFT JOIN 
                    countries co ON i.CountryCodeISO = co.ISO
                LEFT JOIN 
                    imagerating ir ON i.ImageID = ir.ImageID  -- Join with imagerating table to get the rating
                LEFT JOIN
                    flagged f ON i.ImageID = f.ImageID  -- Join with flagged table to check if photo is flagged
                WHERE 
                    i.ImageID = :photoId
                GROUP BY 
                    i.ImageID, ci.CityCode, co.ISO";  // Group by ImageID, CityCode, and CountryCode to handle the AVG function

        return $this->fetchSingle($sql, ['photoId' => $photoId]);
    }




    // Fetch country details
    public function getCountryDetails($countryCode) {
        $sql = "SELECT co.CountryName AS name, co.CurrencyName, co.Population, co.Capital, co.Languages, co.Neighbours, co.CountryDescription
                FROM countries co
                WHERE co.ISO = :countryCode";

        $result = $this->fetchSingle($sql, ['countryCode' => $countryCode]);

        // Check if result is valid
        if ($result) {
            return $result;
        } else {
            return null;  // or throw an exception, depending on your error handling strategy
        }
    }
    public function getCountryDetailsByName($countryName) {
        // Modify the query to fetch country details along with languages and neighbours
        $sql = "
            SELECT 
                co.CountryName AS name, 
                co.CurrencyName, 
                co.Population, 
                co.Capital, 
                co.CountryDescription,
                co.ISO,
                co.Languages,
                co.Neighbours
            FROM countries co
            WHERE co.CountryName = :countryName";

        // Fetch the country details
        $countryDetails = $this->fetchSingle($sql, ['countryName' => $countryName]);

        // If country details exist, fetch the language names
        if ($countryDetails && isset($countryDetails['Languages'])) {
            // Split the language codes (if any) and get language names
            $languageCodes = explode(',', $countryDetails['Languages']);  // Split language codes

            // Fetch language names from the languages table
            $languageNames = [];
            foreach ($languageCodes as $code) {
                $code = trim($code);  // Clean up any extra spaces
                $sqlLang = "SELECT name FROM languages WHERE iso = :iso";
                $language = $this->fetchSingle($sqlLang, ['iso' => $code]);

                if ($language && isset($language['name'])) {
                    $languageNames[] = $language['name'];  // Add the language name to the list
                }
            }

            // Update the country details with the language names
            $countryDetails['Languages'] = $languageNames;
        }

        // If country has neighbours, fetch their names
        if ($countryDetails && isset($countryDetails['Neighbours'])) {
            // Split the neighbour codes (if any) and get country names
            $neighbourCodes = explode(',', $countryDetails['Neighbours']);  // Split neighbour codes
            $neighbourNames = [];

            foreach ($neighbourCodes as $code) {
                $code = trim($code);  // Clean up any extra spaces
                $sqlNeighbour = "SELECT CountryName FROM countries WHERE ISO = :iso";
                $neighbour = $this->fetchSingle($sqlNeighbour, ['iso' => $code]);

                if ($neighbour && isset($neighbour['CountryName'])) {
                    $neighbourNames[] = $neighbour['CountryName'];  // Add the neighbour's name
                }
            }

            // Update the country details with the neighbour names
            $countryDetails['Neighbours'] = $neighbourNames;
        }

        return $countryDetails;
    }
}
