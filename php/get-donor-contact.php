<?php

session_start();

require_once __DIR__ . "/db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
    exit;
}

$donorId = $_POST["donor_id"] ?? "";

if (!is_numeric($donorId)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid donor."
    ]);
    exit;
}

$result = pg_query_params(
    $conn,
    "SELECT full_name, blood_type, phone, location
     FROM donors
     WHERE id = $1
     AND available = TRUE",
    [$donorId]
);

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Donor could not be found."
    ]);
    exit;
}

$donor = pg_fetch_assoc($result);

echo json_encode([
    "success" => true,
    "donor" => [
        "full_name" => $donor["full_name"],
        "blood_type" => $donor["blood_type"],
        "phone" => $donor["phone"],
        "location" => $donor["location"]
    ]
]);

?>