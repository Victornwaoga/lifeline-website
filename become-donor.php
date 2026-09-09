<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"  content="Register as a blood donor with LifeLine and help connect people 
    to potential donors when blood is urgently needed.">
    <title>Become a Donor | LifeLine</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/become-donor.css">
</head>

<body>
    <header class="site-header">
        <div class="container header-container">
            <a href="first-page.php" class="logo" aria-label="LifeLine Home">
                <span class="logo-icon"><span class="logo-drop"></span><img src="images/blood-bank.svg" alt=""></span>
                <span class="logo-text">Life<span>Line</span></span>
            </a>

            <button class="menu-button" type="button" aria-label="Open navigation menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="first-page.php">Home</a>
                <a href="find-donor.php">Find a Donor</a>
                <a href="become-donor.php" class="active">Become a Donor</a>
                <a href="urgent-requests.php">Urgent Requests</a>
                <a href="emergency-resources.php">Emergency Resources</a>
            </nav>
        </div>
    </header>

    <main>

        <section class="donor-hero">
            <div class="container donor-hero-grid">
                <div class="hero-content">
                    <span class="eyebrow"><span class="pulse-dot"></span>BECOME A POTENTIAL DONOR</span>

                    <h1>Your blood could <span>help someone survive.</span></h1>
                    <p>Register with LifeLine so people searching for potential blood donors can find you when 
                        help is urgently needed.</p>

                    <div class="hero-points">
                        <div class="hero-point">
                            <span class="point-icon">&#10003;</span>
                            <div>
                                <strong>Simple registration</strong>
                                <span>Takes only a few minutes.</span>
                            </div>
                        </div>

                        <div class="hero-point">
                            <span class="point-icon">&#10003;</span>
                            <div> 
                                <strong>Help your community</strong>
                                <span>Be reachable when blood is needed.</span>
                            </div>
                        </div>

                        <div class="hero-point">
                            <span class="point-icon">&#10003;</span>
                            <div>
                                <strong>Your consent matters</strong>
                                <span>You choose to join the network.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-image-placeholder">
                    <!-- <div class="placeholder-content"> -->
                        <!-- <div class="placeholder-icon"></div> -->
                        <!-- <strong>Donor Image</strong> -->
                        <img src="images/african-donor.jpg" alt="African Donor">
                    <!-- </div> -->
                </div>
            </div>
        </section>

        <section class="registration-section">
            <div class="container registration-layout">
                <div class="registration-intro">
                    <span class="section-label">DONOR REGISTRATION</span>
                    <h2>Register as a potential donor.</h2>
                    <p>Enter your details below to join the LifeLine Donor Network.</p>
                    <div class="important-note">
                        <span class="note-icon">!</span>
                        <div>
                            <strong>Before you register</strong>
                            <p>Only register if you are willing to be contacted about a possible blood donation request.</p>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <form id="donorRegistrationForm" action="#" method="POST" novalidate>
                        <div class="form-group">
                            <label for="fullName">Full Name<span class="required">*</span></label>
                            <input type="text" name="full_name" id="fullName" placeholder="Enter your full name">
                            <small class="field-help">Enter the name you would like associated with your donor profile.</small>
                            <span class="error-message" id="fullNameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="bloodType">Blood Type<span class="required">*</span></label>
                            <select name="blood_type" id="bloodType">
                                <option value="">Select your blood type</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>

                            <small class="field-help">Select the blood type you know to be yours.</small>
                            <span class="error-message" id="bloodTypeError"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number<span class="required">*</span></label>
                            <input type="tel" name="phone" id="phone" placeholder="Enter your phone number">
                            <small class="field-help">Use a number where you can be reached if a potential request
                                arises.</small>
                            <span class="error-message" id="phoneError"></span>
                        </div>
                        <div class="form-group">
                         <label for="location">Location/Area<span class="required">*</span> </label>
                         <div class="location-status" id="locationStatus" >Location permission is required</div>
                         <input type="text" name="location" id="location" placeholder="Your area or location">
                         <input type="hidden" id="latitude" name="latitude" >  
                         <input type="hidden" id="longitude" name="longitude" >
                         <button type="button" id="getLocationButton" class="location-button" >Allow Location</button>
                          <small class="field-help">Location access is required so LifeLine can help identify potential donors 
                            in your area. Your exact location should not be displayed publicly.</small> 
                           <span class="error-message" id="locationError"></span>
                        </div>
                            <small class="field-help">Enter your town, city or general area. Do not enter your full home address.</small>
                            <span class="error-message" id="locationError"></span>
                        </div>

                        <div class="form-group">
                            <label for="lastDonation">Last Donation Date</label>
                            <input type="date" name="last_donation_date" id="lastDonationDate">
                            <small class="field-help">If you have never donated blood, you can leave this blank.</small>
                            <span class="error-message" id="lastDonationError"></span>
                        </div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="consent-box">
                            <label class="checkbox-label">
                               <input  type="checkbox" name="consent" id="consent">
                                <span class="custom-checkbox"></span>
                                <span class="consent-text">I voluntarily agree to register as a potential blood donor on
                                    LifeLine and understand that my registration may allow appropriate users to identify
                                     me as a potential donor when blood is needed.</span>
                            </label>
                            <span class="error-message" id="consentError"></span>
                        </div>

                        <p class="form-security">&#128274; Your information should only be used for the purposes explained 
                            during registration.</p>
                        <button type="submit" class="submit-button" id="submitButton"><span>Done</span></button>
                    </form>
                </div>
            </div>
        </section>

        <section class="how-section">
            <div class="container">
                <div class="section-heading">
                    <span class="section-label">WHAT HAPPENS NEXT?</span>
                    <h2>From Registration to Response.</h2>
                    <p>LifeLine is designed to make it easier for potential donors and people who need blood to 
                        find each other.</p>
                </div>
                <div class="steps">
                    <article class="step-card">
                        <span class="step-number">01</span>
                        <!-- <div class="step-icon"></div> -->
                        <h3>Register</h3>
                        <p>Provide your basic information and voluntarily join the donor network.</p>
                    </article>

                    <article class="step-card">
                        <span class="step-number">02</span>
                        <!-- <div class="step-icon"></div> -->
                        <h3>Become discoverable</h3>
                        <p> Your blood type and general area can help identify you as a potential donor.</p>
                    </article>

                    <article class="step-card">
                        <span class="step-number">03</span>
                        <!-- <div class="step-icon"></div> -->
                        <h3>Respond to requests</h3>
                        <p>If contacted, you can decide whether you are able and willing to help.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="final-cta">
            <div class="container">
                <div class="cta-card">
                    <span class="cta-icon"></span>
                    <div><span class="section-label">DONOR MATTERS</span>
                        <h2>Someone may need the blood you can give.</h2>
                        <p>Register today and help make it easier for people in your community to find potential donors.</p>
                    </div>

                    <a href="#donorRegistrationForm" class="cta-button">Register Now<span>↑</span></a>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-container">
            <div class="footer-brand">
                <a href="index.php" class="logo footer-logo">
                    <span class="logo-icon"><span class="logo-drop"></span><img src="images/blood-bank.svg" alt="LifeLine"></span>
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

     <script src="js/become-donor.js"></script>
</body>
</html>