
// Quitter la discussion lorsqu'on appuie sur la touche 'Escape'

window.addEventListener('keydown', (event) => {
    if (event.key == "Escape") {
        document.location.href = "./../messagerie/discussions.php";
    }
});

// Gestion du menu contextuel

const messages = document.querySelectorAll('.message');
const menu = document.querySelector('.menu');
const menuContainer = document.getElementById('menu-container');
const menuItems = document.querySelectorAll('.menu li');
const menuDivider = document.getElementById('menu-divider');

console.log(menuItems);


const viewportWidth = window.innerWidth;
const viewportHeight = window.innerHeight;
const menuWidth = menu.offsetWidth;
const menuHeight = menu.offsetHeight;
let top_limit = document.querySelector('#header');
// console.log(top_limit.clientTop)


messages.forEach(message => {
    message.addEventListener('contextmenu', (e) => {

        // Gestion du contenu du menu contextuel

        if (message.classList.contains('from-other')) {
            // Si le message est d'autrui on ne permet pas l'édition
            menuItems[0].classList.add('d-none');
            menuDivider.classList.add('d-none');

            // menuContainer.getElementById('supprimer-pour-moi').classList.remove('d-none');
            menuContainer.querySelector('#supprimer-pour-moi').classList.remove('d-none');

            // On ne permet pas non plus qu'il puisse supprimer pour tout le monde
            menuItems.forEach(item => {
                if(item.innerText == "Supprimer pour moi"){

                }
            });
        } else {
            // Le message vient de moi donc on permet l'édition en première apporximation
            menuItems[0].classList.remove('d-none');
            menuDivider.classList.remove('d-none');
        }

        // Gestion du positionnement du menu contextuel
        e.preventDefault();
        let left = e.clientX;
        let top = e.clientY;
        // console.log(left, top);

        // Droite
        if (left + menuWidth > viewportWidth) {
            left = left - menuWidth;
        }
        //Bas
        if (top + menuHeight > viewportHeight) {
            top = top - menuHeight;
        }
        // if(e.offsetY)

        // console.log(left, top);

        if (menu.classList.contains('show')) {
            // Si le menu a déjà la classe "show" c'est qu'on a cliqué sur un autre message sans cliquer dans un vide pour faire disparaître le menu donc on le fait disparaître et réapparaître automatiquement

            menu.classList.remove('show');
            setTimeout(() => {
                menuContainer.style.top = `${top}px`;
                menuContainer.style.left = `${left}px`;
                // menu.style.display = "block"

                menu.classList.add('show');
                // On ne fait rien pendant 1s
            }, 100);
        } else {
            // Le menu n'était pas actif antérieurement

            menuContainer.style.top = `${top}px`;
            menuContainer.style.left = `${left}px`;
            menu.classList.add('show');
        }




        document.getElementById('messagesCont').classList.add('no-scroll'); // On empêche le scroll pendant que le menu est actif
    });
});

// Masquage du menu

document.addEventListener('click', (e) => {
    if (!menu.contains(e.target)) {
        menu.classList.remove('show');
        document.getElementById('messagesCont').classList.remove('no-scroll');
        // menu.style.display = "none";
    }
});
