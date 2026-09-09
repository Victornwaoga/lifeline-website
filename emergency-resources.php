<?php
require_once 'php/db.php';

$hospitalResult = pg_query($conn, "
    SELECT *
    FROM emergency_resources
    WHERE type = 'hospital'
    AND is_active = TRUE
    ORDER BY id ASC
");

$bloodBankResult = pg_query($conn, "
    SELECT *
    FROM emergency_resources
    WHERE type = 'blood_bank'
    AND is_active = TRUE
    ORDER BY id ASC
");

$ambulanceResult = pg_query($conn, "
    SELECT *
    FROM emergency_resources
    WHERE type = 'ambulance'
    AND is_active = TRUE
    ORDER BY id ASC
");

$emergencyNumberResult = pg_query($conn, "
    SELECT *
    FROM emergency_resources
    WHERE type = 'emergency_number'
    AND is_active = TRUE
    ORDER BY id ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LifeLine emergency hospitals, blood banks, ambulance services and emergency contacts.">
    <title>Emergency Resources | LifeLine</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/emergency-resources.css">
</head>

<body>
    <header class="site-header">
        <div class="header-container">
            <a href="first-page.php" class="logo" aria-label="LifeLine Home">
                <span class="logo-icon"><img src="images/blood-bank.svg" alt=""></span>
                <span class="logo-text">Life<span>Line</span></span>
            </a>

            <button type="button" class="menu-button" aria-label="Open navigation menu" aria-expanded="false"
                aria-controls="mainNavigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="main-nav" id="mainNavigation" aria-label="Main navigation">
                <a href="first-page.php">Home</a>
                <a href="find-donor.php">Find a Donor</a>
                <a href="become-donor.php">Become a Donor</a>
                <a href="urgent-requests.php">Urgent Requests</a>
                <a href="emergency-resources.php" class="active">Emergency Resources</a>
            </nav>
        </div>
    </header>

    <main>

        <section class="resources-hero">
            <div class="container">
                <span class="section-label">EMERGENCY RESOURCES</span>
                <h1>Help when every<span> second matters.</span></h1>
                <p> Find emergency hospitals, blood banks, ambulance services and important emergency contacts in one place.</p>
                <div class="hero-warning">
                    <span></span>
                    <p>If someone is in immediate danger, contact an appropriate emergency service or go to the nearest hospital.</p>
                </div>
            </div>
        </section>

        <section class="quick-actions">
            <div class="container">
                <div class="section-heading">
                    <span class="section-label">QUICK ACTIONS</span>
                    <h2>What do you need?</h2>
                    <p>Choose the service you need right now.</p>
                </div>
                <div class="action-grid">
                    <a href="#hospitals" class="action-card">
                        <div class="action-icon"></div>
                        <div>
                            <h3>Find a Hospital</h3>
                            <p>Locate hospitals and medical facilities.</p>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>
                   
                    <a href="#blood-banks" class="action-card">
                        <div class="action-icon"></div>
                        <div>
                            <h3>Blood Banks</h3>
                            <p>Find blood donation and transfusion facilities.</p>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="#ambulance" class="action-card">
                        <div class="action-icon"></div>
                        <div>
                            <h3>Ambulance</h3>
                            <p>Find emergency transport services.</p>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="#emergency-numbers" class="action-card">
                        <div class="action-icon"></div>
                        <div>
                            <h3>Emergency Numbers</h3>
                            <p>Important emergency contacts.</p>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="resource-section" id="hospitals">
            <div class="container">
                <div class="section-heading">
                    <span class="section-label">HOSPITALS</span>
                    <h2>Nearby medical facilities</h2>
                    <p>Hospitals that may provide emergency care and blood transfusion services.</p>
                </div>

                <div class="resource-search">
                    <label for="hospitalSearch">Search hospitals</label>
                    <input type="search" id="hospitalSearch" placeholder="Search by hospital or location..." autocomplete="off">
                </div>

                <div class="resource-list" id="hospitalList">
                    <?php if (pg_num_rows($hospitalResult) > 0): ?>

                    <?php while ($hospital = pg_fetch_assoc($hospitalResult)): ?>

                     <article class="resource-card" data-name="<?= htmlspecialchars($hospital['name']) ?>" data-location="<?= htmlspecialchars($hospital['location']) ?>">

                      <div class="resource-image">
                    <?php if (!empty($hospital['image_url'])): ?>

                    <img src="<?= htmlspecialchars($hospital['image_url']) ?>" alt="<?= htmlspecialchars($hospital['name']) ?>">
                     <?php else: ?>

                    <div class="image-placeholder"></div>

                   <?php endif; ?>
                </div>

                <div class="resource-content">
                <h3><?= htmlspecialchars($hospital['name']) ?></h3>
                <p class="resource-location">Image Goes Here<?= htmlspecialchars($hospital['location']) ?></p>
                <p> <?= htmlspecialchars($hospital['description']) ?></p>
                <div class="resource-actions">
                    <?php if (!empty($hospital['phone'])): ?>
                        <a href="tel:<?= htmlspecialchars($hospital['phone']) ?>" class="call-button">Call</a>
                    <?php endif; ?>
                    <?php if (!empty($hospital['address'])): ?>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($hospital['address']) ?>"
                            target="_blank" rel="noopener" class="directions-button">Directions</a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
      <?php endwhile; ?>

     <?php else: ?>

       <p>No hospitals are currently available.</p>

    <?php endif; ?>

                </div>
            </div>
        </section>

        <section class="resource-section alternate-section" id="blood-banks">
            <div class="container">
                <div class="resource-list">
                    <?php if (pg_num_rows($bloodBankResult) > 0): ?>
                        <?php while ($bloodBank = pg_fetch_assoc($bloodBankResult)): ?>

                            <article class="resource-card" data-name="<?= htmlspecialchars($bloodBank['name']) ?>" data-location="<?= htmlspecialchars($bloodBank['location']) ?>">
                               <div class="resource-image">

                                   <?php if (!empty($bloodBank['image_url'])): ?>
                                       <img src="<?= htmlspecialchars($bloodBank['image_url']) ?>" alt="<?= htmlspecialchars($bloodBank['name']) ?>">
                                   <?php else: ?>
                                       <div class="image-placeholder"></div>
                                   <?php endif; ?>

                                </div>

                                <div class="resource-content">
                                    <h3><?= htmlspecialchars($bloodBank['name']) ?></h3>
                                    <p class="resource-location"><?= htmlspecialchars($bloodBank['location']) ?></p>
                                    <p><?= htmlspecialchars($bloodBank['description']) ?></p>
                                    <div class="resource-actions">
                                        <?php if (!empty($bloodBank['phone'])): ?>
                                            <a href="tel:<?= htmlspecialchars($bloodBank['phone']) ?>" class="call-button">Call</a>
                                        <?php endif; ?>
                                        <?php if (!empty($bloodBank['address'])): ?>
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($bloodBank['address']) ?>"
                                           target="_blank" rel="noopener" class="directions-button">Directions</a>
                                      <?php endif; ?>
                                   </div>
                               </div>
                           </article>
                      <?php endwhile; ?>
                   <?php else: ?>
                        <p>No blood banks are currently available.</p>

                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="resource-section" id="ambulance">
            <div class="container">
                <div class="resource-list">
                   <?php if (pg_num_rows($ambulanceResult) > 0): ?>
                   <?php while ($ambulance = pg_fetch_assoc($ambulanceResult)): ?>
                        <article class="resource-card" data-name="<?= htmlspecialchars($ambulance['name']) ?>"
                              data-location="<?= htmlspecialchars($ambulance['location']) ?>">
                           <div class="resource-image">
                              <?php if (!empty($ambulance['image_url'])): ?>
                              <img src="<?= htmlspecialchars($ambulance['image_url']) ?>" alt="<?= htmlspecialchars($ambulance['name']) ?>">
                              <?php else: ?>
                             <div class="image-placeholder">Ambulance Image</div>
                              <?php endif; ?>
                           </div>

                           <div class="resource-content">
                             <h3><?= htmlspecialchars($ambulance['name']) ?></h3>
                              <p class="resource-location"><?= htmlspecialchars($ambulance['location']) ?></p>
                             <p><?= htmlspecialchars($ambulance['description']) ?></p>
                              <div class="resource-actions">
                                  <?php if (!empty($ambulance['phone'])): ?>
                                     <a href="tel:<?= htmlspecialchars($ambulance['phone']) ?>" class="call-button" >📞 Call</a>
                                 <?php endif; ?>
                                 <?php if (!empty($ambulance['address'])): ?>
                                     <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($ambulance['address']) ?>"
                                      target="_blank" rel="noopener" class="directions-button">Directions</a>
                                    <?php endif; ?>
                              </div>
                           </div>
                       </article>

                     <?php endwhile; ?>

                 <?php else: ?>
                       <p>No ambulance services are currently available.</p>

                  <?php endif; ?>

                </div>
            </div>
        </section>

        <section class="emergency-numbers" id="emergency-numbers">
            <div class="container">
                <?php if (pg_num_rows($emergencyNumberResult) > 0): ?>
                    <?php while ($emergency = pg_fetch_assoc($emergencyNumberResult)): ?>

                    <div class="emergency-number-card">
                      <h3><?= htmlspecialchars($emergency['name']) ?></h3><br>
                       <p><?= htmlspecialchars($emergency['description']) ?></p><br>
                       <a href="tel:<?= htmlspecialchars($emergency['phone']) ?>" class="call-button">
                       📞 Call <?= htmlspecialchars($emergency['phone']) ?></a>
                    </div>

                <?php endwhile; ?>

                <?php else: ?>

                   <p>No emergency numbers are currently available.</p>

              <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-main">
                <div class="footer-brand">
                    <a href="index.php" class="footer-logo">
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
                        <a href="urgent-requests.php">Urgent Requests</a>
                        <a href="emergency-resources.php">Emergency Resources</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 LifeLine. All Rights Reserved.</p>
                <p>Built to help people connect faster.</p>
            </div>
        </div>
    </footer>

    <script src="js/emergency-resources.js"></script>
</body>
</html>