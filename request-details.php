<?php

session_start();

require_once __DIR__ . "/php/db.php";

$request_id = $_GET["id"] ?? "";

if (!ctype_digit($request_id)) {
    die("Invalid request.");
}

$query = "
    SELECT
        id,
        patient_name,
        blood_type,
        hospital,
        location,
        urgency,
        units_needed,
        needed_by,
        contact_phone,
        additional_info,
        created_at
    FROM urgent_requests
    WHERE id = $1
    LIMIT 1
";

$result = pg_query_params($conn, $query, [$request_id]);

if (!$result || pg_num_rows($result) === 0) {
    die("Urgent request not found.");
}

$request = pg_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details | LifeLine</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header class="site-header">
    <div class="container header-container">
        <a href="first-page.php" class="logo">
            <span class="logo-icon" aria-hidden="true"><span class="logo-drop"><img src="images/blood-bank.svg" alt="LifeLine"></span></span>
            <span class="logo-text">Life<span>Line</span></span>
        </a>

        <button class="menu-button" type="button">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-nav">
            <a href="first-page.php">Home</a>
            <a href="find-donor.php">Find a Donor</a>
            <a href="become-donor.php">Become a Donor</a>
            <a href="urgent-requests.php" class="active">Urgent Requests</a>
            <a href="emergency-resources.php">Emergency Resources</a>
        </nav>
    </div>
</header>

<main>
<section class="container hero-container">
    <div class="hero-content">
        <a class="hero-actions" href="urgent-requests.php">← Back to Urgent Requests</a>
        <div class="request-details-card">
            <span class="section-label"><?= htmlspecialchars(strtoupper($request["urgency"])) ?></span>
            <h1><?= htmlspecialchars($request["blood_type"]) ?>Blood Needed</h1>
            <p>
                <?= htmlspecialchars($request["units_needed"]) ?>
                unit<?= $request["units_needed"] != 1 ? "s" : "" ?>
                required
            </p>


            <hr>

            <h2>Request Information</h2>

            <p>
                <strong>Patient:</strong><br>
                <?= htmlspecialchars($request["patient_name"]) ?>
            </p>

            <p>
                <strong>Hospital:</strong><br>
                <?= htmlspecialchars($request["hospital"]) ?>
            </p>

            <p>
                <strong>Location:</strong><br>
                <?= htmlspecialchars($request["location"]) ?>
            </p>

            <p>
                <strong>Needed by:</strong><br>
                <?= htmlspecialchars(
                    date(
                        "F j, Y \a\\t g:i A",
                        strtotime($request["needed_by"])
                    )
                ) ?>
            </p>

            <?php if (!empty($request["additional_info"])): ?>

                <p>
                    <strong>Additional information:</strong><br>
                    <?= nl2br(
                        htmlspecialchars($request["additional_info"])
                    ) ?>
                </p>
            <?php endif; ?>


            <div class="request-actions">

                <a href="matching-donors.php?request_id=<?= urlencode($request["id"]) ?>"
                    class="request-button">Find Matching Donors<span>&#10142;</span></a>

            </div>
        </div>
    </div>
</section>
</main>

<script src="js/urgent-requests.js"></script>
</body>
</html>