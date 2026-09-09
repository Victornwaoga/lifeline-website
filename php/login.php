<?php

session_start();

require_once __DIR__ . "/db.php";

header("Content-Type: application/json");


/* ONLY ALLOW POST REQUESTS */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}


/* GET FORM DATA */

$identifier = trim($_POST["identifier"] ?? "");
$password = $_POST["password"] ?? "";


/*  VALIDATION */

if ($identifier === "" || $password === "") {

    echo json_encode([
        "success" => false,
        "message" => "Please enter your email/phone and password."
    ]);

    exit;
}


/* FIND USER */

$result = pg_query_params(
    $conn,
    "SELECT id, name, email, phone, password_hash
     FROM users
     WHERE email = $1 OR phone = $1
     LIMIT 1",
    [$identifier]
);


if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Unable to access your account."
    ]);

    exit;
}


/* USER NOT FOUND */

if (pg_num_rows($result) === 0) {

    echo json_encode([
        "success" => false,
        "message" => "Incorrect email/phone or password."
    ]);

    exit;
}


/* GET USER */

$user = pg_fetch_assoc($result);


/* CHECK PASSWORD */

if (!password_verify($password, $user["password_hash"])) {

    echo json_encode([
        "success" => false,
        "message" => "Incorrect email/phone or password."
    ]);

    exit;
}


/* CREATE SESSION */

session_regenerate_id(true);

$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["name"];
$_SESSION["user_email"] = $user["email"];


/* SUCCESS */

echo json_encode([

    "success" => true,

    "message" => "Login successful.",

    "user" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"]
    ]

]);

?>