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
