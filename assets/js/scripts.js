/*!
* Start Bootstrap - Landing Page v6.0.6 (https://startbootstrap.com/theme/landing-page)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-landing-page/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project

document.addEventListener('DOMContentLoaded', () => {
        
        togglePassword("eyeToggle1", "mot_de_passe");
        togglePassword("eyeToggle2", "mot_de_passe_confirmation");


        function togglePassword(toggledId, inputId) {
                const eyeToggle = document.getElementById(toggledId);
                const passwordInput = document.getElementById(inputId);

                eyeToggle.addEventListener("click", function () {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                this.classList.toggle("bi-eye");
                this.classList.toggle("bi-eye-slash");
        });
        }
});
