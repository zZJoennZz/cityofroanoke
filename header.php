<?php
/**
 * The header for our theme
 *
 * @package Visit_Roanoke
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-white text-navy font-body antialiased' ); ?>>
<?php wp_body_open(); ?>

<!-- ==================== NAVIGATION ==================== -->
<nav class="site-nav relative z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-30">

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="flex items-center gap-3">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="custom-logo-link flex-shrink-0 max-w-[200px]">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="w-10 h-10 bg-navy rounded-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    <?php endif; ?>
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
                <button id="mobile-menu-toggle" class="text-navy hover:text-orange p-2 transition" aria-label="<?php esc_attr_e( 'Toggle Menu', 'visit-roanoke' ); ?>" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden absolute left-0 right-0 top-full bg-white border-t border-roanoke-gray-200 shadow-lg z-50 md:hidden w-100">
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