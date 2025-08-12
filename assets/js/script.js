let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .nav');
let header = document.querySelector('.header');


menu.onclick = () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');

}


window.onscroll = () => {
    menu.classList.remove('fa-times');
    navbar.classList.remove('active');


    if (window.scrollY > 0) {
        header.classList.add('active');

    }
    else {
        header.classList.remove('active');

    }


}
function filterDoctors(status) {
    const cards = document.querySelectorAll('.doctor--card');

    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');

        if (status === "" || cardStatus.toLowerCase() === status.toLowerCase()) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
}