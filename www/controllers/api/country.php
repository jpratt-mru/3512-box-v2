<?php
require_once '../database/DatabaseQueries.php';

$databaseQueries = new DatabaseQueries();

if (isset($_GET['country'])) {
    $countryName = $_GET['country'];  // Get the country name from the URL

    // Fetch country details from the database by country name
    $countryDetails = $databaseQueries->getCountryDetailsByName($countryName);

    // Check if the country details were found
    if ($countryDetails) {
        // Check for missing values and set default ones
        $countryDescription = isset($countryDetails['CountryDescription']) ? $countryDetails['CountryDescription'] : 'No description available';
        $population = isset($countryDetails['Population']) && $countryDetails['Population'] !== null ? number_format($countryDetails['Population']) : 'Data not available';
        $capital = isset($countryDetails['Capital']) ? $countryDetails['Capital'] : 'Not available';
        $currency = isset($countryDetails['CurrencyName']) ? $countryDetails['CurrencyName'] : 'Not available';

        // Handle languages (if it's a string, split it, if it's already an array, use it directly)
        if (isset($countryDetails['Languages'])) {
            if (is_string($countryDetails['Languages'])) {
                $languages = explode(',', $countryDetails['Languages']);
            } elseif (is_array($countryDetails['Languages'])) {
                $languages = $countryDetails['Languages'];
            } else {
                $languages = ['No languages available'];
            }

            // Format the language codes (remove any extra parts like 'en-US')
            foreach ($languages as &$language) {
                $language = strtok($language, '-');  // Get the part before the hyphen
            }
        } else {
            $languages = ['No languages available'];
        }

        // Handle neighbours: split them if it's a string, or use the value directly
        $neighbours = isset($countryDetails['Neighbours']) && $countryDetails['Neighbours'] !== null
            ? (is_string($countryDetails['Neighbours']) ? explode(',', $countryDetails['Neighbours']) : $countryDetails['Neighbours'])
            : ['No neighbouring countries available'];

        // Return the valid JSON response with country details
        header('Content-Type: application/json');
        echo json_encode([
            'name' => $countryName,  // Use the country name directly from the query parameter
            'flagUrl' => "https://flagcdn.com/224x168/" . strtolower($countryDetails['ISO']) . ".png",  // Flag URL
            'description' => $countryDescription,
            'population' => $population,
            'capital' => $capital,
            'currency' => $currency,
            'languages' => $languages,
            'neighbours' => $neighbours
        ]);
    } else {
        // Handle country not found
        http_response_code(404);  // Not Found
        echo json_encode(['error' => 'Country not found']);
    }
} else {
    // Handle missing country parameter
    http_response_code(400);  // Bad Request
    echo json_encode(['error' => 'Country parameter is missing']);
}
