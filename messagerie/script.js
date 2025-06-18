
// Quitter la discussion lorsqu'on appuie sur la touche 'Escape'

window.addEventListener('keydown', (event) => {
    if (event.key == "Escape") {
        document.location.href = "./../messagerie/discussions.php"
    }
})

// Gestion du menu contextuel

const messages = document.querySelectorAll('.message')
const menu = document.querySelector('.menu')
const menuContainer = document.getElementById('menu-container')

const viewportWidth = window.innerWidth
const viewportHeight = window.innerHeight
const menuWidth = menu.offsetWidth
const menuHeight = menu.offsetHeight
let top_limit = document.querySelector('#header')
console.log(top_limit.clientTop)


messages.forEach(message => {
    message.addEventListener('contextmenu', (e) => {
        e.preventDefault()
        let left = e.clientX
        let top = e.clientY
        // let right


        // console.log(e);

        // const {clientX : left, clientY: top} = e;
        // Droite
        if (left + menuWidth > viewportWidth) {
            left = left - menuWidth
        }
        //Bas
        if (top + menuHeight > viewportHeight) {
            top = top - menuHeight
        }
        // if(e.offsetY)

        // console.log(left, top);

        if(menu.classList.contains('show')){
            // Si le menu a déjà la classe "show" c'est qu'on a cliqué sur un autre message sans cliquer dans un vide
            // console.log('contient');
            
            menu.classList.remove('show');
            setTimeout(() => {
                menuContainer.style.top = `${top}px`
                menuContainer.style.left = `${left}px`
                // menu.style.display = "block"

                menu.classList.add('show')
                // On ne fait rien pendant 1s
            }, 100);
        }else{
            menuContainer.style.top = `${top}px`
            menuContainer.style.left = `${left}px`
            // menu.style.display = "block"

            menu.classList.add('show')
        }


       

        document.getElementById('messagesCont').classList.add('no-scroll')
    })
})

// Masquage du menu

document.addEventListener('click', (e) => {
    if (!menu.contains(e.target)) {
        menu.classList.remove('show')
        // menu.style.display = "none";
        document.getElementById('messagesCont').classList.remove('no-scroll')
    }
})
