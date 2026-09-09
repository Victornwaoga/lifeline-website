<?php 
    session_start(); require_once __DIR__ . "/php/db.php"; 
    /* Get available donors from PostgreSQL. */ 
    $query = " SELECT id, 
         full_name, 
         blood_type, 
         phone, 
         location, 
         available FROM donors WHERE available = TRUE ORDER BY id DESC "; 
    $result = pg_query($conn, $query); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find potential blood donors near you with LifeLine.">
    <title>Find a Donor | LifeLine</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/find-donor.css">
</head>
<body>

<header class="site-header">
    <div class="container header-container">
        <a href="first-page.php" class="logo" aria-label="LifeLine Home">
            <span class="logo-icon"><span class="logo-drop"><img src="images/blood-bank.svg" alt=""></span></span>
            <span class="logo-text">Life<span>Line</span></span>
        </a>

        <button class="menu-button" type="button" aria-label="Open navigation menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="first-page.php">Home</a>
            <a href="find-donor.php" class="active">Find a Donor</a>
            <a href="become-donor.php">Become a Donor</a>
            <a href="urgent-requests.php">Urgent Requests</a>
            <a href="emergency-resources.php">Emergency Resources</a>
        </nav>
    </div>
</header>

<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow"><span class="pulse-dot"></span>FIND A POTENTIAL DONOR</span>
            <h1>Find a donor <span>when it matters.</span></h1>
            <p>Search the LifeLine network by blood type and location to find potential donors near the area where blood 
                is needed.</p>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <div class="search-box">
                <div class="search-heading">
                    <div class="search-icon"></div>
                    <div>
                        <span class="section-label">DONOR DIRECTORY</span>
                        <h2>Search potential donors</h2>
                        <p>Select a blood type and enter an area to narrow your search.</p>
                    </div>
                </div>

                <form class="donor-search-form" id="donorSearchForm">
                    <div class="form-group">
                        <label for="bloodType">Blood Type</label>
                        <select id="bloodType" name="bloodType">
                            <option value="">All Blood Types</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location">Location/Area</label>
                        <div class="input-wrapper">
                            <span class="input-icon"></span>
                            <input type="search" id="location" name="location" placeholder="e.g. Abakaliki" autocomplete="on">
                        </div>
                    </div>
                    <button type="submit" class="search-button"><span></span>Search Donors</button>
                </form>
            </div>
        </div>
    </section>

    <section class="results-section">
        <div class="container">
            <div class="results-header">
                <div>
                    <span class="section-label">DONOR RESULTS</span>
                    <h2>Potential Donors</h2>
                </div>

                <span class="results-count" id="resultsCount">0 donors found</span>
            </div>

            <div class="donor-grid" id="donorList">

                <?php
                if ($result && pg_num_rows($result) > 0):
                    while ($donor = pg_fetch_assoc($result)):

                        /* Create donor initials */
                        $nameParts =
                            preg_split(
                                '/\s+/',
                                trim($donor["full_name"])
                            );

                        $initials = "";

                        foreach ($nameParts as $part) {

                            if ($part !== "") {

                                $initials .=
                                    strtoupper(
                                        substr($part, 0, 1)
                                    );
                            }
                        }

                        $initials =
                            substr($initials, 0, 2);
                ?>

                <!-- Real Donor Card -->
                <article class="donor-card" data-blood="<?= htmlspecialchars($donor["blood_type"]) ?>" 
                data-location="<?= htmlspecialchars($donor["location"]) ?>">
                    <div class="donor-card-top">
                        <div class="donor-avatar">
                            <?= htmlspecialchars($initials) ?>
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

                    <div class="donor-status"><span class="status-dot"></span>Potential donor</div>
                    <button class="contact-button" type="button" data-donor-id="<?= htmlspecialchars($donor["id"]) ?>">
                        View Contact <span>→</span>
                    </button>
                </article>

                <?php
                    endwhile;
                endif;
                ?>
            </div>

            <div class="no-results" id="noResults" style="display: none;">
                <div class="no-results-icon">&#128270;</div>
                <h3>No potential donors found</h3>
                <p>Try another blood type or location.</p>
            </div>

            <div class="directory-notice">
                <span>ⓘ</span>
                <p>
                    LifeLine donor listings represent people who have voluntarily registered as potential donors. 
                    Always confirm availability and suitability through the appropriate medical professionals before proceeding.
                </p>
            </div>
        </div>
    </section>

    <section class="join-section">
        <div class="container">
            <div class="join-card">
                <div>
                    <span class="section-label">HELP GROW THE NETWORK</span>
                    <h2>Don't see the donor<span>you need?</span></h2>
                    <p>More registered donors mean more potential connections when blood is urgently needed.</p>
                    <a href="become-donor.php" class="btn-primary">Become a Donor<span>→</span></a>
                </div>
                <div class="join-placeholder">
                     <img src="images/donatorr.jpg" alt="">
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="index.php" class="logo footer-logo">
                    <span class="logo-icon"><span class="logo-drop"></span><img src="images/blood-bank.svg" alt=""></span>
                    <span class="logo-text">Life<span>Line</span></span>
                </a>
                <p>Blood Donation & Emergency Response Network.</p>
            </div>

            <div class="footer-links">
                <div>
                    <h3>LifeLine</h3>
                    <a href="first-page.php">Home</a>
                    <a href="find-donor.php">Find a Donor</a>
                    <a href="become-donor.php">Become a Donor</a>
                </div>

                <div>
                    <h3>Emergency</h3>
                    <a href="urgent-requests.php">Urgent Requests </a>
                    <a href="emergency-resources.php">Emergency Resources</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 LifeLine. All rights reserved.</p>
            <p>Built to connect people when it matters.</p>
        </div>
    </div>
</footer>

<script src="js/find-donor.js"></script>
</body>
</html>