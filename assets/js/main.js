/**
 * Main JavaScript for Visit Roanoke Theme
 *
 * @package Visit_Roanoke
 */

(function() {
    'use strict';

    // Mobile hamburger toggle
    var toggle = document.getElementById('mobile-menu-toggle');
    var menu   = document.getElementById('mobile-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            var isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });
    }

    // Mobile accordion submenu toggles
    document.querySelectorAll('.mobile-submenu-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var li      = this.closest('.menu-item-has-children');
            var submenu = li.querySelector(':scope > .sub-menu');
            var isOpen  = submenu.classList.contains('is-open');

            // Close sibling submenus at the same level
            var siblings = li.parentElement.querySelectorAll(':scope > .menu-item-has-children > .sub-menu.is-open');
            siblings.forEach(function(openMenu) {
                if (openMenu !== submenu) {
                    openMenu.classList.remove('is-open');
                    openMenu.parentElement.querySelector('.mobile-submenu-toggle').classList.remove('is-open');
                }
            });

            // Toggle current
            submenu.classList.toggle('is-open', !isOpen);
            this.classList.toggle('is-open', !isOpen);
        });
    });

})();