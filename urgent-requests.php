<?php
session_start();

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View urgent blood donation requests in your community with LifeLine.">
    <title>Urgent Requests | LifeLine</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/urgent-requests.css">
</head>
<body>
   <header class="site-header">
   <div class="header-container">
     <a href="first-page.php" class="logo">
        <span class="logo-icon"></span>
        <span class="logo-text">LifeLine</span>
     </a>

     <button class="menu-button" type="button" aria-label="Open navigation menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
     </button>

     <nav class="main-nav" aria-label="Main navigation">
        <a href="first-page.php">Home</a>
        <a href="find-donor.php">Find a Donor</a>
        <a href="become-donor.php">Become a Donor</a>
        <a href="urgent-requests.php" class="active">Urgent Requests</a>
        <a href="emergency-resources.php">Emergency Resources</a>
     </nav>
    </div>
</header>

<main>
<section class="request-hero">
    <div class="container">
        <span class="section-label">URGENT BLOOD REQUESTS</span>
        <h1>Someone needs blood. <span><br>You may be able to help.</span></h1>
        <p>View active blood requests from hospitals and families in your community. If you are a suitable donor, 
            your response could help someone get the support they need faster.</p>
        <a href="post-request.php" class="primary-button"><span>+</span>Post Urgent Request</a>
    </div>
</section>

<section class="request-board">
    <div class="container">
        <div class="board-header">
            <div>
                <span class="section-label">ACTIVE REQUESTS</span>
                <h2>Blood is needed now.</h2>
            </div>

            <span class="request-count">
               <?php echo $active_count; ?> active request
               <?php echo ($active_count != 1) ? '(s)' : ''; ?>
           </span>
        </div>

        <div class="request-list" id="requestList">
            <?php if ($active_count === 0): ?>
                <div class="no-requests">
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
                            <div class="blood-icon"><?= htmlspecialchars($request['blood_type']) ?></div>
                            <div>
                                <h3><?= htmlspecialchars( $request['blood_type']) ?> Blood Needed</h3>
                                <p><?= htmlspecialchars( $request['units_needed']) ?>
                                    unit<?= $request['units_needed'] != 1 ? 's' : '' ?> required</p>
                            </div>
                        </div>

                        <div class="request-details">
                            <div class="detail">
                                <span class="detail-icon"></span>
                                <div>
                                    <strong>Location</strong>
                                    <p><?= htmlspecialchars( $request['hospital']) ?>,
                                        <?= htmlspecialchars( $request['location']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="detail">
                                <span class="detail-icon"></span>
                                <div>
                                    <strong>Needed by</strong>
                                    <p><?= htmlspecialchars( date('F j, Y \a\t g:i A', strtotime($request['needed_by']))) ?></p>
                                </div>
                            </div>

                            <div class="detail">
                                <span class="detail-icon"></span>
                                <div>
                                    <strong>Patient</strong>
                                    <p><?= htmlspecialchars( $request['patient_name']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="request-actions">
                            <a href="request-details.php?id=<?= urlencode($request['id']) ?>" class="request-button">
                                 View Request <span>&#10142;</span>
                            </a><br>

                            <a href="matching-donors.php?request_id=<?= urlencode($request['id']) ?>"
                                  class="request-button">Find Matching Donors <span>&#10142;</span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <span class="section-label">HOW IT WORKS</span>
        <h2> Respond when someone needs help.</h2>
        <div class="steps">
            <div class="step">
                <span class="step-number">01</span>
                <h3>A request is posted</h3>
                <p>A hospital or family shares the blood type and location of the urgent need.</p>
            </div>

            <div class="step">
                <span class="step-number">02</span>
                <h3>Potential donors see it</h3>
                <p>Suitable donors can discover requests in their area.</p>
            </div>

            <div class="step">
                <span class="step-number">03</span>
                <h3>Donors respond</h3>
                <p>A suitable donor can follow the request's contact process to offer help.</p>
            </div>
        </div>
    </div>
</section>
</main>

    <footer class="site-footer">
        <div class="container footer-container">
            <div class="footer-brand">
                <a href="index.php" class="logo footer-logo">
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
<script src="js/urgent-requests.js"></script>
</body>
</html>