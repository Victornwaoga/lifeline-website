<?php

$host = "localhost";
$port = "";
$dbname = "lifeline";
$user = "postgres";
$password = "PASSWORD";

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if ($conn === false) {
    die("Database connection failed.");
}

?>