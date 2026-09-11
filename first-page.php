<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: log.php");
    exit;
}


require_once __DIR__ . "/php/db.php";

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
    ORDER BY created_at DESC
";

$result = pg_query($conn, $query);
if (!$result) {
    die("Unable to load urgent requests.");
}

$active_count = pg_num_rows($result);

// Count registered donors
$donor_query = "SELECT COUNT(*) AS total FROM donors";
$donor_result = pg_query($conn, $donor_query);

if (!$donor_result) {
    die("Unable to count registered donors.");
}

$donor_data = pg_fetch_assoc($donor_result);
$donor_count = (int) $donor_data['total'];


// Count urgent requests
$urgent_query = "SELECT COUNT(*) AS total FROM urgent_requests";
$urgent_result = pg_query($conn, $urgent_query);

if (!$urgent_result) {
    die("Unable to count urgent requests.");
}

$urgent_data = pg_fetch_assoc($urgent_result);
$urgent_count = (int) $urgent_data['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lifeLine | Blood Donation & Emergency Response</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-container">
            <a href="first-page.php" class="logo">
                <span class="logo-icon" aria-hidden="true"><span class="logo-drop"><img src="images/blood-bank.svg" alt="LifeLine"></span></span>
                <span class="logo-text">Life<span>Line</span></span>
            </a>

            <!-- Mobile menu button -->
            <button type="button" class="menu-button" aria-label="Open navigation menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="first-page.php" class="active">Home</a>
                <a href="find-donor.php">Find a Donor</a>
                <a href="become-donor.php">Become a Donor</a>
                <a href="urgent-requests.php">Urgent Requests</a>
                <a href="emergency-resources.php">Emergency Resources</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-container">
                <div class="hero-content">
                    <span class="eyebrow"><span class="pulse-dot"></span>BLOOD EMERGENCIES NEED FAST ACTION</span>
                    <h1>Someone near you may need<span> blood today.</span></h1>
                    <p class="hero-question">Will they be able to find you?</p>
                    <p class="hero-description">LifeLine connects people who urgently need blood with potential donors in their area -
                         making it easier to find the right people when time matters.</p>

                    <div class="hero-actions">
                        <a href="become-donor.php" class="btn btn-primary">Become a Donor<span>&#10142;</span></a>
                        <a href="find-donor.php" class="btn btn-secondary">Find a Donor<span>&#10142;</span></a>
                    </div>

                    <div class="hero-trust">
                        <!-- <span class="trust-icon">&#10004;</span> -->
                        <span>Voluntary Donor Registration</span>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="image-placeholder hero-image-placeholder">
                        <p><img src="images/emergency-response.jpg" alt="Emergency Response"></p>
                        <small></small>
                    </div>

                    <!-- The Floating Emergency Card -->
                    <div class="floating-card">
                        <span class="floating-icon"><img src="images/blood-bank.svg" alt=""></span>
                        <div>
                            <strong>Emergency Response</strong>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="statistics">
            <div class="container">
                <div class="section-heading centered">
                    <span class="section-label">LIFELINE TODAY</span>
                    <h2>A growing network<span>ready to connect.</span></h2>
                    <p>Every registered donor increases the possibility of finding help when blood is urgently needed.</p>
                </div>

                <div class="stats-grid">
                    <!-- Registered Donors -->
                    <article class="stat-card">
                       <div class="stat-icon"></div>
                       <strong class="stat-number"><?= $donor_count ?></strong>
                       <h3>Registered Donors</h3>
                       <p>People who have joined the LifeLine donor network.</p>
                    </article>

                     <!-- Urgent Requests -->
                    <article class="stat-card">
                       <div class="stat-icon"></div>
                       <strong class="stat-number"><?= $urgent_count ?></strong>
                       <h3>Urgent Requests</h3>
                       <p>Blood requests posted across the network.</p>
                   </article>

                    <!-- Potential Connections -->
                    <article class="stat-card stat-card-wide">
                       <div class="stat-icon"></div>
                       <strong class="stat-number"><?= $donor_count + $urgent_count ?></strong>
                       <h3>Potential Connections</h3>
                       <p>Potential donor connections made possible through the network.</p>
                   </article>
               </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <div class="section-heading centered">
                    <span class="section-label">HOW IT WORKS</span>
                    <h2>Three steps. <span>One connected network.</span></h2>
                    <p>LifeLine makes the process of finding potential blood donors easier to understand and navigate.</p>
                </div>

                <div class="steps">
                    <article class="step-card">
                        <div class="step-number">01</div>
                        <div class="step-icon"></div>
                        <h3>Register</h3> 
                        <p>People willing to donate provide their blood type, location and basic contact information.</p>
                    </article>

                    <div class="step-arrow" aria-hidden="true">&#8595;</div>

                    <article class="step-card">
                        <div class="step-number">02</div>
                        <div class="step-icon">&#128269;</div>
                        <h3>Search</h3>
                        <p>Search the donor directory by blood type and location when blood is needed.</p>
                    </article>

                    <div class="step-arrow" aria-hidden="true">&#8595;</div>

                    <article class="step-card">
                        <div class="step-number">03</div>
                        <div class="step-icon"></div>
                        <h3>Respond</h3> 
                        <p>Potential donors can be contacted when their blood type and area match a request.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="urgent-section">
            <div class="container">
                <div class="section-heading">
                    <span class="section-label urgent-label"><span class="pulse-dot"></span>URGENT REQUESTS</span> 
                    <h2>Someone may need <span>help right now.</span></h2>
                    <p>A preview of recent blood requests from the LifeLine network.</p>
                </div>

                <section class="request-list">

               <span class="request-count">
                   <?php echo $active_count; ?> active request
                   <?php echo ($active_count != 1)?'s' : ''; ?>
                </span>
            </div>

            <div class="request-list" id="requestList">
                <?php if ($active_count === 0): ?>
                    <div class="request-content">
                        <h3>No active blood requests</h3>
                        <p>There are currently no urgent blood requests in the system.</p>
                    </div>

                   <?php else: ?>

                   <?php while ($request = pg_fetch_assoc($result)): ?>

                    <article class="request-card <?= htmlspecialchars(strtolower($request['urgency'])) ?>"
                        data-blood-type="<?= htmlspecialchars($request['blood_type']) ?>"
                        data-location="<?= htmlspecialchars($request['location']) ?>">

                        <div class="request-card-top">
                            <span class="urgency-badge">
                                <?php
                                $urgency = strtolower($request['urgency']);

                                if ($urgency === 'urgent') {
                                    echo 'URGENT';
                                } elseif ($urgency === 'critical') {
                                    echo 'CRITICAL';
                                } else {
                                    echo htmlspecialchars(
                                        strtoupper($request['urgency'])
                                    );
                                }
                                ?>

                            </span>

                            <span class="request-time">

                                <?= htmlspecialchars(
                                    date(
                                        'M j, Y',
                                        strtotime($request['created_at'])
                                    )
                                ) ?>

                            </span>
                        </div>

                        <div class="blood-request">
                            <div class="blood-icon">

                                <?= htmlspecialchars($request['blood_type']) ?>

                            </div>

                            <div>
                                <h3>
                                    <?= htmlspecialchars(
                                        $request['blood_type']
                                    ) ?>
                                    Blood Needed
                                </h3>

                                <p>
                                    <?= htmlspecialchars(
                                        $request['units_needed']
                                    ) ?>

                                    unit<?= $request['units_needed'] != 1 ? 's' : '' ?>

                                    required
                                </p>
                            </div>
                        </div>

                        <div class="request-details">
                            <div class="detail">
                                <span class="detail-icon"></span>
                                <div>
                                    <strong>Location</strong>
                                    <p>
                                        <?= htmlspecialchars(
                                            $request['hospital']
                                        ) ?>

                                        ,

                                        <?= htmlspecialchars(
                                            $request['location']
                                        ) ?>

                                    </p>
                                </div>
                            </div>

                            <div class="detail">
                                <span class="detail-icon"></span>
                                <div>
                                    <strong>Needed by</strong>
                                    <p>
                                        <?= htmlspecialchars(
                                            date(
                                                'F j, Y \a\t g:i A',
                                                strtotime($request['needed_by'])
                                            )
                                        ) ?>

                                    </p>
                                </div>
                            </div>

                            <div class="detail">
                                <span class="detail-icon"></span>
                                <div>
                                    <strong>Patient</strong>
                                    <p>

                                        <?= htmlspecialchars(
                                            $request['patient_name']
                                        ) ?>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <a href="request-details.php?id=<?= urlencode($request['id']) ?>" class="text-link">
                            View Request<span>&#10142;</span></a>
                    </article>

                <?php endwhile; ?>
                <?php endif; ?>
               </div>
            </div>
       </section>
            </div>
                <div class="center-action">
                    <a href="urgent-requests.php" class="outline-button">View All Urgent Requests<span>&#10142;</span></a>
                </div>
            </div>
        </section>

         <section class="why-section">
            <div class="container">
                <div class="section-heading centered">
                    <span class="section-label">WHY LIFELINE?</span>
                    <h2>When blood is needed, <span>time matters.</span></h2>
                    <p>LifeLine is designed to make potential donor connections easier to discover during urgent situations.</p>
                </div>

                <div class="why-grid">
                    <article class="why-card">
                        <div class="why-icon"></div>
                        <h3>Find Potential Donors </h3>
                        <p> Search by blood type and location instead of relying entirely on personal contacts. </p>
                    </article>

                    <article class="why-card">
                        <div class="why-icon"> </div> <h3>Connect Locally</h3>
                        <p>Help users discover potential donors in areas where blood may be needed.</p>
                    </article>

                    <article class="why-card">
                        <div class="why-icon"></div>
                        <h3>Respond Faster</h3>
                        <p>Urgent requests can make important needs visible to the donor network.</p>
                    </article>

                    <article class="why-card">
                        <div class="why-icon"></div>
                        <h3>Donor Consent</h3>
                        <p>People choose to register and consent before their information is included.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="donor-cta">
            <div class="container">
                <div class="cta-card">
                    <div class="cta-content">
                        <span class="section-label">JOIN THE NETWORK</span>
                        <h2>You could be the person <span>someone is looking for.</span></h2>
                        <p>Join people who have chosen to make themselves potentially reachable whenblood is urgently needed.</p>
                        <a href="become-donor.php" class="btn btn-white">Become a Donor<span>→</span></a>
                    </div>

                    <div class="cta-visual">
                        <div class="image-placeholder"><img src="images/blood-donor-illustration.jpg" alt="Donor"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="resources-section">
            <div class="container">
                <div class="section-heading centered">
                    <span class="section-label">EMERGENCY RESOURCES</span>
                    <h2>Important information, <span>in one place.</span></h2> 
                    <p>Access useful emergency and blood-related resources through LifeLine.</p>
                </div>

                <div class="resources-grid">
                    <a href="emergency-resources.php"
                       class="resource-card">
                        <div class="resource-icon"></div>
                        <div>
                            <h3>Hospitals</h3>
                            <p>Find hospitals and healthcare facilities.</p>
                            <span>View Hospitals &#10142;</span>
                        </div>
                    </a>


                    <a href="emergency-resources.php" class="resource-card">
                        <div class="resource-icon"></div>
                        <div>
                            <h3>Blood Banks</h3>
                            <p>Find blood banks and donation resources.</p>
                            <span>View Blood Banks &#10142;</span>
                        </div>
                    </a>


                    <a href="emergency-resources.php" class="resource-card">
                        <div class="resource-icon"></div>
                        <div>
                            <h3>Emergency Services</h3>
                            <p>Access important emergency contact information.</p>
                            <span>View Contacts</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="final-cta">
            <div class="container">
                <div class="final-cta-content">
                    <span class="heartbeat"></span>
                    <h2>Blood can't wait.</h2>
                    <p>Help make potential donors easier to find  when someone needs blood urgently.</p>
                    <div class="final-actions">
                        <a href="become-donor.php" class="btn btn-primary">Become a Donor<span>&#10142;</span></a>
                        <a href="find-donor.php" class="btn btn-secondary">Find a Donor<span>&#10142;</span></a>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <footer class="site-footer">
        <div class="container footer-container">
            <div class="footer-brand">
                <a href="first-page.php" class="logo footer-logo">
                    <span class="logo-icon"><span class="logo-drop"><img src="images/blood-bank.svg" alt="LifeLine"></span></span>
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
                    <a href="urgent-requests.php">Urgent Requests</a>
                    <a href="emergency-resources.php">Emergency Resources</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 LifeLine. All rights reserved.</p>
                <p>Built to connect people when it matters.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>