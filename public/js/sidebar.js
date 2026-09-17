// Select DOM elements
const toggleButton = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');
const menuIcon = document.getElementById('menu-icon');
const closeIcon = document.getElementById('close-icon');

// Toggle sidebar open/close
function toggleSidebar() {
    sidebar.classList.toggle('close');
    
    // Toggle icon display
    if (sidebar.classList.contains('close')) {
        menuIcon.style.display = 'none';
        closeIcon.style.display = 'block';
    } else {
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    }
    
    // Close all submenus when toggling sidebar
    closeAllSubMenus();
}

// Toggle submenu open/close
function toggleSubMenu(button) {
    // If sidebar is closed, open it first
    if (sidebar.classList.contains('close')) {
        toggleSidebar();
    }
    
    // If this submenu is not already open, close all other submenus
    if (!button.nextElementSibling.classList.contains('show')) {
        closeAllSubMenus();
    }
    
    // Toggle this submenu
    button.nextElementSibling.classList.toggle('show');
    button.classList.toggle('rotate');
}

// Close all open submenus
function closeAllSubMenus() {
    const openSubmenus = sidebar.querySelectorAll('.sub-menu.show');
    openSubmenus.forEach(submenu => {
        submenu.classList.remove('show');
        if (submenu.previousElementSibling) {
            submenu.previousElementSibling.classList.remove('rotate');
        }
    });
}

// Initialize sidebar state on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial icon state
    if (sidebar.classList.contains('close')) {
        menuIcon.style.display = 'none';
        closeIcon.style.display = 'block';
    } else {
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    }
    
    // Highlight active menu item based on current route
    const currentPath = window.location.pathname;
    const menuItems = sidebar.querySelectorAll('a');
    
    menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.parentElement.classList.add('active');
            
            // If this is a submenu item, open its parent
            const parentMenu = item.closest('.sub-menu');
            if (parentMenu) {
                parentMenu.classList.add('show');
                if (parentMenu.previousElementSibling) {
                    parentMenu.previousElementSibling.classList.add('rotate');
                }
            }
        }
    });
});