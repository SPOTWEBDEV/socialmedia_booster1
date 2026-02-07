<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("HOST", "localhost");

$isLocalhost = ($_SERVER['HTTP_HOST'] === 'localhost');

if ($isLocalhost) {
    // Local
    define("USER", "root");
    define("PASSWORD", "");
    define("DATABASE", "boost");
    $domain = "http://localhost/socialmedia_booster1/";
} else {
    // Live
    define("USER", "boostkore_user");
    define("PASSWORD", "boostkore_password");
    define("DATABASE", "boostkore_db");
    $domain = "https://boostkore.com/";
}

// Object-oriented mysqli connection
$connection = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

// Site config
$sitename = "Boostkore";
$siteemail = "support@boostkore.com";
$sitephone = "+44 776 0957 798";

session_start();

$projectID = '69875132aebfc2d5a7e9a1cc';
$publicKey = 'pk_live-64e7b375350e4b519092237a4de9db2d';
