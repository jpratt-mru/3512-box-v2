<?php

class Router {
    private $db;
    private $routes;
    private $requestUri;

    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Database connection
        require_once '../database/DatabaseQueries.php';
        $this->db = new DatabaseQueries();

        // Get the request URI
        $this->requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Define routes
        $this->routes = [
            '/' => '../views/home.php',  // SPA main entry point (to load the search view)
            '/admin' => '../controllers/admin/login/create.php', // Admin login page
            '/admin/dashboard/stats' => '../controllers/admin/stats/index.php', // Admin stats dashboard
            '/admin/dashboard/photos' => '../controllers/admin/photos/index.php', // Photo Dashboard
            '/admin/dashboard/photos/flag' => '../controllers/admin/photos/flag.php', // Flag photo
            '/admin/dashboard/photos/unflag' => '../controllers/admin/photos/unflag.php', // Unflag photo
            '/admin/dashboard/photos/delete' => '../controllers/admin/photos/delete.php', // Delete photo
            '/admin/dashboard/photos/restore' => '../controllers/admin/photos/restore.php', // Restore photo
            '/admin/logout' => '../controllers/admin/logout/logout.php', // Logout

            // API routes for JS to consume
            '/api/search' => '../controllers/api/search.php', // Search API
            '/api/photos' => '../controllers/api/photo-location.php', // Fetch photos by location
            '/api/photo' => '../controllers/api/photo.php', // Fetch specific photo details
            '/api/country' => '../controllers/api/country.php', // Fetch country details
        ];
    }

    // Method to match the route and include the corresponding controller
    public function dispatch() {
        // Check if the requested URI matches a defined route
        if (array_key_exists($this->requestUri, $this->routes)) {
            include $this->routes[$this->requestUri];
        } else {
            // If route doesn't exist, return 404
            http_response_code(404);
            include '../views/404.php'; // Custom 404 page
        }
    }
}
