<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeLine | Login</title>
    <link rel="stylesheet"  href="css/auth.css">
</head>

<body>
    <main class="auth-container">
        <div class="auth-brand">
            <div class="brand-icon">+</div>
            <div>
                <h1>LifeLine</h1>
                <p>Blood Donation & Emergency Response</p>
            </div>

        </div>

        <section class="auth-card">
            <div class="auth-tabs">
                <button type="button" id="loginTab" class="auth-tab active" aria-selected="true">Login</button>
                <button type="button" id="signupTab" class="auth-tab" aria-selected="false">Sign Up</button>
            </div>

            <div id="loginPanel" class="auth-panel">
                <div class="auth-heading">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to continue to LifeLine.</p>
                </div>

                <form id="loginForm" novalidate>
                    <div class="form-group">
                        <label for="loginIdentifier">Email or Phone</label>
                        <input type="text" id="loginIdentifier" name="identifier" placeholder="Enter your email or phone"
                            autocomplete="username">
                        <small id="loginIdentifierError" class="form-error"></small>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="loginPassword" name="password" placeholder="Enter your password"
                                autocomplete="current-password">
                            <button type="button" class="password-toggle" data-target="loginPassword">Show</button>
                        </div>

                        <small id="loginPasswordError" class="form-error"></small>
                    </div>

                    <!-- Forgot password -->
                    <button type="button" id="forgotPassword" class="forgot-password">Forgot password?</button>

                    <!-- Login button -->
                    <button type="submit" class="auth-submit">Login</button>
                    <p id="loginMessage" class="form-message"></p>
                </form>

                <p class="switch-text">Don't have an account?
                    <button type="button" id="switchToSignup">Create One</button>
                </p>
            </div>

            <div id="signupPanel" class="auth-panel" hidden>
                <div class="auth-heading">
                    <h2>Create your LifeLine account</h2>
                    <p>Join the network and help make emergency response faster.</p>
                </div>

                <form id="signupForm" novalidate>

                    <!-- Full name -->
                    <div class="form-group">
                        <label for="signupName">Full Name</label>
                        <input type="text" id="signupName" name="name" placeholder="Enter your full name" autocomplete="name">
                        <small id="signupNameError" class="form-error"></small>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="signupEmail">Email address</label>
                        <input type="email" id="signupEmail" name="email" placeholder="you@example.com" autocomplete="email">
                        <small id="signupEmailError" class="form-error"></small>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="signupPhone">Phone number</label>
                        <input type="tel" id="signupPhone" name="phone" placeholder="Enter your phone number" autocomplete="tel">
                        <small id="signupPhoneError" class="form-error"></small>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="signupPassword">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="signupPassword" name="password" placeholder="Create a password"
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="signupPassword">Show</button>
                        </div>

                        <small id="signupPasswordError" class="form-error"></small>
                    </div>

                    <!-- Confirm password -->
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Repeat your password"
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="confirmPassword">Show</button>
                        </div>
                        <small id="confirmPasswordError" class="form-error"></small>
                    </div>

                    <!-- Confirm Location -->
                    <div class="form-group">
                        <label for="confirmLocation">Location</label>
                        <input type="text" name="confirmlocation" id="location" required>
                    </div>

                    <!-- Terms -->
                    <div class="terms-group">
                        <label>
                            <input type="checkbox" id="termsAgreement" name="terms">
                            <span>I agree to the LifeLine terms and understand that my information will be used to provide this service.</span>
                        </label>

                        <small id="termsError" class="form-error"></small>
                    </div>

                    <!-- Signup button -->
                    <button type="submit" class="auth-submit">Create Account</button>
                    <p id="signupMessage" class="form-message"></p>
                </form>

                <p class="switch-text">Already have an account? <button type="button" id="switchToLogin">Login</button></p>
            </div>
        </section>

        <!-- Footer -->
        <p class="auth-footer">Life <span>Line</span></p>
    </main>

    <script src="js/auth.js"></script>
</body>
</html>