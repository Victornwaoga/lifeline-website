// document.addEventListener("DOMContentLoaded", () => {
   
    const menuButton = document.querySelector(".menu-button");
    const mainNav = document.querySelector(".main-nav");


   // Open/close mobile menu
    menuButton.addEventListener("click", () => {

       const isOpen = mainNav.classList.toggle("nav-open");

       menuButton.classList.toggle("menu-open");

       menuButton.setAttribute(
          "aria-expanded",
           isOpen
       );

    });


    // Close menu when a navigation link is clicked
    const navLinks = document.querySelectorAll(".main-nav a");

    navLinks.forEach((link) => {

        link.addEventListener("click", () => {

            mainNav.classList.remove("nav-open");

            menuButton.classList.remove("menu-open");

            menuButton.setAttribute(
               "aria-expanded",
               "false"
            );

        });

    });


    // Close mobile menu when screen becomes desktop size
    window.addEventListener("resize", () => {

       if (window.innerWidth >= 900) {

           mainNav.classList.remove("nav-open");

           menuButton.classList.remove("menu-open");

           menuButton.setAttribute(
               "aria-expanded",
               "false"
           );

        }

    });
// });