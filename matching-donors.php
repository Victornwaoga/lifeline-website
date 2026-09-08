<?php

session_start();

require_once __DIR__ . "/php/db.php";

$request_id = $_GET["request_id"] ?? "";

if (!ctype_digit($request_id)) {
    die("Invalid request.");
}

/*
|--------------------------------------------------------------------------
| Get the urgent request
|--------------------------------------------------------------------------
*/

$request_query = "
    SELECT
        id,
        patient_name,
        blood_type,
        hospital,
        location,
        urgency,
        units_needed,
        needed_by,
        contact_phone
    FROM urgent_requests
    WHERE id = $1
    LIMIT 1
";

$request_result = pg_query_params(
    $conn,
    $request_query,
    [$request_id]
);

if (!$request_result || pg_num_rows($request_result) === 0) {
    die("Urgent request not found.");
}

$request = pg_fetch_assoc($request_result);


/*
|--------------------------------------------------------------------------
| Find available donors with the requested blood type
|--------------------------------------------------------------------------
*/



$donor_query = "
    SELECT
        id,
        full_name,
        blood_type,
        phone,
        location,
        CASE
            WHEN LOWER(location) = LOWER($1) THEN 0
            WHEN LOWER(location) LIKE LOWER($2) THEN 1
            ELSE 2
        END AS location_priority
    FROM donors
    WHERE available = TRUE
      AND blood_type = $3
    ORDER BY location_priority ASC, id DESC
";

$location_pattern = "%" . $request["location"] . "%";

$donor_result = pg_query_params(
    $conn,
    $donor_query,
    [
        $request["location"],
        $location_pattern,
        $request["blood_type"]
    ]
);

if (!$donor_result) {
    die("Unable to find matching donors.");
}

$donors = [];

while ($donor = pg_fetch_assoc($donor_result)) {
    $donors[] = $donor;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Potential LifeLine donors for an urgent blood request."
    >

    <title>Matching Donors | LifeLine</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header class="site-header">

    <div class="header-container">

        <a href="index.php" class="logo">

            <span class="logo-icon"></span>

            <span class="logo-text">
                LifeLine
            </span>

        </a>

        <button
            class="menu-button"
            type="button"
            aria-label="Open navigation menu"
            aria-expanded="false"
        >

            <span></span>
            <span></span>
            <span></span>

        </button>

        <nav
            class="main-nav"
            aria-label="Main navigation"
        >

            <a href="index.php">
                Home
            </a>

            <a href="find-donor.php">
                Find a Donor
            </a>

            <a href="become-donor.php">
                Become a Donor
            </a>

            <a href="urgent-requests.php">
                Urgent Requests
            </a>

            <a href="emergency-resources.php">
                Emergency Resources
            </a>

        </nav>

    </div>

</header>


<main>

<section class="request-hero">

    <div class="container">

        <span class="section-label">
            POTENTIAL MATCHES
        </span>

        <h1>
            Donors who may be able to help.
        </h1>

        <p>
            These are registered donors who are currently marked
            as available and have the requested blood type.
        </p>

    </div>

</section>


<section class="request-board">

    <div class="container">

        <div class="board-header">

            <div>

                <span class="section-label">
                    URGENT REQUEST
                </span>

                <h2>
                    <?= htmlspecialchars($request["blood_type"]) ?>
                    blood needed
                </h2>

            </div>

        </div>


        <div class="request-card">

            <div class="blood-request">

                <div class="blood-icon">

                    <?= htmlspecialchars(
                        $request["blood_type"]
                    ) ?>

                </div>

                <div>

                    <h3>
                        <?= htmlspecialchars(
                            $request["patient_name"]
                        ) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars(
                            $request["units_needed"]
                        ) ?>

                        unit<?= $request["units_needed"] != 1 ? "s" : "" ?>

                        required
                    </p>

                </div>

            </div>


            <div class="request-details">

                <div class="detail">

                    <strong>
                        Hospital
                    </strong>

                    <p>
                        <?= htmlspecialchars(
                            $request["hospital"]
                        ) ?>
                    </p>

                </div>

                <div class="match-request-location">
                    <strong>Request Location:</strong>
                    <?= htmlspecialchars($request["location"]) ?>
                </div>


                <div class="detail">

                    <strong>
                        Location
                    </strong>

                    <p>
                        <?= htmlspecialchars(
                            $request["location"]
                        ) ?>
                    </p>

                </div>


                <div class="detail">

                    <strong>
                        Needed by
                    </strong>

                    <p>
                        <?= htmlspecialchars(
                            date(
                                "F j, Y \a\\t g:i A",
                                strtotime($request["needed_by"])
                            )
                        ) ?>
                    </p>

                </div>

            </div>

        </div>


        <div style="margin-top: 40px;">

            <span class="section-label">
                AVAILABLE DONORS
            </span>

            <h2>
                <?= count($donors) ?>
                potential donor<?= count($donors) != 1 ? "s" : "" ?>
                found
            </h2>

        </div>


        <?php if (count($donors) === 0): ?>

            <div class="no-requests">

                <h3>
                    No matching donors found
                </h3>

                <p>
                    There are currently no available registered
                    donors with this blood type.
                </p>

            </div>

        <?php else: ?>

            <div class="donor-grid">

                <?php foreach ($donors as $donor): ?>

                    <?php

                    $nameParts = preg_split(
                        '/\s+/',
                        trim($donor["full_name"])
                    );

                    $initials = "";

                    foreach ($nameParts as $part) {

                        if ($part !== "") {

                            $initials .= strtoupper(
                                substr($part, 0, 1)
                            );

                        }

                    }

                    $initials = substr(
                        $initials,
                        0,
                        2
                    );

                    ?>

                    <article class="donor-card">

                        <div class="donor-card-top">

                            <div class="donor-avatar">

                                <?= htmlspecialchars(
                                    $initials
                                ) ?>

                            </div>

                            <div class="blood-badge">

                                <?= htmlspecialchars(
                                    substr(
                                        $donor["blood_type"],
                                        0,
                                        -1
                                    )
                                ) ?>

                                <span>

                                    <?= htmlspecialchars(
                                        substr(
                                            $donor["blood_type"],
                                            -1
                                        )
                                    ) ?>

                                </span>

                            </div>

                        </div>


                        <h3>

                            <?= htmlspecialchars(
                                $donor["full_name"]
                            ) ?>

                        </h3>


                        <p class="donor-location">

                            <?= htmlspecialchars(
                                $donor["location"]
                            ) ?>

                        </p>


                        <div class="donor-status">

                            <span class="status-dot"></span>

                            Available donor

                        </div>

                        <?php
                           $request_location = strtolower(trim($request["location"]));
                           $donor_location = strtolower(trim($donor["location"]));

                           $same_location = (
                           $request_location === $donor_location
                      );

                        $near_location = (
                       strpos($donor_location, $request_location) !== false ||
                        strpos($request_location, $donor_location) !== false
                       );
                      ?>

                     <?php if ($same_location): ?>

                     <div class="location-match same-location">
                     ✓ Same Location
                     </div>

                      <?php elseif ($near_location): ?>

                     <div class="location-match nearby-location">
                       ✓ Location Match
                    </div>

                    <?php endif; ?>


                        <button
                            class="contact-button"
                            type="button"
                            data-donor-id="<?= htmlspecialchars(
                                $donor["id"]
                            ) ?>"
                        >

                            View Contact
                            <span>→</span>

                        </button>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <div class="directory-notice">

            <span>ⓘ</span>

            <p>
                A matching blood type does not by itself confirm
                medical suitability. Donor availability and
                suitability should always be confirmed through
                the appropriate medical professionals.
            </p>

        </div>

    </div>

</section>
</main>


<footer class="site-footer">
    <div class="container footer-container">
        <div class="footer-brand">
            <a href="first-page.php" class="logo footer-logo">
                <span class="logo-icon">
                    <span class="logo-drop">
                        <img src="images/blood-bank.svg" alt="LifeLine">
                    </span>
                </span>
                <span class="logo-text">Life<span>Line</span></span>
            </a>
            <p>Blood Donation & Emergency Response Network.</p>
        </div>
    </div>
</footer>


<script src="js/urgent-requests.js"></script>
<script src="js/matching-donors.js"></script>
</body>

</html>