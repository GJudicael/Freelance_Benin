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
const optionSupprimer = document.getElementById('supprimer');
const optionSupprimer_pour_moi = document.getElementById('supprimer_pour_moi');
const messagesCont = document.getElementById('messagesCont');
const delai_modification_messages = 5 * 60 * 1000; // 5 minutes en ms


const viewportWidth = window.innerWidth;
const viewportHeight = window.innerHeight;
let top_limit = document.querySelector('#header');
const espacement = 10;
// console.log(top_limit.clientTop)


messages.forEach(message => {
    // Quelques modifications statiques

    // Ajustement de la longeur du message
    const infos_supp = message.querySelector('#infos_sup');
    if (infos_supp) {
        const width = message.clientWidth + infos_supp.clientWidth + espacement;
        message.style.width = `${width}px`;
    }

    message.addEventListener('contextmenu', (e) => {

        menuItems.forEach(item => {
            if (item.classList.contains)
                item.classList.remove('d-none');
        });

        // Gestion du contenu du menu contextuel
        const message_id = message.querySelector('.message_id').textContent;

        if (!message.classList.contains('deleted')) {

            if (!optionSupprimer_pour_moi.classList.contains('d-none')) {
                optionSupprimer_pour_moi.classList.add('d-none');
            }

            // Complétion des ids appropriés
            optionModifier.querySelector('a').href = './traitements/modifier_message.php?message_id=' + message_id;

            // Affichage d'options sur conditions

            if (message_modifiable(message)) {
                optionModifier.classList.remove('d-none');
                menuDivider.classList.remove('d-none');
            } else {
                optionModifier.classList.add('d-none');
                menuDivider.classList.add('d-none');
            }
        } else {
            // Le message sélectionné est un message du style 'ce message est supprimé'
            menuItems.forEach(item => {
                if (!item.classList.contains('#supprimer_pour_moi')) {
                    item.classList.add('d-none');
                }
            });

            optionSupprimer_pour_moi.querySelector('input.message_id').value = message_id;
            optionSupprimer_pour_moi.classList.remove('d-none');

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

        // Autres actions

        // Mettre dans l'input caché de suppresion l'id du message sur lequel on a cliqué
        document.getElementById('deletionInput').value = message_id;
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
    optionSupprimer.addEventListener('click', ()=>{
        menu.classList.remove('show');
        document.getElementById('messagesCont').classList.remove('no-scroll');
    })
}

// Au clic sur l'option de modification

// optionModifier.addEventListener('click', () => {

// });

// // Au clic sur l'option de suppression

// optionSupprimer.addEventListener('click', () => {
//     // console.log('j\'ai cliqué sur le bouton de suppression');


// });
