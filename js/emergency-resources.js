document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       GET ELEMENTS
    ===================================================== */

    const menuButton = document.querySelector(".menu-button");
    const mainNavigation = document.querySelector(".main-nav");

    const hospitalSearch =
        document.getElementById("hospitalSearch");

    const hospitalList =
        document.getElementById("hospitalList");

    const bloodBankList =
        document.getElementById("bloodBankList");

    const ambulanceList =
        document.getElementById("ambulanceList");


    /* =====================================================
       2. MOBILE HAMBURGER MENU
    ===================================================== */

    if (menuButton && mainNavigation) {

        menuButton.addEventListener("click", function () {

            const isOpen =
                mainNavigation.classList.toggle("nav-open");

            menuButton.classList.toggle(
                "menu-open",
                isOpen
            );

            menuButton.setAttribute(
                "aria-expanded",
                isOpen
            );

        });


        /* =================================================
           CLOSE MENU WHEN NAVIGATION LINK IS CLICKED
        ================================================= */

        const navigationLinks =
            mainNavigation.querySelectorAll("a");

        navigationLinks.forEach(function (link) {

            link.addEventListener("click", function () {

                mainNavigation.classList.remove(
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


        /* =================================================
           CLOSE MENU WHEN CLICKING OUTSIDE
        ================================================= */

        document.addEventListener("click", function (event) {

            const clickedInsideMenu =
                mainNavigation.contains(event.target);

            const clickedMenuButton =
                menuButton.contains(event.target);


            if (
                !clickedInsideMenu &&
                !clickedMenuButton &&
                mainNavigation.classList.contains("nav-open")
            ) {

                mainNavigation.classList.remove(
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


        /* =================================================
           CLOSE MENU WITH ESCAPE KEY
        ================================================= */

        document.addEventListener("keydown", function (event) {

            if (event.key === "Escape") {

                mainNavigation.classList.remove(
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


    /* =====================================================
       3. HOSPITAL SEARCH
    ===================================================== */

    if (hospitalSearch && hospitalList) {

        const hospitalCards =
            hospitalList.querySelectorAll(".resource-card");


        hospitalSearch.addEventListener(
            "input",
            function () {

                const searchValue =
                    hospitalSearch.value
                        .trim()
                        .toLowerCase();


                let visibleCards = 0;


                hospitalCards.forEach(function (card) {

                    const hospitalName =
                        card.dataset.name || "";


                    const hospitalLocation =
                        card.dataset.location || "";


                    const cardText =
                        card.textContent || "";


                    const searchableText =
                        (
                            hospitalName +
                            " " +
                            hospitalLocation +
                            " " +
                            cardText
                        ).toLowerCase();


                    if (
                        searchableText.includes(searchValue)
                    ) {

                        card.style.display = "";

                        visibleCards++;

                    } else {

                        card.style.display = "none";

                    }

                });


                /* =========================================
                   NO RESULTS MESSAGE
                ========================================= */

                showNoResultsMessage(
                    hospitalList,
                    visibleCards,
                    "No hospitals found matching your search."
                );

            }
        );

    }


    /* =====================================================
       4. GENERIC RESOURCE SEARCH FUNCTION
       
       This can later be reused for:
       - Blood banks
       - Ambulances
       - Clinics
       - Pharmacies
       - Other emergency resources
    ===================================================== */

    function searchResources(
        searchInput,
        resourceList,
        noResultsText
    ) {

        if (!searchInput || !resourceList) {
            return;
        }


        const cards =
            resourceList.querySelectorAll(".resource-card");


        searchInput.addEventListener(
            "input",
            function () {

                const searchValue =
                    searchInput.value
                        .trim()
                        .toLowerCase();


                let visibleCards = 0;


                cards.forEach(function (card) {

                    const name =
                        card.dataset.name || "";


                    const location =
                        card.dataset.location || "";


                    const cardText =
                        card.textContent || "";


                    const searchableText =
                        (
                            name +
                            " " +
                            location +
                            " " +
                            cardText
                        ).toLowerCase();


                    const matches =
                        searchableText.includes(
                            searchValue
                        );


                    if (matches) {

                        card.style.display = "";

                        visibleCards++;

                    } else {

                        card.style.display = "none";

                    }

                });


                showNoResultsMessage(
                    resourceList,
                    visibleCards,
                    noResultsText
                );

            }
        );

    }


    /* =====================================================
       5. NO RESULTS MESSAGE
    ===================================================== */

    function showNoResultsMessage(
        list,
        visibleCards,
        message
    ) {

        let noResults =
            list.querySelector(".no-results");


        if (visibleCards === 0) {

            if (!noResults) {

                noResults =
                    document.createElement("div");

                noResults.className =
                    "no-results";


                noResults.textContent =
                    message;


                noResults.style.padding =
                    "20px";


                noResults.style.background =
                    "#ffffff";


                noResults.style.border =
                    "1px solid #dfecef";


                noResults.style.borderRadius =
                    "10px";


                noResults.style.color =
                    "#65737c";


                noResults.style.textAlign =
                    "center";


                noResults.style.fontSize =
                    "0.9rem";


                list.appendChild(noResults);

            }

        } else {

            if (noResults) {

                noResults.remove();

            }

        }

    }


    /* =====================================================
       6. SMOOTH SCROLL FOR QUICK ACTIONS
    ===================================================== */

    const internalLinks =
        document.querySelectorAll(
            'a[href^="#"]'
        );


    internalLinks.forEach(function (link) {

        link.addEventListener("click", function (event) {

            const targetId =
                link.getAttribute("href");


            /* =============================================
               Ignore empty "#" links
            ============================================= */

            if (
                !targetId ||
                targetId === "#"
            ) {

                return;

            }


            const target =
                document.querySelector(targetId);


            if (target) {

                event.preventDefault();


                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            }

        });

    });


    /* =====================================================
       7. PROTECT EMPTY DIRECTION LINKS
       
       Until real Google Maps/location URLs are added,
       clicking "Directions" should not simply jump to
       the top of the page.
    ===================================================== */

    const emptyLinks =
        document.querySelectorAll(
            'a[href="#"]'
        );


    emptyLinks.forEach(function (link) {

        link.addEventListener("click", function (event) {

            event.preventDefault();

            console.log(
                "Directions link is waiting for a real location URL."
            );

        });

    });


    /* =====================================================
       8. GENERIC SEARCH SUPPORT
       
       Currently there are no search boxes for these two
       sections, but the function is ready for later.
    ===================================================== */

    const bloodBankSearch =
        document.getElementById("bloodBankSearch");


    const ambulanceSearch =
        document.getElementById("ambulanceSearch");


    searchResources(
        bloodBankSearch,
        bloodBankList,
        "No blood banks found matching your search."
    );


    searchResources(
        ambulanceSearch,
        ambulanceList,
        "No ambulance services found matching your search."
    );




    /* =====================================================
       9. CALL BUTTON SAFETY
       
       Empty tel: links should not attempt to call.
    ===================================================== */

    const callLinks =
        document.querySelectorAll(
            'a[href^="tel:"]'
        );


    callLinks.forEach(function (link) {

        const phoneNumber =
            link.getAttribute("href");


        if (
            !phoneNumber ||
            phoneNumber === "tel:"
        ) {

            link.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();


                    console.log(
                        "This contact does not have a phone number yet."
                    );

                }
            );

        }

    });


    /* =====================================================
       10. CURRENT YEAR
       
       If you later add an element with:
       
       <span id="currentYear"></span>
       
       JavaScript will automatically update it.
    ===================================================== */

    const currentYear =
        document.getElementById("currentYear");


    if (currentYear) {

        currentYear.textContent =
            new Date().getFullYear();

    }


    /* =====================================================
       11. PAGE READY MESSAGE
       
       Useful while developing.
    ===================================================== */

    console.log(
        "LifeLine Emergency Resources page loaded successfully."
    );

    const hospitals = document.querySelectorAll(".hospital-card");
    const loadMoreButton = document.getElementById("loadMoreHospitals");
    const loadMoreContainer = document.getElementById("hospitalLoadMoreContainer");

    let visibleCount = 6;

    function showHospitals() {

        hospitals.forEach((hospital, index) => {
            hospital.style.display = index < visibleCount ? "" : "none";
        });

        if (visibleCount >= hospitals.length) {
            loadMoreContainer.style.display = "none";
        }
    }

    showHospitals();

    loadMoreButton.addEventListener("click", function () {
        visibleCount += 6;
        showHospitals();
    });

});

    