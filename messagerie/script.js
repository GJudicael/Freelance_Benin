// Fonctions utilitaires

function message_modifiable(message) {
    const created_at = new Date(message.querySelector('.created_at').textContent);
    const now = new Date();
    const diffMs = now - created_at; // en ms
    if (diffMs < delai_modification_messages && message.classList.contains('from-me')) {
        // Le message est de moi et a été envoyé depuis moins de 5 minutes
        return true;
    } else {
        return false;
    }
}

// Quitter la discussion lorsqu'on appuie sur la touche 'Escape'

window.addEventListener('keydown', (event) => {
    if (event.key == "Escape") {
        document.location.href = "discussions.php";
    }
});

// Gestion du menu contextuel

const messages = document.querySelectorAll('.message');
const menu = document.querySelector('.menu');
const menuContainer = document.getElementById('menu-container');
const menuItems = document.querySelectorAll('.menu li');
const menuDivider = document.getElementById('menu-divider');
const optionModifier = document.getElementById('modifier');
const messagesCont = document.getElementById('messagesCont');
const delai_modification_messages = 5 * 60 * 1000; // 5 minutes en ms


const viewportWidth = window.innerWidth;
const viewportHeight = window.innerHeight;
let top_limit = document.querySelector('#header');
// console.log(top_limit.clientTop)


messages.forEach(message => {
    message.addEventListener('contextmenu', (e) => {
        // Gestion du contenu du menu contextuel

        // Complétion des ids appropriés
        const message_id = message.querySelector('.message_id').textContent;
        optionModifier.href = optionModifier.href + message_id;

        // Affichage d'options sur conditions

        if (message_modifiable(message)) {
            optionModifier.classList.remove('d-none');
            menuDivider.classList.remove('d-none');
        } else {
            optionModifier.classList.add('d-none');
            menuDivider.classList.add('d-none');
        }

        // Gestion du positionnement du menu contextuel
        const menuWidth = menu.offsetWidth;
        const menuHeight = menu.offsetHeight;

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

if (messagesCont) {
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target)) {
            menu.classList.remove('show');
            document.getElementById('messagesCont').classList.remove('no-scroll');
            // menu.style.display = "none";
        }
    });
}

// Au clic sur l'option de modification

optionModifier.addEventListener('click', () => {

});
