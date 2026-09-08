const menuButton =
document.querySelector(".menu-button");

const mainNav =
document.querySelector(".main-nav");

/* =========================================================
2. MOBILE HAMBURGER MENU
========================================================= */

if (menuButton && mainNav) {

menuButton.addEventListener(
    "click",
    function () {

        const isOpen =
            mainNav.classList.toggle("nav-open");

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


/* =====================================================
   CLOSE MENU AFTER CLICKING A LINK
===================================================== */

const navLinks =
    mainNav.querySelectorAll("a");


navLinks.forEach(
    function (link) {

        link.addEventListener(
            "click",
            function () {

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


/* =====================================================
   CLOSE MENU WITH ESCAPE KEY
===================================================== */

document.addEventListener(
    "keydown",
    function (event) {

        if (event.key === "Escape") {

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


/* =====================================================
   RESET MENU WHEN RETURNING TO DESKTOP
===================================================== */

window.addEventListener(
    "resize",
    function () {

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
3. CONFIRM JAVASCRIPT LOADED
========================================================= */

console.log(
"LifeLine Urgent Requests JavaScript loaded successfully."
);