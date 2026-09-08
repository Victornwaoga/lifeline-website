const form = document.getElementById("donorRegistrationForm");

const fullName = document.getElementById("fullName");
const bloodType = document.getElementById("bloodType");
const phone = document.getElementById("phone");
const locationInput = document.getElementById("location");
const lastDonation = document.getElementById("lastDonationDate");
const consent = document.getElementById("consent");
const submitButton = document.getElementById("submitButton");


/* =========================================================
   2. LOCATION ELEMENTS
========================================================= */

const getLocationButton =
    document.getElementById("getLocationButton");

const locationStatus =
    document.getElementById("locationStatus");

const latitudeInput =
    document.getElementById("latitude");

const longitudeInput =
    document.getElementById("longitude");


/* =========================================================
   3. ERROR ELEMENTS
========================================================= */

const fullNameError =
    document.getElementById("fullNameError");

const bloodTypeError =
    document.getElementById("bloodTypeError");

const phoneError =
    document.getElementById("phoneError");

const locationError =
    document.getElementById("locationError");

const lastDonationError =
    document.getElementById("lastDonationError");

const consentError =
    document.getElementById("consentError");


/* =========================================================
   4. LOCATION STATUS
========================================================= */

let locationVerified = false;


/* =========================================================
   5. CLEAN TEXT
========================================================= */

function cleanText(value) {
    return value
        .trim()
        .replace(/\s+/g, " ");
}


/* =========================================================
   6. SHOW ERROR
========================================================= */

function showError(input, errorElement, message) {

    if (input) {
        input.classList.add("input-error");
        input.setAttribute("aria-invalid", "true");
    }

    if (errorElement) {
        errorElement.textContent = message;
    }
}


/* =========================================================
   7. CLEAR ERROR
========================================================= */

function clearError(input, errorElement) {

    if (input) {
        input.classList.remove("input-error");
        input.setAttribute("aria-invalid", "false");
    }

    if (errorElement) {
        errorElement.textContent = "";
    }
}


/* =========================================================
   8. FULL NAME VALIDATION
========================================================= */

function validateFullName() {

    const value = cleanText(fullName.value);

    if (value === "") {

        showError(
            fullName,
            fullNameError,
            "Please enter your full name."
        );

        return false;
    }

    if (value.length < 3) {

        showError(
            fullName,
            fullNameError,
            "Please enter your full name."
        );

        return false;
    }

    const namePattern =
        /^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/;

    if (!namePattern.test(value)) {

        showError(
            fullName,
            fullNameError,
            "Please enter a valid name."
        );

        return false;
    }

    clearError(fullName, fullNameError);

    return true;
}


/* =========================================================
   9. BLOOD TYPE VALIDATION
========================================================= */

function validateBloodType() {

    const allowedBloodTypes = [
        "A+",
        "A-",
        "B+",
        "B-",
        "AB+",
        "AB-",
        "O+",
        "O-"
    ];

    if (!allowedBloodTypes.includes(bloodType.value)) {

        showError(
            bloodType,
            bloodTypeError,
            "Please select your blood type."
        );

        return false;
    }

    clearError(bloodType, bloodTypeError);

    return true;
}


/* =========================================================
   10. PHONE VALIDATION
========================================================= */

function validatePhone() {

    const value = phone.value.trim();

    const cleanedPhone =
        value.replace(/[\s()-]/g, "");

    if (cleanedPhone === "") {

        showError(
            phone,
            phoneError,
            "Please enter your phone number."
        );

        return false;
    }

    const nigeriaPhonePattern =
        /^(?:0[789][01]\d{8}|\+234[789][01]\d{8})$/;

    if (!nigeriaPhonePattern.test(cleanedPhone)) {

        showError(
            phone,
            phoneError,
            "Enter a valid Nigerian phone number."
        );

        return false;
    }

    clearError(phone, phoneError);

    return true;
}


/* =========================================================
   11. GET USER LOCATION
========================================================= */

function getUserLocation() {

    if (!navigator.geolocation) {

        locationVerified = false;

        locationStatus.textContent =
            "Location services are not supported by this browser.";

        showError(
            locationInput,
            locationError,
            "Your browser does not support location services."
        );

        return;
    }


    locationStatus.textContent =
        "Requesting your location...";

    getLocationButton.disabled = true;

    getLocationButton.textContent =
        "Getting Location...";


    navigator.geolocation.getCurrentPosition(

        function(position) {

            const latitude =
                position.coords.latitude;

            const longitude =
                position.coords.longitude;


            latitudeInput.value =
                latitude;

            longitudeInput.value =
                longitude;


            locationVerified = true;


            locationStatus.textContent =
                "✓ Location verified";

            locationStatus.classList.add(
                "location-success"
            );


            getLocationButton.textContent =
                "✓ Location Verified";

            getLocationButton.disabled =
                false;


            // locationInput.value =
            //     "Location verified";


            clearError(
                locationInput,
                locationError
            );
        },


        function(error) {

            locationVerified = false;

            latitudeInput.value = "";
            longitudeInput.value = "";

            getLocationButton.disabled = false;

            getLocationButton.textContent =
                "Allow Location";

            locationInput.value = "";


            switch (error.code) {

                case error.PERMISSION_DENIED:

                    locationStatus.textContent =
                        "Location permission was denied.";

                    showError(
                        locationInput,
                        locationError,
                        "Please allow location access before registering."
                    );

                    break;


                case error.POSITION_UNAVAILABLE:

                    locationStatus.textContent =
                        "Location could not be determined.";

                    showError(
                        locationInput,
                        locationError,
                        "Your location could not be determined. Please try again."
                    );

                    break;


                case error.TIMEOUT:

                    locationStatus.textContent =
                        "Location request timed out.";

                    showError(
                        locationInput,
                        locationError,
                        "Location request timed out. Please try again."
                    );

                    break;


                default:

                    locationStatus.textContent =
                        "Unable to get your location.";

                    showError(
                        locationInput,
                        locationError,
                        "Unable to verify your location."
                    );
            }
        },

        {
            enableHighAccuracy: false,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}


/* =========================================================
   12. LOCATION BUTTON
========================================================= */

if (getLocationButton) {

    getLocationButton.addEventListener(
        "click",
        getUserLocation
    );
}


/* =========================================================
   13. LOCATION VALIDATION
========================================================= */

function validateLocation() {

    if (!locationVerified) {

        showError(
            locationInput,
            locationError,
            "You must allow location access before registering."
        );

        return false;
    }

    clearError(
        locationInput,
        locationError
    );

    return true;
}


/* =========================================================
   14. LAST DONATION DATE
========================================================= */

function validateLastDonation() {

    const value = lastDonation.value;


    /* First-time donors can leave it blank */

    if (value === "") {

        clearError(
            lastDonation,
            lastDonationError
        );

        return true;
    }


    const selectedDate =
        new Date(value + "T00:00:00");


    const today =
        new Date();

    today.setHours(
        0,
        0,
        0,
        0
    );


    if (selectedDate > today) {

        showError(
            lastDonation,
            lastDonationError,
            "Donation date cannot be in the future."
        );

        return false;
    }


    clearError(
        lastDonation,
        lastDonationError
    );

    return true;
}


/* =========================================================
   15. SET MAXIMUM DONATION DATE
========================================================= */

function setMaximumDonationDate() {

    const today = new Date();

    const year =
        today.getFullYear();

    const month =
        String(today.getMonth() + 1)
            .padStart(2, "0");

    const day =
        String(today.getDate())
            .padStart(2, "0");

    const formattedDate =
        `${year}-${month}-${day}`;

    lastDonation.max =
        formattedDate;
}


setMaximumDonationDate();


/* =========================================================
   16. CONSENT VALIDATION
========================================================= */

function validateConsent() {

    if (!consent.checked) {

        consentError.textContent =
            "You must agree before registering as a donor.";

        return false;
    }

    consentError.textContent = "";

    return true;
}


/* =========================================================
   17. VALIDATE EVERYTHING
========================================================= */

function validateForm() {

    const nameValid =
        validateFullName();

    const bloodValid =
        validateBloodType();

    const phoneValid =
        validatePhone();

    const locationValid =
        validateLocation();

    const donationValid =
        validateLastDonation();

    const consentValid =
        validateConsent();


    return (
        nameValid &&
        bloodValid &&
        phoneValid &&
        locationValid &&
        donationValid &&
        consentValid
    );
}


/* =========================================================
   18. FOCUS FIRST INVALID FIELD
========================================================= */

function focusFirstInvalid() {

    const firstInvalid =
        form.querySelector(".input-error");

    if (firstInvalid) {

        firstInvalid.focus();

        firstInvalid.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }
}


/* =========================================================
   19. FULL NAME LIVE VALIDATION
========================================================= */

fullName.addEventListener(
    "input",
    function() {

        if (
            fullName.classList.contains(
                "input-error"
            )
        ) {

            validateFullName();
        }
    }
);


fullName.addEventListener(
    "blur",
    validateFullName
);


/* =========================================================
   20. BLOOD TYPE VALIDATION
========================================================= */

bloodType.addEventListener(
    "change",
    validateBloodType
);

bloodType.addEventListener(
    "blur",
    validateBloodType
);


/* =========================================================
   21. PHONE VALIDATION
========================================================= */

phone.addEventListener(
    "input",
    function() {

        if (
            phone.classList.contains(
                "input-error"
            )
        ) {

            validatePhone();
        }
    }
);


phone.addEventListener(
    "blur",
    validatePhone
);


/* =========================================================
   22. LOCATION VALIDATION
========================================================= */

locationInput.addEventListener(
    "blur",
    validateLocation
);


/* =========================================================
   23. LAST DONATION VALIDATION
========================================================= */

lastDonation.addEventListener(
    "change",
    validateLastDonation
);

lastDonation.addEventListener(
    "blur",
    validateLastDonation
);


/* =========================================================
   24. CONSENT VALIDATION
========================================================= */

consent.addEventListener(
    "change",
    validateConsent
);


/* =========================================================
   25. FORM SUBMISSION → PHP → POSTGRESQL
========================================================= */

form.addEventListener(
    "submit",
    async function(event) {

        event.preventDefault();


        /* Validate everything */

        const isValid =
            validateForm();


        if (!isValid) {

            focusFirstInvalid();

            return;
        }


        /* Prevent double submission */

        submitButton.disabled = true;


        submitButton.innerHTML =
            "Registering...";


        try {

            /* =========================================
               SEND FORM TO PHP
            ========================================= */

            const formData =
                new FormData(form);


            const response =
                await fetch(
                    "php/register-donor.php",
                    {
                        method: "POST",
                        body: formData
                    }
                );


            if (!response.ok) {

                throw new Error(
                    "Server error."
                );
            }


            const data =
                await response.json();


            /* =========================================
               PHP SUCCESS
            ========================================= */

            if (data.success) {

                alert(
                    data.message
                );


                /*
                 * Reset the form.
                 */

                form.reset();


                /*
                 * Reset location state.
                 */

                locationVerified = false;

                latitudeInput.value = "";
                longitudeInput.value = "";

                locationStatus.textContent = "";

                getLocationButton.textContent =
                    "Allow Location";


                /*
                 * Reset button.
                 */

                submitButton.disabled = false;

                submitButton.innerHTML =
                    "Register as a Donor";


                return;
            }


            /* =========================================
               PHP ERROR
            ========================================= */

            alert(
                data.message ||
                "Unable to register donor."
            );


            submitButton.disabled = false;

            submitButton.innerHTML =
                "Register as a Donor";

        }


        catch (error) {

            console.error(error);

            alert(
                "Unable to connect to the server. Please try again."
            );


            submitButton.disabled = false;

            submitButton.innerHTML =
                "Register as a Donor";
        }
    }
);


/* =========================================================
   26. MOBILE HAMBURGER MENU
========================================================= */

const menuButton =
    document.querySelector(".menu-button");

const mainNav =
    document.querySelector(".main-nav");


if (menuButton && mainNav) {

    menuButton.addEventListener(
        "click",
        function() {

            const isOpen =
                mainNav.classList.toggle(
                    "nav-open"
                );


            menuButton.classList.toggle(
                "menu-open"
            );


            menuButton.setAttribute(
                "aria-expanded",
                String(isOpen)
            );


            menuButton.setAttribute(
                "aria-label",
                isOpen
                    ? "Close navigation menu"
                    : "Open navigation menu"
            );
        }
    );


    const navLinks =
        mainNav.querySelectorAll("a");


    navLinks.forEach(
        function(link) {

            link.addEventListener(
                "click",
                function() {

                    mainNav.classList.remove(
                        "nav-open"
                    );

                    menuButton.classList.remove(
                        "menu-open"
                    );

                    menuButton.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                    menuButton.setAttribute(
                        "aria-label",
                        "Open navigation menu"
                    );
                }
            );
        }
    );


    window.addEventListener(
        "resize",
        function() {

            if (window.innerWidth >= 900) {

                mainNav.classList.remove(
                    "nav-open"
                );

                menuButton.classList.remove(
                    "menu-open"
                );

                menuButton.setAttribute(
                    "aria-expanded",
                    "false"
                );

                menuButton.setAttribute(
                    "aria-label",
                    "Open navigation menu"
                );
            }
        }
    );
}


/* =========================================================
   27. ACCESSIBILITY
========================================================= */

fullName.setAttribute(
    "aria-required",
    "true"
);

bloodType.setAttribute(
    "aria-required",
    "true"
);

phone.setAttribute(
    "aria-required",
    "true"
);

locationInput.setAttribute(
    "aria-required",
    "true"
);

consent.setAttribute(
    "aria-required",
    "true"
);


/* =========================================================
   28. INITIAL STATE
========================================================= */

clearError(
    fullName,
    fullNameError
);

clearError(
    bloodType,
    bloodTypeError
);

clearError(
    phone,
    phoneError
);

clearError(
    locationInput,
    locationError
);

clearError(
    lastDonation,
    lastDonationError
);

consentError.textContent = "";