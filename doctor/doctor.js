let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .nav');
let header = document.querySelector('.header');

menu.onclick = () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
};

window.onscroll = () => {
    menu.classList.remove('fa-times');
    navbar.classList.remove('active');

    if (window.scrollY > 0) {
        header.classList.add('active');
    } else {
        header.classList.remove('active');
    }
};
// Select menu icon, sidebar, and main content container
let menu = document.querySelector('.menu');           // Menu button (hamburger icon)
let sidebar = document.querySelector('.sidebar');     // Sidebar element
let maincontent = document.querySelector('.main--content'); // Main content area

// Toggle sidebar visibility when menu icon is clicked
menu.onclick = function () {
    // Add/remove 'active' class on sidebar and main content
    sidebar.classList.toggle('active');
    maincontent.classList.toggle('active');
};
