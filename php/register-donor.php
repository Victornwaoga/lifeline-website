<?php

session_start();

require_once __DIR__ . "/db.php";

header("Content-Type: application/json");


/* =========================================
   CHECK REQUEST
========================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}


/* =========================================
   CHECK LOGIN
========================================= */

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "You must be logged in before registering as a donor."
    ]);

    exit;
}


$userId = $_SESSION["user_id"];


/* =========================================
   GET FORM DATA
========================================= */

$fullName =
    trim($_POST["full_name"] ?? "");

$bloodType =
    trim($_POST["blood_type"] ?? "");

$phone =
    trim($_POST["phone"] ?? "");

$location =
    trim($_POST["location"] ?? "");

$lastDonationDate =
    trim($_POST["last_donation_date"] ?? "");

$latitude =
    trim($_POST["latitude"] ?? "");

$longitude =
    trim($_POST["longitude"] ?? "");

$consent =
    isset($_POST["consent"]);


/* =========================================
   REQUIRED FIELDS
========================================= */

if (
    $fullName === "" ||
    $bloodType === "" ||
    $phone === "" ||
    $location === "" ||
    !$consent
) {

    echo json_encode([
        "success" => false,
        "message" => "Please complete all required fields."
    ]);

    exit;
}


/* =========================================
   VALID BLOOD TYPE
========================================= */

$validBloodTypes = [
    "A+",
    "A-",
    "B+",
    "B-",
    "AB+",
    "AB-",
    "O+",
    "O-"
];


if (!in_array($bloodType, $validBloodTypes, true)) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid blood type."
    ]);

    exit;
}


/* =========================================
   OPTIONAL DATE
========================================= */

if ($lastDonationDate === "") {
    $lastDonationDate = null;
}


/* =========================================
   OPTIONAL GPS
========================================= */

if ($latitude === "") {
    $latitude = null;
}

if ($longitude === "") {
    $longitude = null;
}


/* =========================================
   INSERT DONOR
========================================= */

$result = pg_query_params(

    $conn,

    "INSERT INTO donors
    (
        user_id,
        full_name,
        blood_type,
        phone,
        location,
        last_donation_date,
        latitude,
        longitude,
        available,
        consent
    )
    VALUES
    (
        $1,
        $2,
        $3,
        $4,
        $5,
        $6,
        $7,
        $8,
        TRUE,
        $9
    )
    RETURNING id",

    [
        $userId,
        $fullName,
        $bloodType,
        $phone,
        $location,
        $lastDonationDate,
        $latitude,
        $longitude,
        $consent
    ]
);


/* =========================================
   CHECK DATABASE RESULT
========================================= */

if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Unable to register donor."
    ]);

    exit;
}


/* =========================================
   GET DONOR ID
========================================= */

$donor =
    pg_fetch_assoc($result);


/* =========================================
   SUCCESS
========================================= */

echo json_encode([

    "success" => true,

    "message" =>
        "You have been successfully registered as a donor.",

    "donor_id" =>
        $donor["id"]

]);

?>