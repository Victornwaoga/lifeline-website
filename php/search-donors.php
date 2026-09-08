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

$blood_type = trim($_POST["blood_type"] ?? "");
$location = trim($_POST["location"] ?? "");

/*
|--------------------------------------------------------------------------
| Build the search query
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        full_name,
        blood_type,
        phone,
        location,
        available
    FROM donors
    WHERE available = TRUE
";

$params = [];
$param_number = 1;

/*
|--------------------------------------------------------------------------
| Blood type filter
|--------------------------------------------------------------------------
*/

if ($blood_type !== "") {
    $sql .= " AND blood_type = $" . $param_number;
    $params[] = $blood_type;
    $param_number++;
}

/*
|--------------------------------------------------------------------------
| Location filter
|--------------------------------------------------------------------------
*/

if ($location !== "") {
    $sql .= " AND LOWER(location) LIKE LOWER($" . $param_number . ")";
    $params[] = "%" . $location . "%";
    $param_number++;
}

$sql .= " ORDER BY id DESC";

$result = pg_query_params($conn, $sql, $params);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Unable to search donors."
    ]);
    exit;
}

$donors = [];

while ($donor = pg_fetch_assoc($result)) {

    $donors[] = [
        "id" => $donor["id"],
        "full_name" => $donor["full_name"],
        "blood_type" => $donor["blood_type"],
        "location" => $donor["location"],
        "available" => $donor["available"]
    ];
}

echo json_encode([
    "success" => true,
    "donors" => $donors,
    "count" => count($donors)
]);

?>