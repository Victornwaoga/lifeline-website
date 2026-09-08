const searchForm = document.getElementById("donorSearchForm");

const bloodTypeInput = document.getElementById("bloodType");

const locationInput = document.getElementById("location");

const donorList = document.getElementById("donorList");

const resultsCount = document.getElementById("resultsCount");

const noResults = document.getElementById("noResults");

const donorCards = Array.from(
    document.querySelectorAll(".donor-card")
);


/* =========================================================
   2. MOBILE HAMBURGER MENU
========================================================= */

const menuButton =
    document.querySelector(".menu-button");

const mainNav =
    document.querySelector(".main-nav");


if (menuButton && mainNav) {

    menuButton.addEventListener("click", () => {

        const isOpen =
            mainNav.classList.toggle("nav-open");

        menuButton.classList.toggle(
            "menu-open"
        );

        menuButton.setAttribute(
            "aria-expanded",
            isOpen
        );

    });


    /* Close menu after clicking a link */

    const navLinks =
        mainNav.querySelectorAll("a");

    navLinks.forEach((link) => {

        link.addEventListener("click", () => {

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

        });

    });


    /* Close menu when switching to desktop */

    window.addEventListener("resize", () => {

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

        }

    });

}


/* =========================================================
   3. NORMALIZE TEXT
   Makes searching easier.
========================================================= */

// function normalizeText(text) {

//     return text
//         .toLowerCase()
//         .trim();

// }


/* =========================================================
   4. FILTER DONORS
========================================================= */

// function filterDonors() {

//     const selectedBloodType =
//         normalizeText(
//             bloodTypeInput.value
//         );


//     const enteredLocation =
//         normalizeText(
//             locationInput.value
//         );

    // const matchingDonors =
    //     donorCards.filter((card) => {

    //         const donorBloodType =
    //             normalizeText(
    //                 card.dataset.blood
    //             );


    //         const donorLocation =
    //             normalizeText(
    //                 card.dataset.location
    //             );


    //         /*
    //            Blood type condition
    //         */

    //         const bloodMatches =
    //             selectedBloodType === "" ||
    //             donorBloodType === selectedBloodType;


    //         /*
    //            Location condition

    //            "aba" will match
    //            "abakaliki"
    //         */

    //         const locationMatches =
    //             enteredLocation === "" ||
    //             donorLocation.includes(
    //                 enteredLocation
    //             );


    //         return (
    //             bloodMatches &&
    //             locationMatches
    //         );

    //     });


    /* =====================================================
       5. SHOW / HIDE DONOR CARDS
    ===================================================== */

    // donorCards.forEach((card) => {

    //     const isMatch =
    //         matchingDonors.includes(card);


    //     if (isMatch) {

    //         card.style.display = "";

    //     } else {

    //         card.style.display = "none";

    //     }

    // });


    /* =====================================================
       6. UPDATE RESULT COUNT
    ===================================================== */

    // updateResultsCount(
    //     matchingDonors.length
    // );


    /* =====================================================
       7. SHOW / HIDE NO RESULTS
    ===================================================== */

//     if (matchingDonors.length === 0) {

//         noResults.style.display = "block";

//     } else {

//         noResults.style.display = "none";

//     }

// }


/* =========================================================
   8. UPDATE RESULTS COUNT
========================================================= */

// function updateResultsCount(numberOfDonors) {

//     if (numberOfDonors === 1) {

//         resultsCount.textContent =
//             "1 potential donor found";

//     } else {

//         resultsCount.textContent =
//             `${numberOfDonors} potential donors found`;

//     }

// }


/* =========================================================
   9. LIVE SEARCH
========================================================= */


// /*
//    Whenever the user types a location,
//    filter the donor cards immediately.
// */

// locationInput.addEventListener(
//     "input",
//     filterDonors
// );


// /*
//    Blood type also updates immediately.
// */

// bloodTypeInput.addEventListener(
//     "change",
//     filterDonors
// );


/* =========================================================
   10. SEARCH FORM SUBMISSION
========================================================= */

// searchForm.addEventListener(
//     "submit",
//     (event) => {

//         /*
//            Prevent the browser from
//            refreshing / changing page.
//         */

//         event.preventDefault();


//         filterDonors();


//         /*
//            Bring the results into view
//            after searching.
//         */

//         const resultsSection =
//             document.querySelector(
//                 ".results-section"
//             );


//         if (resultsSection) {

//             resultsSection.scrollIntoView({
//                 behavior: "smooth",
//                 block: "start"
//             });

//         }

//     }
// );

// /* CLEAR SEARCH */

// function clearSearch() {

//     bloodTypeInput.value = "";

//     locationInput.value = "";

//     filterDonors();

// }


// document.addEventListener(
//     "keydown",
//     (event) => {

//         if (event.key === "Escape") {

//             clearSearch();

//         }

//     }
// );


// const contactButtons =
//     document.querySelectorAll(".contact-button");


// contactButtons.forEach((button) => {

//     button.addEventListener("click", async () => {

//         const donorId =
//             button.dataset.donorId;

//         if (!donorId) {

//             alert("Donor information is unavailable.");

//             return;
//         }


//         button.disabled = true;

//         button.textContent = "Loading...";


//         try {

//             const formData =
//                 new FormData();

//             formData.append(
//                 "donor_id",
//                 donorId
//             );


//             const response =
//                 await fetch(
//                     "php/get-donor-contact.php",
//                     {
//                         method: "POST",
//                         body: formData
//                     }
//                 );


//             const data =
//                 await response.json();


//             if (data.success) {

//                 alert(
//                     "Donor: " +
//                     data.donor.full_name +
//                     "\nBlood Type: " +
//                     data.donor.blood_type +
//                     "\nLocation: " +
//                     data.donor.location +
//                     "\nPhone: " +
//                     data.donor.phone
//                 );

//             } else {

//                 alert(
//                     data.message ||
//                     "Unable to get donor contact."
//                 );

//             }

//         } catch (error) {

//             console.error(error);

//             alert(
//                 "Unable to connect to the server."
//             );

//         } finally {

//             button.disabled = false;

//             button.innerHTML =
//                 'View Contact <span>→</span>';

//         }

//     });

// });


// filterDonors();

/* =========================================================
   3. SEARCH DONORS FROM POSTGRESQL
========================================================= */

async function searchDonors() {

    const selectedBloodType =
        bloodTypeInput.value.trim();

    const enteredLocation =
        locationInput.value.trim();

    const formData = new FormData();

    formData.append(
        "blood_type",
        selectedBloodType
    );

    formData.append(
        "location",
        enteredLocation
    );

    resultsCount.textContent = "Searching...";
    noResults.style.display = "none";

    try {

        const response = await fetch(
            "php/search-donors.php",
            {
                method: "POST",
                body: formData
            }
        );

        const data = await response.json();

        if (!data.success) {

            resultsCount.textContent = "Search failed";

            noResults.style.display = "block";

            noResults.querySelector("h3").textContent =
                "Unable to search donors";

            noResults.querySelector("p").textContent =
                data.message || "Please try again.";

            return;
        }

        /*
        -------------------------------------------------------
        Clear the current donor cards
        -------------------------------------------------------
        */

        donorList.innerHTML = "";

        /*
        -------------------------------------------------------
        Display the donors returned by PostgreSQL
        -------------------------------------------------------
        */

        data.donors.forEach((donor) => {

            const nameParts =
                donor.full_name.trim().split(/\s+/);

            let initials = "";

            nameParts.forEach((part) => {

                if (part !== "") {

                    initials +=
                        part.charAt(0).toUpperCase();

                }

            });

            initials =
                initials.substring(0, 2);

            const card =
                document.createElement("article");

            card.className = "donor-card";

            card.dataset.blood =
                donor.blood_type;

            card.dataset.location =
                donor.location;

            card.innerHTML = `
                <div class="donor-card-top">

                    <div class="donor-avatar">
                        ${escapeHtml(initials)}
                    </div>

                    <div class="blood-badge">
                        ${escapeHtml(
                            donor.blood_type.slice(0, -1)
                        )}

                        <span>
                            ${escapeHtml(
                                donor.blood_type.slice(-1)
                            )}
                        </span>
                    </div>

                </div>

                <h3>
                    ${escapeHtml(donor.full_name)}
                </h3>

                <p class="donor-location">
                    ${escapeHtml(donor.location)}
                </p>

                <div class="donor-status">
                    <span class="status-dot"></span>
                    Potential donor
                </div>

                <button
                    class="contact-button"
                    type="button"
                    data-donor-id="${escapeHtml(donor.id)}"
                >
                    View Contact <span>→</span>
                </button>
            `;

            donorList.appendChild(card);

        });

        /*
        -------------------------------------------------------
        Update result count
        -------------------------------------------------------
        */

        updateResultsCount(data.count);

        /*
        -------------------------------------------------------
        Show or hide no-results message
        -------------------------------------------------------
        */

        if (data.count === 0) {

            noResults.style.display = "block";

        } else {

            noResults.style.display = "none";

        }

        /*
        -------------------------------------------------------
        Reconnect contact buttons
        -------------------------------------------------------
        */

        attachContactButtons();

    } catch (error) {

        console.error(
            "Donor search error:",
            error
        );

        resultsCount.textContent =
            "Search failed";

        noResults.style.display = "block";

        noResults.querySelector("h3").textContent =
            "Unable to connect to LifeLine";

        noResults.querySelector("p").textContent =
            "Please check your connection and try again.";

    }

}


/* =========================================================
   4. UPDATE RESULT COUNT
========================================================= */

function updateResultsCount(numberOfDonors) {

    if (numberOfDonors === 1) {

        resultsCount.textContent =
            "1 potential donor found";

    } else {

        resultsCount.textContent =
            `${numberOfDonors} potential donors found`;

    }

}


/* =========================================================
   5. ESCAPE HTML
   Helps safely display database values.
========================================================= */

function escapeHtml(value) {

    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}


/* =========================================================
   6. CONTACT BUTTONS
========================================================= */

function attachContactButtons() {

    const contactButtons =
        document.querySelectorAll(".contact-button");

    contactButtons.forEach((button) => {

        button.addEventListener(
            "click",
            async () => {

                const donorId =
                    button.dataset.donorId;

                if (!donorId) {

                    alert(
                        "Donor information is unavailable."
                    );

                    return;
                }

                button.disabled = true;

                button.textContent = "Loading...";

                try {

                    const formData =
                        new FormData();

                    formData.append(
                        "donor_id",
                        donorId
                    );

                    const response =
                        await fetch(
                            "php/get-donor-contact.php",
                            {
                                method: "POST",
                                body: formData
                            }
                        );

                    const data =
                        await response.json();

                    if (data.success) {

                        alert(
                            "Donor: " +
                            data.donor.full_name +
                            "\nBlood Type: " +
                            data.donor.blood_type +
                            "\nLocation: " +
                            data.donor.location +
                            "\nPhone: " +
                            data.donor.phone
                        );

                    } else {

                        alert(
                            data.message ||
                            "Unable to get donor contact."
                        );

                    }

                } catch (error) {

                    console.error(error);

                    alert(
                        "Unable to connect to the server."
                    );

                } finally {

                    button.disabled = false;

                    button.innerHTML =
                        'View Contact <span>→</span>';

                }

            }
        );

    });

}


/* =========================================================
   7. SEARCH FORM
========================================================= */

searchForm.addEventListener(
    "submit",
    async (event) => {

        event.preventDefault();

        await searchDonors();

        const resultsSection =
            document.querySelector(
                ".results-section"
            );

        if (resultsSection) {

            resultsSection.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });

        }

    }
);


/* =========================================================
   8. LIVE LOCATION SEARCH
========================================================= */

locationInput.addEventListener(
    "input",
    searchDonors
);


/* =========================================================
   9. BLOOD TYPE SEARCH
========================================================= */

bloodTypeInput.addEventListener(
    "change",
    searchDonors
);


/* =========================================================
   10. CLEAR SEARCH
========================================================= */

function clearSearch() {

    bloodTypeInput.value = "";

    locationInput.value = "";

    searchDonors();

}


/* =========================================================
   11. ESCAPE KEY
========================================================= */

document.addEventListener(
    "keydown",
    (event) => {

        if (event.key === "Escape") {

            clearSearch();

        }

    }
);


/* =========================================================
   12. INITIAL SEARCH
========================================================= */

searchDonors();