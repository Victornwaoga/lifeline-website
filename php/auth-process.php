<?php

session_start();

require_once __DIR__ . "/db.php";

header("Content-Type: application/json");


/* ONLY POST REQUESTS */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}


/* GET ACTION */

$action = $_POST["action"] ?? "";


/* LOGIN */

if ($action === "login") {

    $identifier =
        trim($_POST["identifier"] ?? "");

    $password =
        $_POST["password"] ?? "";


    if ($identifier === "" || $password === "") {

        echo json_encode([
            "success" => false,
            "message" => "Please enter your login details."
        ]);

        exit;
    }


    /* Find user by email OR phone */

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
            "message" => "Database error."
        ]);

        exit;
    }


    $user = pg_fetch_assoc($result);


    /* User not found */

    if (!$user) {

        echo json_encode([
            "success" => false,
            "message" => "Incorrect email/phone or password."
        ]);

        exit;
    }


    /* Check password */

    if (!password_verify(
        $password,
        $user["password_hash"]
    )) {

        echo json_encode([
            "success" => false,
            "message" => "Incorrect email/phone or password."
        ]);

        exit;
    }


    /* CREATE LOGIN SESSION */

    $_SESSION["user_id"] =
        $user["id"];

    $_SESSION["user_name"] =
        $user["name"];

    $_SESSION["user_email"] =
        $user["email"];


    echo json_encode([

        "success" => true,

        "message" =>
            "Login successful.",

        "user" => [
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"]
        ]

    ]);

    exit;
}


/* UNKNOWN ACTION */

echo json_encode([
    "success" => false,
    "message" => "Unknown request."
]);

?>