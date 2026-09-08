<?php

require_once __DIR__ . "/db.php";;

header("Content-Type: application/json");


/* =========================================
   ONLY ALLOW POST REQUESTS
========================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}


/* =========================================
   GET FORM DATA
========================================= */

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$password = $_POST["password"] ?? "";


/* =========================================
   REQUIRED FIELDS
========================================= */

if (
    $name === "" ||
    $email === "" ||
    $phone === "" ||
    $password === ""
) {

    echo json_encode([
        "success" => false,
        "message" => "Please fill in all required fields."
    ]);

    exit;
}


/* =========================================
   VALIDATE EMAIL
========================================= */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);

    exit;
}


/* =========================================
   VALIDATE PASSWORD
========================================= */

if (strlen($password) < 8) {

    echo json_encode([
        "success" => false,
        "message" => "Password must contain at least 8 characters."
    ]);

    exit;
}


/* =========================================
   CHECK EMAIL
========================================= */

$result = pg_query_params(
    $conn,
    "SELECT id FROM users WHERE email = $1",
    [$email]
);


if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Unable to check email."
    ]);

    exit;
}


if (pg_num_rows($result) > 0) {

    echo json_encode([
        "success" => false,
        "message" => "This email is already registered."
    ]);

    exit;
}


/* =========================================
   CHECK PHONE
========================================= */

$result = pg_query_params(
    $conn,
    "SELECT id FROM users WHERE phone = $1",
    [$phone]
);


if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Unable to check phone number."
    ]);

    exit;
}


if (pg_num_rows($result) > 0) {

    echo json_encode([
        "success" => false,
        "message" => "This phone number is already registered."
    ]);

    exit;
}


/* =========================================
   HASH PASSWORD
========================================= */

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/* =========================================
   INSERT USER
========================================= */

$result = pg_query_params(
    $conn,

    "INSERT INTO users
    (name, email, phone, password_hash)
    VALUES
    ($1, $2, $3, $4)
    RETURNING id, name",

    [
        $name,
        $email,
        $phone,
        $passwordHash
    ]
);


/* =========================================
   CHECK INSERT
========================================= */

if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Account could not be created."
    ]);

    exit;
}


/* =========================================
   GET NEW USER
========================================= */

$user = pg_fetch_assoc($result);


/* =========================================
   SUCCESS RESPONSE
========================================= */

echo json_encode([

    "success" => true,

    "message" => "Account created successfully.",

    "user" => [
        "id" => $user["id"],
        "name" => $user["name"]
    ]

]);

?>