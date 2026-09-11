<?php

if (getenv("DATABASE_URL")) {

    // Render PostgreSQL
    $databaseUrl = getenv("DATABASE_URL");

    $db = parse_url($databaseUrl);

    $host = $db["host"];
    $port = $db["port"] ?? 5432;
    $dbname = ltrim($db["path"], "/");
    $user = $db["user"];
    $password = $db["pass"];

} else {

    // Local PostgreSQL
    $host = "localhost";
    $port = "5432";
    $dbname = "lifeline";
    $user = "postgres";
    $password = "";
}

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if ($conn === false) {
    die("Database connection failed.");
}

?>