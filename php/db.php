<?php

$host = "localhost";
$port = "5432";
$dbname = "lifeline";
$user = "postgres";
$password = "~!qw@#$%";

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if ($conn === false) {
    die("Database connection failed.");
}

?>