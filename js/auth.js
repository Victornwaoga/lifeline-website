document.addEventListener("DOMContentLoaded", function () {

    /* =========================================
       GET ELEMENTS
    ========================================= */

    const loginTab = document.getElementById("loginTab");
    const signupTab = document.getElementById("signupTab");

    const loginPanel = document.getElementById("loginPanel");
    const signupPanel = document.getElementById("signupPanel");

    const switchToSignup =
        document.getElementById("switchToSignup");

    const switchToLogin =
        document.getElementById("switchToLogin");

    const loginForm =
        document.getElementById("loginForm");

    const signupForm =
        document.getElementById("signupForm");


    /* =========================================
       SWITCH TO LOGIN
    ========================================= */

    function showLogin() {

        loginTab.classList.add("active");
        signupTab.classList.remove("active");

        loginTab.setAttribute(
            "aria-selected",
            "true"
        );

        signupTab.setAttribute(
            "aria-selected",
            "false"
        );

        loginPanel.hidden = false;
        signupPanel.hidden = true;
    }


    /* =========================================
       SWITCH TO SIGN UP
    ========================================= */

    function showSignup() {

        signupTab.classList.add("active");
        loginTab.classList.remove("active");

        signupTab.setAttribute(
            "aria-selected",
            "true"
        );

        loginTab.setAttribute(
            "aria-selected",
            "false"
        );

        signupPanel.hidden = false;
        loginPanel.hidden = true;
    }


    /* =========================================
       TAB BUTTONS
    ========================================= */

    loginTab.addEventListener(
        "click",
        showLogin
    );

    signupTab.addEventListener(
        "click",
        showSignup
    );

    switchToSignup.addEventListener(
        "click",
        showSignup
    );

    switchToLogin.addEventListener(
        "click",
        showLogin
    );


    /* =========================================
       SHOW / HIDE PASSWORD
    ========================================= */

    const passwordButtons =
        document.querySelectorAll(
            ".password-toggle"
        );

    passwordButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    const targetId =
                        button.dataset.target;

                    const passwordInput =
                        document.getElementById(
                            targetId
                        );

                    if (
                        passwordInput.type ===
                        "password"
                    ) {

                        passwordInput.type =
                            "text";

                        button.textContent =
                            "Hide";

                    } else {

                        passwordInput.type =
                            "password";

                        button.textContent =
                            "Show";
                    }
                }
            );
        }
    );


    /* =========================================
       LOGIN FORM
    ========================================= */

    loginForm.addEventListener(
        "submit",
        async function (event) {

            event.preventDefault();


            const identifier =
                document.getElementById(
                    "loginIdentifier"
                );

            const password =
                document.getElementById(
                    "loginPassword"
                );

            const identifierError =
                document.getElementById(
                    "loginIdentifierError"
                );

            const passwordError =
                document.getElementById(
                    "loginPasswordError"
                );

            const message =
                document.getElementById(
                    "loginMessage"
                );


            /* Clear old messages */

            identifierError.textContent = "";
            passwordError.textContent = "";
            message.textContent = "";


            let valid = true;


            /* Check identifier */

            if (
                identifier.value.trim() === ""
            ) {

                identifierError.textContent =
                    "Please enter your email or phone number.";

                valid = false;
            }


            /* Check password */

            if (
                password.value === ""
            ) {

                passwordError.textContent =
                    "Please enter your password.";

                valid = false;
            }


            if (!valid) {
                return;
            }


            /* =====================================
               SEND LOGIN TO PHP
            ===================================== */

            const formData =
                new FormData(loginForm);

            formData.append(
                "action",
                "login"
            );


            try {

                const response =
                    await fetch(
                        "php/auth-process.php",
                        {
                            method: "POST",
                            body: formData
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        "Server returned an error."
                    );
                }


                const data =
                    await response.json();


                /* =================================
                   LOGIN SUCCESS
                ================================= */

                if (data.success) {

                    message.textContent =
                        data.message;


                    setTimeout(
                        function () {

                            window.location.href =
                                "first-page.php";

                        },
                        800
                    );

                }


                /* =================================
                   LOGIN FAILED
                ================================= */

                else {

                    message.textContent =
                        data.message ||
                        "Incorrect email/phone or password.";
                }

            }


            catch (error) {

                console.error(error);

                message.textContent =
                    "Unable to connect to the server.";
            }

        }
    );


    /* =========================================
       SIGN UP FORM
    ========================================= */

    signupForm.addEventListener(
        "submit",
        async function (event) {

            event.preventDefault();


            const name =
                document.getElementById(
                    "signupName"
                );

            const email =
                document.getElementById(
                    "signupEmail"
                );

            const phone =
                document.getElementById(
                    "signupPhone"
                );

            const password =
                document.getElementById(
                    "signupPassword"
                );

            const confirmPassword =
                document.getElementById(
                    "confirmPassword"
                );

            const terms =
                document.getElementById(
                    "termsAgreement"
                );


            const nameError =
                document.getElementById(
                    "signupNameError"
                );

            const emailError =
                document.getElementById(
                    "signupEmailError"
                );

            const phoneError =
                document.getElementById(
                    "signupPhoneError"
                );

            const passwordError =
                document.getElementById(
                    "signupPasswordError"
                );

            const confirmError =
                document.getElementById(
                    "confirmPasswordError"
                );

            const termsError =
                document.getElementById(
                    "termsError"
                );

            const message =
                document.getElementById(
                    "signupMessage"
                );


            /* Clear errors */

            nameError.textContent = "";
            emailError.textContent = "";
            phoneError.textContent = "";
            passwordError.textContent = "";
            confirmError.textContent = "";
            termsError.textContent = "";
            message.textContent = "";


            let valid = true;


            /* =====================================
               NAME
            ===================================== */

            if (
                name.value.trim().length < 2
            ) {

                nameError.textContent =
                    "Please enter your full name.";

                valid = false;
            }


            /* =====================================
               EMAIL
            ===================================== */

            const emailPattern =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


            if (
                !emailPattern.test(
                    email.value.trim()
                )
            ) {

                emailError.textContent =
                    "Please enter a valid email address.";

                valid = false;
            }


            /* =====================================
               PHONE
            ===================================== */

            const phonePattern =
                /^[0-9+\-\s()]{7,20}$/;


            if (
                !phonePattern.test(
                    phone.value.trim()
                )
            ) {

                phoneError.textContent =
                    "Please enter a valid phone number.";

                valid = false;
            }


            /* =====================================
               PASSWORD
            ===================================== */

            if (
                password.value.length < 8
            ) {

                passwordError.textContent =
                    "Password must contain at least 8 characters.";

                valid = false;
            }


            /* =====================================
               CONFIRM PASSWORD
            ===================================== */

            if (
                confirmPassword.value !==
                password.value
            ) {

                confirmError.textContent =
                    "Passwords do not match.";

                valid = false;
            }


            /* =====================================
               TERMS
            ===================================== */

            if (!terms.checked) {

                termsError.textContent =
                    "You must agree before creating an account.";

                valid = false;
            }


            if (!valid) {
                return;
            }


            /* =====================================
               SEND SIGNUP TO PHP
            ===================================== */

            const formData =
                new FormData(signupForm);


            formData.append(
                "action",
                "signup"
            );


            try {

                const response =
                    await fetch(
                        "php/signup.php",
                        {
                            method: "POST",
                            body: formData
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        "Server returned an error."
                    );
                }


                const data =
                    await response.json();


                if (data.success) {

                    message.textContent =
                        data.message;


                    signupForm.reset();


                    setTimeout(
                        function () {

                            showLogin();

                        },
                        1500
                    );

                }

                else {

                    message.textContent =
                        data.message ||
                        "Unable to create account.";
                }

            }


            catch (error) {

                console.error(error);

                message.textContent =
                    "Unable to connect to the server.";
            }

        }
    );


    /* =========================================
       FORGOT PASSWORD
    ========================================= */

    const forgotPassword = document.getElementById("forgotPassword");

    if (forgotPassword) {
        forgotPassword.addEventListener("click", async function () {

            const identifier = prompt(
                "Enter the email address or phone number connected to your LifeLine account:"
            );

            if (!identifier) {
                return;
            }

            forgotPassword.disabled = true;
            forgotPassword.textContent = "Sending...";

            try {
                const formData = new FormData();

                formData.append("identifier", identifier.trim());

                const response = await fetch("php/request-reset.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message);
                }

            } catch (error) {
                console.error("Password reset error:", error);

                alert(
                    "Something went wrong while requesting the password reset."
            );

            } finally {
                forgotPassword.disabled = false;
                forgotPassword.textContent = "Forgot password?";
           }
        });
   }
});