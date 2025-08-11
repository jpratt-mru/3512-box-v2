<?php
return [
    'host' => 'database',      // Database host. 💬 This USED to be `localhost`, but since the Docker service in our case is called `database`, that's what needs to go here.
    'port' => '3306',           // MySQL default port
    'dbname' => getenv('DB_NAME'),       // Name of your database
    'username' => 'root',       // Database username
    'password' => getenv('DB_PASSWORD'),       // Database password
    'charset' => 'utf8mb4',     // Charset for consistent encoding
];
