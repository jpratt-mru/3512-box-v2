<?php

// echo "The value of Foo is: ";
// echo getenv('Foo'); 


# Include the Router class
require_once '../Router.php';

// // Instantiate the Router class and dispatch the request
$router = new Router();
$router->dispatch(); // This will handle routing based on the URL (either API or static view)
