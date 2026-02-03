<?php
error_reporting(0);
ini_set('display_errors', 0);


function checkUrlProtocol($url)
{
    $parsedUrl = parse_url($url);
    return isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : 'invalid';
}

// Automatically get the current URL
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
    . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Get the protocol from the current URL
$request = checkUrlProtocol($currentUrl);

// Default configurations
define("HOST", "localhost");

// Determine if online or offline
$isLocalhost = ($_SERVER['HTTP_HOST'] === 'localhost');

// Database connection (Only use one based on environment)
$connection = null;

if ($isLocalhost) {
    // Offline (Localhost)
    $domain = "http://localhost/socialmedia_booster1/";

    define("USER", "root");
    define("PASSWORD", "");
    define("DATABASE", "billz-crypto");

    // Database connection
    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

  
} else {
    // Online (Live Server)
    $domain = "https://quanstofy.com/";

    define("USER", "quanstof_billz-crypto");
    define("PASSWORD", "quanstof_billz-crypto");
    define("DATABASE", "quanstof_billz-crypto");

    // Database connection
    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

   
}

// Site configurations
$sitename = "Quanstofy";

// Email Config 
$siteemail = "support@quanstofy.com";
$emailpassword  = "support@quanstofy.com";
$host = 'mail.quanstofy.com';
$sitephone  = "+44 776 0957 798";
$siteaddress  = "Weston, New York";

session_start();
