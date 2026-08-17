<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        html { scroll-behavior: smooth; }
        .font-headline { font-family: 'ivyepic-variable', 'Arial Narrow', 'Impact', sans-serif; text-transform: uppercase; letter-spacing: 0.02em; }
        .font-body { font-family: 'Collier', 'Georgia', serif; line-height: 1.7; }

        /* Remove extra space at top */
        html, body { margin: 0; padding: 0; }
        #wpadminbar { position: fixed !important; } /* Prevent admin bar from pushing content down */

        /* Custom Logo sizing */
        .custom-logo-link img {
            height: auto;
            max-height: 100px;
            width: auto;
            display: block;
        }

        /* Submenus hidden by default */
        .sub-menu { display: none; }
        .sub-menu.is-open { display: block; }

        /* Bridge the gap so hover never breaks between parent and submenu */
        @media (min-width: 820px) {
            .menu-item-has-children > a {
                position: relative;
            }
            .menu-item-has-children > a::after {
                content: '';
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 0.75rem;
                transform: translateY(100%);
                z-index: 60;
            }
            .group:hover > .sub-menu,
            .group:focus-within > .sub-menu,
            .menu-item-has-children:hover > .sub-menu {
                display: block;
            }
        }

        /* Desktop: show on hover/focus */
        @media (min-width: 820px) {
            .group:hover > .sub-menu,
            .group:focus-within > .sub-menu {
                display: block;
            }
            .mobile-submenu-toggle { display: none !important; }
        }

        /* Mobile accordion arrow rotation */
        .mobile-submenu-toggle.is-open svg {
            transform: rotate(180deg);
        }

        /* Mobile menu styling */
        #mobile-menu .menu-item {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.5rem 0;
        }
        #mobile-menu .menu-item:last-child {
            border-bottom: none;
        }
        #mobile-menu .sub-menu {
            position: static;
            width: 100%;
            border: none;
            box-shadow: none;
            background: #f5f5f5;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            padding: 0.25rem 0;
        }
        #mobile-menu .sub-menu .menu-item {
            border-bottom: none;
            padding: 0.25rem 0;
        }
        #mobile-menu .sub-menu a {
            padding-left: 1.5rem;
        }

        /* Override Tailwind md: breakpoint to 820px for nav elements */
        @media (min-width: 820px) {
            .site-nav .hidden.md\:flex { display: flex !important; }
            #mobile-menu { display: none !important; }
            #mobile-menu-toggle { display: none !important; }
        }
        @media (max-width: 819px) {
            .site-nav .hidden.md\:flex { display: none !important; }
        }
    </style>
</head>
<body <?php body_class( 'bg-white text-navy font-body antialiased' ); ?>>

<?php wp_body_open(); ?>

<!-- ==================== NAVIGATION ==================== -->
<nav class="site-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-30">
            
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="flex items-center gap-3">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="custom-logo-link flex-shrink-0">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="w-10 h-10 bg-navy rounded-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <!-- <div class="flex flex-col">
                        <div class="font-headline text-navy font-bold text-xl tracking-tight leading-none">Visit Roanoke</div>
                        <div class="text-orange text-[10px] font-headline font-bold tracking-[0.15em] uppercase leading-none mt-1">The Unique Dining Capital <br />of Texas</div>
                    </div> -->
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 items-center">
                <?php
                wp_nav_menu( array(
                    'theme_location'  => 'primary',
                    'container'       => false,
                    'menu_class'      => 'flex space-x-8 items-center m-0 p-0 list-none',
                    'fallback_cb'     => 'visit_roanoke_fallback_menu',
                    'walker'          => new Visit_Roanoke_Walker_Nav_Menu(),
                    'depth'           => 3,
                ) );
                ?>
            </div>

            <!-- Mobile Toggle -->
            <div class="md:hidden">
                <button id="mobile-menu-toggle" class="text-navy hover:text-orange p-2 transition" aria-label="Toggle Menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-roanoke-gray-200 bg-white">
        <div class="px-4 py-2">
            <?php
            wp_nav_menu( array(
                'theme_location'  => 'primary',
                'container'       => false,
                'menu_class'      => 'm-0 p-0 list-none flex flex-col',
                'fallback_cb'     => 'visit_roanoke_fallback_menu',
                'walker'          => new Visit_Roanoke_Walker_Nav_Menu(),
                'depth'           => 3,
            ) );
            ?>
        </div>
    </div>
</nav>

<script>
    // Mobile hamburger toggle
    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        var menu = document.getElementById('mobile-menu');
        var isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
    });

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
</script>