document.addEventListener("DOMContentLoaded", () => {

    // ==============================
    // MOBILE MENU
    // ==============================

    const menuButton = document.querySelector(".menu-button");
    const navigation = document.querySelector(".main-nav");

    if (menuButton && navigation) {

        menuButton.addEventListener("click", () => {

            navigation.classList.toggle("active");

            const isOpen = navigation.classList.contains("active");

            menuButton.setAttribute("aria-expanded", isOpen);

            menuButton.textContent = isOpen ? "✕" : "☰";
        });

        // Close menu when a navigation link is clicked
        const navLinks = navigation.querySelectorAll("a");

        navLinks.forEach(link => {
            link.addEventListener("click", () => {

                navigation.classList.remove("active");

                menuButton.setAttribute("aria-expanded", "false");

                menuButton.textContent = "☰";
            });
        });
    }


    // ==============================
    // HEADER EFFECT ON SCROLL
    // ==============================

    const header = document.querySelector(".site-header");

    if (header) {

        window.addEventListener("scroll", () => {

            if (window.scrollY > 30) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }

        });
    }


    // ==============================
    // SMOOTH SCROLL
    // ==============================

    const smoothLinks = document.querySelectorAll('a[href^="#"]');

    smoothLinks.forEach(link => {

        link.addEventListener("click", event => {

            const targetId = link.getAttribute("href");

            if (targetId === "#") {
                return;
            }

            const target = document.querySelector(targetId);

            if (target) {

                event.preventDefault();

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });

    });


    // ==============================
    // SCROLL REVEAL
    // ==============================

    const revealElements = document.querySelectorAll(
        ".feature-card, .step, .notice-box, .privacy-section"
    );

    if ("IntersectionObserver" in window) {

        const observer = new IntersectionObserver(
            entries => {

                entries.forEach(entry => {

                    if (entry.isIntersecting) {

                        entry.target.classList.add("visible");

                        observer.unobserve(entry.target);
                    }

                });

            },
            {
                threshold: 0.15
            }
        );

        revealElements.forEach(element => {

            element.classList.add("reveal");

            observer.observe(element);

        });

    } else {

        revealElements.forEach(element => {
            element.classList.add("visible");
        });

    }


    // ==============================
    // CURRENT YEAR
    // ==============================

    const yearElement = document.querySelector("#current-year");

    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }


    // ==============================
    // GET STARTED BUTTON FEEDBACK
    // ==============================

    const startButton = document.querySelector(
        'a[href="index.php"]'
    );

    if (startButton) {

        startButton.addEventListener("click", () => {

            startButton.classList.add("button-clicked");

            setTimeout(() => {
                startButton.classList.remove("button-clicked");
            }, 300);

        });

    }

});