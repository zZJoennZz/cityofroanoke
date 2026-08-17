<?php
/**
 * Visit Roanoke Theme functions and definitions
 *
 * @package Visit_Roanoke
 * @since   1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme version — bump this when you deploy updates
 */
define( 'VISIT_ROANOKE_VERSION', '1.0.0' );

/**
 * Theme directory paths
 */
define( 'VISIT_ROANOKE_DIR', get_template_directory() );
define( 'VISIT_ROANOKE_URI', get_template_directory_uri() );

/* ============================================================
   1. THEME SETUP
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function visit_roanoke_setup() {

        /* ---- Translations ---- */
        load_theme_textdomain( 'visit-roanoke', VISIT_ROANOKE_DIR . '/languages' );

        /* ---- Feed links ---- */
        add_theme_support( 'automatic-feed-links' );

        /* ---- Title tag ---- */
        add_theme_support( 'title-tag' );

        /* ---- Post thumbnails ---- */
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 1200, 9999 );

        /* ---- HTML5 markup ---- */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style',
        ) );

        /* ---- Responsive embeds ---- */
        add_theme_support( 'responsive-embeds' );

        /* ---- Wide alignment ---- */
        add_theme_support( 'align-wide' );

        /* ---- Custom logo ---- */
        add_theme_support( 'custom-logo', array(
            'height'      => 100,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        /* ---- Custom header (optional) ---- */
        add_theme_support( 'custom-header', array(
            'default-image'      => '',
            'width'              => 1920,
            'height'             => 600,
            'flex-height'        => true,
            'flex-width'         => true,
            'uploads'            => true,
            'video'              => true,
        ) );

        /* ---- Navigation menus ---- */
        register_nav_menus( array(
            'primary'   => __( 'Primary Menu', 'visit-roanoke' ),
            'footer'    => __( 'Footer Menu', 'visit-roanoke' ),
            'social'    => __( 'Social Links', 'visit-roanoke' ),
        ) );

        /* ---- Image sizes ---- */
        add_image_size( 'roanoke-card', 600, 400, true );
        add_image_size( 'roanoke-hero', 1920, 900, true );
        add_image_size( 'roanoke-gallery', 800, 600, true );
    }
endif;
add_action( 'after_setup_theme', 'visit_roanoke_setup' );

/* ============================================================
   2. ENQUEUE STYLES & SCRIPTS
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_scripts' ) ) :
    /**
     * Enqueue stylesheets and JavaScript files.
     */
    function visit_roanoke_scripts() {

        wp_enqueue_style(
            'roanoke-fonts',
            'https://use.typekit.net/xon1mex.css',
            array(),
            null
        );

        /* ---- Main stylesheet ---- */
        wp_enqueue_style(
            'roanoke-global',
            VISIT_ROANOKE_URI . '/assets/css/global.css',
            array(),
            VISIT_ROANOKE_VERSION
        );

        /* ---- Main JavaScript ---- */
        wp_enqueue_script(
            'roanoke-main',
            VISIT_ROANOKE_URI . '/assets/js/main.js',
            array(),
            VISIT_ROANOKE_VERSION,
            true // Load in footer
        );

        /* ---- Frontpage stylesheet (homepage only) ---- */
        if ( is_front_page() ) {
            wp_enqueue_style(
                'roanoke-frontpage',
                VISIT_ROANOKE_URI . '/assets/css/frontpage.css',
                array( 'roanoke-global' ),
                VISIT_ROANOKE_VERSION
            );
        }

        if ( is_page() ) {
        wp_enqueue_style(
            'roanoke-page',
            VISIT_ROANOKE_URI . '/assets/css/page.css',
            array( 'roanoke-global' ),
            VISIT_ROANOKE_VERSION
        );
        }

        /* ---- Threaded comments ---- */
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'visit_roanoke_scripts' );

/* ============================================================
   3. ADMIN / BACKEND ENQUEUES
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_admin_scripts' ) ) :
    /**
     * Enqueue admin-only assets.
     */
    function visit_roanoke_admin_scripts( $hook ) {
        wp_enqueue_style(
            'roanoke-admin',
            VISIT_ROANOKE_URI . '/assets/css/admin.css',
            array(),
            VISIT_ROANOKE_VERSION
        );
    }
endif;
add_action( 'admin_enqueue_scripts', 'visit_roanoke_admin_scripts' );

/* ============================================================
   4. CUSTOM WALKER — NAV MENU WITH SUBMENU SUPPORT
   ============================================================ */

if ( ! class_exists( 'Visit_Roanoke_Walker_Nav_Menu' ) ) :
    /**
     * Custom Walker for the primary navigation menu.
     * Adds dropdown support, mobile accordion toggles, and proper ARIA attributes.
     */
    class Visit_Roanoke_Walker_Nav_Menu extends Walker_Nav_Menu {

        /**
         * Starts the element output.
         */
        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            if ( in_array( 'menu-item-has-children', $classes, true ) ) {
                $classes[] = 'group';
                $classes[] = 'relative';
                $classes[] = 'flex';
            }

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names . '>';

            $atts           = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target ) ? $item->target : '';
            $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
            $atts['href']   = ! empty( $item->url ) ? $item->url : '';
            $atts['class']  = 'nav-link';

            if ( in_array( 'menu-item-has-children', $classes, true ) ) {
                $atts['aria-haspopup']  = 'true';
                $atts['aria-expanded']  = 'false';
            }

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                    $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $title = apply_filters( 'the_title', $item->title, $item->ID );
            $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

            $item_output  = $args->before ?? '';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= ($args->link_before ?? '') . $title . ($args->link_after ?? '');
            $item_output .= '</a>';
            $item_output .= $args->after ?? '';

            /* ---- Mobile submenu toggle button ---- */
            if ( in_array( 'menu-item-has-children', $classes, true ) ) {
                $item_output .= '<button class="mobile-submenu-toggle ml-auto p-2 text-navy hover:text-orange transition" aria-label="' . esc_attr__( 'Toggle submenu', 'visit-roanoke' ) . '" aria-expanded="false">';
                $item_output .= '<svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                $item_output .= '</button>';
            }

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        /**
         * Starts the submenu list.
         */
        public function start_lvl( &$output, $depth = 0, $args = null ) {
            $indent  = str_repeat( "\t", $depth );
            $classes = array( 'sub-menu', 'absolute', 'left-0', 'top-full', 'min-w-[200px]', 'bg-white', 'rounded-lg', 'shadow-lg', 'py-2', 'z-50', 'list-none', 'm-0', 'p-0' );

            $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $output .= "\n$indent<ul$class_names>\n";
        }

        /**
         * Ends the submenu list.
         */
        public function end_lvl( &$output, $depth = 0, $args = null ) {
            $indent  = str_repeat( "\t", $depth );
            $output .= "$indent</ul>\n";
        }
    }
endif;

/* ============================================================
   5. FALLBACK MENU
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_fallback_menu' ) ) :
    /**
     * Fallback menu when no menu is assigned to the primary location.
     */
    function visit_roanoke_fallback_menu() {
        $home_url = esc_url( home_url( '/' ) );
        printf(
            '<ul class="flex space-x-8 items-center m-0 p-0 list-none">' .
            '<li><a href="%s" class="nav-link">%s</a></li>' .
            '</ul>',
            $home_url,
            esc_html__( 'Home', 'visit-roanoke' )
        );
    }
endif;

/* ============================================================
   6. WIDGET AREAS
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_widgets_init' ) ) :
    /**
     * Register widget areas.
     */
    function visit_roanoke_widgets_init() {
        register_sidebar( array(
            'name'          => __( 'Sidebar', 'visit-roanoke' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Add widgets here to appear in your sidebar.', 'visit-roanoke' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Footer Column 1', 'visit-roanoke' ),
            'id'            => 'footer-1',
            'description'   => __( 'First footer widget column.', 'visit-roanoke' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="font-headline font-black text-sm mb-4 text-white uppercase tracking-wider">',
            'after_title'   => '</h4>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Footer Column 2', 'visit-roanoke' ),
            'id'            => 'footer-2',
            'description'   => __( 'Second footer widget column.', 'visit-roanoke' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="font-headline font-black text-sm mb-4 text-white uppercase tracking-wider">',
            'after_title'   => '</h4>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Footer Column 3', 'visit-roanoke' ),
            'id'            => 'footer-3',
            'description'   => __( 'Third footer widget column.', 'visit-roanoke' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="font-headline font-black text-sm mb-4 text-white uppercase tracking-wider">',
            'after_title'   => '</h4>',
        ) );
    }
endif;
add_action( 'widgets_init', 'visit_roanoke_widgets_init' );

/* ============================================================
   7. EXCERPT LENGTH
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_excerpt_length' ) ) :
    /**
     * Custom excerpt length.
     */
    function visit_roanoke_excerpt_length( $length ) {
        return 25;
    }
endif;
add_filter( 'excerpt_length', 'visit_roanoke_excerpt_length', 999 );

if ( ! function_exists( 'visit_roanoke_excerpt_more' ) ) :
    /**
     * Custom "read more" link.
     */
    function visit_roanoke_excerpt_more( $more ) {
        return sprintf(
            ' &hellip; <a href="%s" class="text-roanoke-blue font-headline font-bold text-sm uppercase tracking-wider hover:text-burnt-orange transition">%s</a>',
            esc_url( get_permalink() ),
            esc_html__( 'Read More', 'visit-roanoke' )
        );
    }
endif;
add_filter( 'excerpt_more', 'visit_roanoke_excerpt_more' );

/* ============================================================
   8. BODY CLASS HELPERS
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_body_classes' ) ) :
    /**
     * Add custom classes to the body element.
     */
    function visit_roanoke_body_classes( $classes ) {
        // Add a class if the admin bar is showing
        if ( is_admin_bar_showing() ) {
            $classes[] = 'admin-bar';
        }

        // Add a class for the front page
        if ( is_front_page() ) {
            $classes[] = 'is-front-page';
        }

        return $classes;
    }
endif;
add_filter( 'body_class', 'visit_roanoke_body_classes' );

/* ============================================================
   9. CUSTOMIZER SETTINGS (Placeholder)
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_customize_register' ) ) :
    /**
     * Customizer settings.
     */
    function visit_roanoke_customize_register( $wp_customize ) {
        // Social links section
        $wp_customize->add_section( 'roanoke_social', array(
            'title'    => __( 'Social Links', 'visit-roanoke' ),
            'priority' => 160,
        ) );

        $socials = array( 'facebook', 'instagram', 'youtube', 'twitter' );
        foreach ( $socials as $social ) {
            $wp_customize->add_setting( 'roanoke_social_' . $social, array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ) );

            $wp_customize->add_control( 'roanoke_social_' . $social, array(
                'label'   => ucfirst( $social ) . ' URL',
                'section' => 'roanoke_social',
                'type'    => 'url',
            ) );
        }
    }
endif;
add_action( 'customize_register', 'visit_roanoke_customize_register' );

/* ============================================================
   10. HELPER FUNCTIONS
   ============================================================ */

if ( ! function_exists( 'visit_roanoke_get_social_link' ) ) :
    /**
     * Get a social link from the Customizer.
     */
    function visit_roanoke_get_social_link( $platform ) {
        return get_theme_mod( 'roanoke_social_' . $platform, '' );
    }
endif;

if ( ! function_exists( 'visit_roanoke_social_icon' ) ) :
    /**
     * Output a social icon link.
     */
    function visit_roanoke_social_icon( $platform, $url ) {
        if ( empty( $url ) ) {
            return;
        }

        $icons = array(
            'facebook'  => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
            'instagram' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>',
            'youtube'   => '<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
            'twitter'   => '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>',
        );

        $icon = isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';

        printf(
            '<a href="%s" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="%s">' .
            '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">%s</svg>' .
            '</a>',
            esc_url( $url ),
            esc_attr( sprintf( __( 'Visit us on %s', 'visit-roanoke' ), ucfirst( $platform ) ) ),
            $icon
        );
    }
endif;

function visit_roanoke_customize_gallery( $wp_customize ) {
    $wp_customize->add_section( 'gallery_section', array(
        'title'    => __( 'Gallery Images', 'visit-roanoke' ),
        'priority' => 160,
    ) );

    for ( $i = 1; $i <= 6; $i++ ) {
        // Image (stores attachment ID)
        $wp_customize->add_setting( "gallery_image_{$i}", array(
            'default'           => '',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, "gallery_image_{$i}", array(
            'label'     => sprintf( __( 'Gallery Image %d', 'visit-roanoke' ), $i ),
            'section'   => 'gallery_section',
            'mime_type' => 'image',
        ) ) );

        // Alt text
        $wp_customize->add_setting( "gallery_alt_{$i}", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "gallery_alt_{$i}", array(
            'label'   => sprintf( __( 'Image %d Alt Text', 'visit-roanoke' ), $i ),
            'section' => 'gallery_section',
            'type'    => 'text',
        ) );

        // Caption
        $wp_customize->add_setting( "gallery_caption_{$i}", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "gallery_caption_{$i}", array(
            'label'   => sprintf( __( 'Image %d Caption', 'visit-roanoke' ), $i ),
            'section' => 'gallery_section',
            'type'    => 'text',
        ) );

        // Layout class
        $wp_customize->add_setting( "gallery_layout_{$i}", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "gallery_layout_{$i}", array(
            'label'   => sprintf( __( 'Image %d Layout', 'visit-roanoke' ), $i ),
            'section' => 'gallery_section',
            'type'    => 'select',
            'choices' => array(
                ''      => __( 'Default', 'visit-roanoke' ),
                'tall'  => __( 'Tall', 'visit-roanoke' ),
                'wide'  => __( 'Wide', 'visit-roanoke' ),
            ),
        ) );
    }
}
add_action( 'customize_register', 'visit_roanoke_customize_gallery' );

class Roanoke_Event_Meta_Box {

    private $prefix = '_roanoke_event_';
    private $event_template = 'page-event.php';

    public function __construct() {
        // 2 args so we receive the $post object
        add_action('add_meta_boxes', [$this, 'add_meta_box'], 10, 2);
        add_action('save_post', [$this, 'save_meta_box'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function enqueue_admin_assets($hook) {
        if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
        $screen = get_current_screen();
        if ($screen && $screen->id === 'page') {
            wp_enqueue_media();
        }
    }

    public function add_meta_box( $post_type, $post ) {
        if ( $post_type !== 'page' ) {
            return;
        }

        $template = visit_roanoke_get_effective_template( $post->ID );
        if ( $template !== $this->event_template ) {
            return;
        }

        add_meta_box(
            'roanoke_event_meta',
            'Event Page Details',
            [$this, 'render_meta_box'],
            'page',
            'normal',
            'high'
        );
    }


    private function get($post_id, $key, $default = '') {
        $val = get_post_meta($post_id, $this->prefix . $key, true);
        return ($val !== '' && $val !== false && $val !== null) ? $val : $default;
    }

    private function get_arr($post_id, $key) {
        $val = get_post_meta($post_id, $this->prefix . $key, true);
        return is_array($val) ? $val : [];
    }

    public function render_meta_box($post) {
        wp_nonce_field('roanoke_event_save', 'roanoke_event_nonce');

        $scheme = $this->get($post->ID, 'color_scheme', 'custom');
        $schemes = [
            'fireworks' => 'All-American Fireworks (Red/Navy/Gold)',
            'celebrate' => 'Celebrate Roanoke (Amber/Burnt/Gold)',
            'taste'     => 'Taste & Tunes (Green/Espresso/Orange)',
            'holiday'   => 'Holiday in the Plaza (Green/Cranberry/Gold)',
            'hometown'  => 'Hometown Holiday (Blue/Midnight/Silver)',
            'custom'    => 'Custom Colors (set below)',
        ];

        $logo_id      = $this->get($post->ID, 'logo_id');
        $hero_img_id  = $this->get($post->ID, 'hero_image_id');
        $map_img_id   = $this->get($post->ID, 'map_image_id');
        $gallery_ids  = $this->get($post->ID, 'gallery_ids');
        $schedule     = $this->get_arr($post->ID, 'schedule');
        $bring        = $this->get_arr($post->ID, 'bring');
        $leave        = $this->get_arr($post->ID, 'leave');
        $faq          = $this->get_arr($post->ID, 'faq');
        $sponsors     = $this->get_arr($post->ID, 'sponsors');

        if (empty($schedule)) $schedule = [['time' => '', 'activity' => '', 'description' => '']];
        if (empty($bring))    $bring    = [['item' => '']];
        if (empty($leave))    $leave    = [['item' => '']];
        if (empty($faq))      $faq      = [['question' => '', 'answer' => '']];
        if (empty($sponsors)) $sponsors = [['image_id' => '', 'name' => '', 'link' => '']];
        ?>

        <style>
        .roanoke-meta-wrap { padding: 10px 0; }
        .roanoke-meta-section { margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; }
        .roanoke-meta-section h3 { margin: 0 0 12px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: #1d2327; }
        .roanoke-media-field { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .roanoke-media-preview { max-width: 200px; max-height: 150px; overflow: hidden; border: 1px solid #c3c4c7; background: #f0f0f1; }
        .roanoke-media-preview img { max-width: 100%; height: auto; display: block; }
        .roanoke-repeater-item { display: flex; align-items: center; gap: 8px; padding: 8px; background: #f6f7f7; margin-bottom: 6px; border: 1px solid #c3c4c7; }
        .roanoke-repeater-item--faq { flex-direction: column; align-items: stretch; }
        .roanoke-repeater-item--faq input,
        .roanoke-repeater-item--faq textarea { width: 100%; box-sizing: border-box; }
        .roanoke-repeater-item--faq .roanoke-repeater-remove { align-self: flex-end; }
        .roanoke-repeater-item--sponsor { flex-direction: column; align-items: stretch; gap: 8px; }
        .roanoke-repeater-item--sponsor .roanoke-media-field { justify-content: flex-start; flex-wrap: wrap; }
        .roanoke-repeater-item--sponsor input { width: 100%; box-sizing: border-box; }
        .roanoke-repeater-item--sponsor .roanoke-repeater-remove { align-self: flex-end; }
        .roanoke-repeater-remove { background: #d63638 !important; color: #fff !important; border-color: #d63638 !important; min-width: 32px; cursor: pointer; }
        .roanoke-gallery-preview { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
        .roanoke-gallery-preview img { width: 80px; height: 80px; object-fit: cover; border: 2px solid #c3c4c7; }
        .roanoke-repeater-add { margin-top: 6px !important; }
        </style>

        <div class="roanoke-meta-wrap">

            <!-- BRANDING -->
            <div class="roanoke-meta-section">
                <h3>Event Branding</h3>
                <table class="form-table">
                    <tr>
                        <th><label for="event_color_scheme">Color Preset</label></th>
                        <td>
                            <select name="event_color_scheme" id="event_color_scheme" style="width:100%;max-width:400px;">
                                <?php foreach ($schemes as $k => $label) : ?>
                                    <option value="<?php echo esc_attr($k); ?>" <?php selected($scheme, $k); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">Choose a preset or select Custom to set your own colors.</p>
                        </td>
                    </tr>
                </table>

                <div id="custom-colors" style="display:<?php echo $scheme === 'custom' ? 'block' : 'none'; ?>;">
                    <table class="form-table">
                        <tr><th>Primary</th><td><input type="color" name="event_color_primary"   value="<?php echo esc_attr($this->get($post->ID, 'color_primary',   '#D97706')); ?>" style="width:60px;height:36px;"></td></tr>
                        <tr><th>Secondary</th><td><input type="color" name="event_color_secondary" value="<?php echo esc_attr($this->get($post->ID, 'color_secondary', '#7C2D12')); ?>" style="width:60px;height:36px;"></td></tr>
                        <tr><th>Accent</th><td><input type="color" name="event_color_accent"    value="<?php echo esc_attr($this->get($post->ID, 'color_accent',    '#FCD34D')); ?>" style="width:60px;height:36px;"></td></tr>
                        <tr><th>Dark</th><td><input type="color" name="event_color_dark"      value="<?php echo esc_attr($this->get($post->ID, 'color_dark',      '#1F2937')); ?>" style="width:60px;height:36px;"></td></tr>
                        <tr><th>Light</th><td><input type="color" name="event_color_light"     value="<?php echo esc_attr($this->get($post->ID, 'color_light',     '#FEF3C7')); ?>" style="width:60px;height:36px;"></td></tr>
                    </table>
                </div>

                <table class="form-table">
                    <tr>
                        <th>Event Logo</th>
                        <td>
                            <div class="roanoke-media-field">
                                <input type="hidden" name="event_logo_id" id="event_logo_id" value="<?php echo esc_attr($logo_id); ?>">
                                <div class="roanoke-media-preview" id="logo_preview">
                                    <?php if ($logo_id) echo wp_get_attachment_image(intval($logo_id), 'medium'); ?>
                                </div>
                                <button type="button" class="button roanoke-media-upload" data-target="event_logo_id" data-preview="logo_preview">Select Logo</button>
                                <button type="button" class="button roanoke-media-remove" data-target="event_logo_id" data-preview="logo_preview" <?php echo $logo_id ? '' : 'style="display:none;"'; ?>>Remove</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Tagline</th>
                        <td><input type="text" name="event_tagline" value="<?php echo esc_attr($this->get($post->ID, 'tagline', 'Mark Your Calendar!')); ?>" class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <!-- HERO MEDIA -->
            <div class="roanoke-meta-section">
                <h3>Hero Media</h3>
                <table class="form-table">
                    <tr>
                        <th>Hero Image</th>
                        <td>
                            <div class="roanoke-media-field">
                                <input type="hidden" name="event_hero_image_id" id="event_hero_image_id" value="<?php echo esc_attr($hero_img_id); ?>">
                                <div class="roanoke-media-preview" id="hero_preview">
                                    <?php if ($hero_img_id) echo wp_get_attachment_image(intval($hero_img_id), 'medium'); ?>
                                </div>
                                <button type="button" class="button roanoke-media-upload" data-target="event_hero_image_id" data-preview="hero_preview">Select Image</button>
                                <button type="button" class="button roanoke-media-remove" data-target="event_hero_image_id" data-preview="hero_preview" <?php echo $hero_img_id ? '' : 'style="display:none;"'; ?>>Remove</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Hero Video URL</th>
                        <td><input type="url" name="event_hero_video" value="<?php echo esc_url($this->get($post->ID, 'hero_video')); ?>" class="regular-text" placeholder="https://..."></td>
                    </tr>
                    <tr>
                        <th>Short Description</th>
                        <td><textarea name="event_short_desc" rows="2" class="large-text"><?php echo esc_textarea($this->get($post->ID, 'short_desc')); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <!-- EVENT DETAILS -->
            <div class="roanoke-meta-section">
                <h3>Event Details</h3>
                <table class="form-table">
                    <tr><th>Event Date</th><td><input type="date" name="event_date" value="<?php echo esc_attr($this->get($post->ID, 'date')); ?>"></td></tr>
                    <tr><th>Start Time</th><td><input type="text" name="event_time" value="<?php echo esc_attr($this->get($post->ID, 'time', '5:00 PM')); ?>" class="regular-text"></td></tr>
                    <tr><th>End Time</th><td><input type="text" name="event_end_time" value="<?php echo esc_attr($this->get($post->ID, 'end_time')); ?>" class="regular-text"></td></tr>
                    <tr><th>Location</th><td><input type="text" name="event_location" value="<?php echo esc_attr($this->get($post->ID, 'location', 'Downtown Roanoke')); ?>" class="regular-text"></td></tr>
                    <tr><th>Address</th><td><input type="text" name="event_address" value="<?php echo esc_attr($this->get($post->ID, 'address')); ?>" class="regular-text"></td></tr>
                    <tr><th>Admission</th><td><input type="text" name="event_cost" value="<?php echo esc_attr($this->get($post->ID, 'cost', 'FREE')); ?>" class="regular-text"></td></tr>
                    <tr><th>Countdown Target</th><td><input type="datetime-local" name="event_countdown" value="<?php echo esc_attr($this->get($post->ID, 'countdown')); ?>"></td></tr>
                </table>
            </div>

            <!-- DESCRIPTION -->
            <div class="roanoke-meta-section">
                <h3>Full Description</h3>
                <?php
                wp_editor(
                    $this->get($post->ID, 'long_desc'),
                    'event_long_desc_editor',
                    [
                        'textarea_name' => 'event_long_desc',
                        'textarea_rows' => 10,
                        'teeny'         => true,
                        'quicktags'     => true,
                    ]
                );
                ?>
            </div>

            <!-- SCHEDULE -->
            <div class="roanoke-meta-section">
                <h3>Event Schedule</h3>
                <div class="roanoke-repeater" data-field="schedule">
                    <div class="roanoke-repeater-items">
                        <?php foreach ($schedule as $i => $item) : ?>
                        <div class="roanoke-repeater-item">
                            <input type="text" name="event_schedule[<?php echo $i; ?>][time]" value="<?php echo esc_attr($item['time'] ?? ''); ?>" placeholder="5:00 PM" class="small-text">
                            <input type="text" name="event_schedule[<?php echo $i; ?>][activity]" value="<?php echo esc_attr($item['activity'] ?? ''); ?>" placeholder="Activity" class="regular-text">
                            <input type="text" name="event_schedule[<?php echo $i; ?>][description]" value="<?php echo esc_attr($item['description'] ?? ''); ?>" placeholder="Description" class="regular-text">
                            <button type="button" class="button roanoke-repeater-remove">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button roanoke-repeater-add">+ Add Schedule Item</button>
                </div>
            </div>

            <!-- BRING -->
            <div class="roanoke-meta-section">
                <h3>What to Bring</h3>
                <div class="roanoke-repeater" data-field="bring">
                    <div class="roanoke-repeater-items">
                        <?php foreach ($bring as $i => $item) : ?>
                        <div class="roanoke-repeater-item">
                            <input type="text" name="event_bring[<?php echo $i; ?>][item]" value="<?php echo esc_attr($item['item'] ?? ''); ?>" placeholder="Item" class="regular-text">
                            <button type="button" class="button roanoke-repeater-remove">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button roanoke-repeater-add">+ Add Item</button>
                </div>
            </div>

            <!-- LEAVE -->
            <div class="roanoke-meta-section">
                <h3>What to Leave at Home</h3>
                <div class="roanoke-repeater" data-field="leave">
                    <div class="roanoke-repeater-items">
                        <?php foreach ($leave as $i => $item) : ?>
                        <div class="roanoke-repeater-item">
                            <input type="text" name="event_leave[<?php echo $i; ?>][item]" value="<?php echo esc_attr($item['item'] ?? ''); ?>" placeholder="Item" class="regular-text">
                            <button type="button" class="button roanoke-repeater-remove">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button roanoke-repeater-add">+ Add Item</button>
                </div>
            </div>

            <!-- PARKING -->
            <div class="roanoke-meta-section">
                <h3>Parking &amp; Shuttle Info</h3>
                <?php
                wp_editor(
                    $this->get($post->ID, 'parking'),
                    'event_parking_editor',
                    [
                        'textarea_name' => 'event_parking',
                        'textarea_rows' => 6,
                        'teeny'         => true,
                    ]
                );
                ?>
            </div>

            <!-- MAP -->
            <div class="roanoke-meta-section">
                <h3>Map</h3>
                <table class="form-table">
                    <tr>
                        <th>Map Embed Code</th>
                        <td><textarea name="event_map_embed" rows="4" class="large-text" placeholder="<iframe src=...>"><?php echo esc_textarea($this->get($post->ID, 'map_embed')); ?></textarea></td>
                    </tr>
                    <tr>
                        <th>Map Image</th>
                        <td>
                            <div class="roanoke-media-field">
                                <input type="hidden" name="event_map_image_id" id="event_map_image_id" value="<?php echo esc_attr($map_img_id); ?>">
                                <div class="roanoke-media-preview" id="map_preview">
                                    <?php if ($map_img_id) echo wp_get_attachment_image(intval($map_img_id), 'medium'); ?>
                                </div>
                                <button type="button" class="button roanoke-media-upload" data-target="event_map_image_id" data-preview="map_preview">Select Image</button>
                                <button type="button" class="button roanoke-media-remove" data-target="event_map_image_id" data-preview="map_preview" <?php echo $map_img_id ? '' : 'style="display:none;"'; ?>>Remove</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- PARTICIPATE -->
            <div class="roanoke-meta-section">
                <h3>Participate</h3>
                <table class="form-table">
                    <tr><th>Vendor Description</th><td><textarea name="event_vendors_text" rows="2" class="large-text"><?php echo esc_textarea($this->get($post->ID, 'vendors_text')); ?></textarea></td></tr>
                    <tr><th>Vendor Link</th><td><input type="url" name="event_vendor_link" value="<?php echo esc_url($this->get($post->ID, 'vendor_link')); ?>" class="regular-text"></td></tr>
                    
                    <!-- SPONSORS REPEATER -->
                    <tr>
                        <th>Sponsors</th>
                        <td>
                            <div class="roanoke-repeater" data-field="sponsors">
                                <div class="roanoke-repeater-items">
                                    <?php foreach ($sponsors as $i => $item) : ?>
                                    <div class="roanoke-repeater-item roanoke-repeater-item--sponsor">
                                        <div class="roanoke-media-field">
                                            <input type="hidden" name="event_sponsors[<?php echo $i; ?>][image_id]" value="<?php echo esc_attr($item['image_id'] ?? ''); ?>">
                                            <div class="roanoke-media-preview" style="max-width:100px;max-height:100px;">
                                                <?php if (!empty($item['image_id'])) echo wp_get_attachment_image(intval($item['image_id']), 'thumbnail'); ?>
                                            </div>
                                            <button type="button" class="button roanoke-repeater-media-upload">Select Logo</button>
                                            <button type="button" class="button roanoke-repeater-media-remove" <?php echo empty($item['image_id']) ? 'style="display:none;"' : ''; ?>>Remove</button>
                                        </div>
                                        <input type="text" name="event_sponsors[<?php echo $i; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Sponsor Name" class="regular-text">
                                        <input type="url" name="event_sponsors[<?php echo $i; ?>][link]" value="<?php echo esc_attr($item['link'] ?? ''); ?>" placeholder="https://..." class="regular-text">
                                        <button type="button" class="button roanoke-repeater-remove">&times;</button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="button roanoke-repeater-add">+ Add Sponsor</button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr><th>Volunteer Link</th><td><input type="url" name="event_volunteer_link" value="<?php echo esc_url($this->get($post->ID, 'volunteer_link')); ?>" class="regular-text"></td></tr>
                </table>
            </div>

            <!-- GALLERY -->
            <div class="roanoke-meta-section">
                <h3>Photo Gallery</h3>
                <div class="roanoke-gallery-field">
                    <input type="hidden" name="event_gallery_ids" id="event_gallery_ids" value="<?php echo esc_attr($gallery_ids); ?>">
                    <div class="roanoke-gallery-preview" id="gallery_preview">
                        <?php
                        if ($gallery_ids) {
                            foreach (explode(',', $gallery_ids) as $gid) {
                                $gid = intval(trim($gid));
                                if ($gid) echo wp_get_attachment_image($gid, 'thumbnail');
                            }
                        }
                        ?>
                    </div>
                    <button type="button" class="button" id="gallery_select">Select Images</button>
                    <button type="button" class="button" id="gallery_clear" <?php echo $gallery_ids ? '' : 'style="display:none;"'; ?>>Clear All</button>
                </div>
            </div>

            <!-- FAQ -->
            <div class="roanoke-meta-section">
                <h3>FAQ</h3>
                <div class="roanoke-repeater" data-field="faq">
                    <div class="roanoke-repeater-items">
                        <?php foreach ($faq as $i => $item) : ?>
                        <div class="roanoke-repeater-item roanoke-repeater-item--faq">
                            <input type="text" name="event_faq[<?php echo $i; ?>][question]" value="<?php echo esc_attr($item['question'] ?? ''); ?>" placeholder="Question" class="regular-text" style="margin-bottom:6px;">
                            <textarea name="event_faq[<?php echo $i; ?>][answer]" rows="2" class="large-text" placeholder="Answer"><?php echo esc_textarea($item['answer'] ?? ''); ?></textarea>
                            <button type="button" class="button roanoke-repeater-remove">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button roanoke-repeater-add">+ Add FAQ</button>
                </div>
            </div>

            <!-- EXTRAS -->
            <div class="roanoke-meta-section">
                <h3>Extras</h3>
                <table class="form-table">
                    <tr><th>Awards (one per line)</th><td><textarea name="event_awards" rows="3" class="large-text" placeholder="Best Festival 2025"><?php echo esc_textarea($this->get($post->ID, 'awards')); ?></textarea></td></tr>
                    <tr><th>Merch Text</th><td><textarea name="event_merch_text" rows="2" class="large-text"><?php echo esc_textarea($this->get($post->ID, 'merch_text')); ?></textarea></td></tr>
                    <tr><th>Merch Link</th><td><input type="url" name="event_merch_link" value="<?php echo esc_url($this->get($post->ID, 'merch_link')); ?>" class="regular-text"></td></tr>
                </table>
            </div>

            <!-- CTA -->
            <div class="roanoke-meta-section">
                <h3>Call to Action</h3>
                <table class="form-table">
                    <tr><th>Primary Button Text</th><td><input type="text" name="event_cta_text" value="<?php echo esc_attr($this->get($post->ID, 'cta_text', 'Get Tickets')); ?>" class="regular-text"></td></tr>
                    <tr><th>Primary Button Link</th><td><input type="url" name="event_cta_link" value="<?php echo esc_url($this->get($post->ID, 'cta_link')); ?>" class="regular-text"></td></tr>
                    <tr><th>Secondary Button Text</th><td><input type="text" name="event_cta_secondary_text" value="<?php echo esc_attr($this->get($post->ID, 'cta_secondary_text', 'Learn More')); ?>" class="regular-text"></td></tr>
                    <tr><th>Secondary Button Link</th><td><input type="url" name="event_cta_secondary_link" value="<?php echo esc_url($this->get($post->ID, 'cta_secondary_link')); ?>" class="regular-text"></td></tr>
                </table>
            </div>

        </div>

        <script>
        (function($) {
            'use strict';

            // Toggle custom colors
            document.getElementById('event_color_scheme').addEventListener('change', function() {
                document.getElementById('custom-colors').style.display = (this.value === 'custom') ? 'block' : 'none';
            });

            // Single image upload (logo, hero, map)
            $(document).on('click', '.roanoke-media-upload', function(e) {
                e.preventDefault();
                var button = $(this);
                var target = $('#' + button.data('target'));
                var preview = $('#' + button.data('preview'));

                var frame = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use Image' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    target.val(attachment.id);
                    preview.html('<img src="' + attachment.url + '" style="max-width:200px;">');
                    button.siblings('.roanoke-media-remove').show();
                });

                frame.open();
            });

            // Single image remove
            $(document).on('click', '.roanoke-media-remove', function(e) {
                e.preventDefault();
                var button = $(this);
                $('#' + button.data('target')).val('');
                $('#' + button.data('preview')).html('');
                button.hide();
            });

            // Gallery multi-select
            $('#gallery_select').on('click', function(e) {
                e.preventDefault();

                var frame = wp.media({
                    title: 'Select Gallery Images',
                    button: { text: 'Add to Gallery' },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').map(function(a) { return a.toJSON(); });
                    var ids = attachments.map(function(a) { return a.id; }).join(',');
                    var existing = $('#event_gallery_ids').val();
                    var allIds = existing ? existing + ',' + ids : ids;
                    $('#event_gallery_ids').val(allIds);

                    var html = attachments.map(function(a) { return '<img src="' + a.url + '">'; }).join('');
                    $('#gallery_preview').append(html);
                    $('#gallery_clear').show();
                });

                frame.open();
            });

            // Gallery clear
            $('#gallery_clear').on('click', function() {
                $('#event_gallery_ids').val('');
                $('#gallery_preview').html('');
                $(this).hide();
            });

            // Repeater add (schedule, bring, leave, faq, sponsors)
            $(document).on('click', '.roanoke-repeater-add', function() {
                var container = $(this).closest('.roanoke-repeater');
                var field = container.data('field');
                var items = container.find('.roanoke-repeater-items');
                var index = items.children().length;
                var html = '';

                if (field === 'faq') {
                    html = '<div class="roanoke-repeater-item roanoke-repeater-item--faq">' +
                           '<input type="text" name="event_faq[' + index + '][question]" placeholder="Question" class="regular-text" style="margin-bottom:6px;">' +
                           '<textarea name="event_faq[' + index + '][answer]" rows="2" class="large-text" placeholder="Answer"></textarea>' +
                           '<button type="button" class="button roanoke-repeater-remove">&times;</button></div>';
                } else if (field === 'schedule') {
                    html = '<div class="roanoke-repeater-item">' +
                           '<input type="text" name="event_schedule[' + index + '][time]" placeholder="5:00 PM" class="small-text">' +
                           '<input type="text" name="event_schedule[' + index + '][activity]" placeholder="Activity" class="regular-text">' +
                           '<input type="text" name="event_schedule[' + index + '][description]" placeholder="Description" class="regular-text">' +
                           '<button type="button" class="button roanoke-repeater-remove">&times;</button></div>';
                } else if (field === 'sponsors') {
                    html = '<div class="roanoke-repeater-item roanoke-repeater-item--sponsor">' +
                           '<div class="roanoke-media-field">' +
                           '<input type="hidden" name="event_sponsors[' + index + '][image_id]">' +
                           '<div class="roanoke-media-preview" style="max-width:100px;max-height:100px;"></div>' +
                           '<button type="button" class="button roanoke-repeater-media-upload">Select Logo</button>' +
                           '<button type="button" class="button roanoke-repeater-media-remove" style="display:none;">Remove</button>' +
                           '</div>' +
                           '<input type="text" name="event_sponsors[' + index + '][name]" placeholder="Sponsor Name" class="regular-text">' +
                           '<input type="url" name="event_sponsors[' + index + '][link]" placeholder="https://..." class="regular-text">' +
                           '<button type="button" class="button roanoke-repeater-remove">&times;</button></div>';
                } else {
                    html = '<div class="roanoke-repeater-item">' +
                           '<input type="text" name="event_' + field + '[' + index + '][item]" placeholder="Item" class="regular-text">' +
                           '<button type="button" class="button roanoke-repeater-remove">&times;</button></div>';
                }
                items.append(html);
            });

            // Repeater remove
            $(document).on('click', '.roanoke-repeater-remove', function() {
                $(this).closest('.roanoke-repeater-item').remove();
            });

            // Repeater media upload (sponsor logos inside repeaters)
            $(document).on('click', '.roanoke-repeater-media-upload', function(e) {
                e.preventDefault();
                var button = $(this);
                var item = button.closest('.roanoke-repeater-item');
                var input = item.find('input[type="hidden"]');
                var preview = item.find('.roanoke-media-preview');

                var frame = wp.media({
                    title: 'Select Sponsor Logo',
                    button: { text: 'Use Image' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    input.val(attachment.id);
                    preview.html('<img src="' + attachment.url + '" style="max-width:100px;max-height:100px;">');
                    button.siblings('.roanoke-repeater-media-remove').show();
                });

                frame.open();
            });

            // Repeater media remove
            $(document).on('click', '.roanoke-repeater-media-remove', function(e) {
                e.preventDefault();
                var button = $(this);
                var item = button.closest('.roanoke-repeater-item');
                item.find('input[type="hidden"]').val('');
                item.find('.roanoke-media-preview').html('');
                button.hide();
            });

        })(jQuery);
        </script>
        <?php
    }

    public function save_meta_box( $post_id, $post ) {
        if ( ! isset( $_POST['roanoke_event_nonce'] ) || ! wp_verify_nonce( $_POST['roanoke_event_nonce'], 'roanoke_event_save' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        if ( $post->post_type !== 'page' ) {
            return;
        }

        // Defensive: don't save event data if the template has changed away from the event template
        $template = visit_roanoke_get_effective_template( $post_id );
        if ( $template !== 'page-event.php' ) {
            return;
        }

        // Plain text fields (no HTML allowed)
        $text_fields = [
            'color_scheme', 'color_primary', 'color_secondary', 'color_accent', 'color_dark', 'color_light',
            'logo_id', 'tagline',
            'hero_image_id', 'hero_video', 'short_desc',
            'date', 'time', 'end_time', 'location', 'address', 'cost', 'countdown',
            'map_image_id',
            'vendors_text', 'vendor_link', 'volunteer_link',
            'gallery_ids', 'awards', 'merch_text', 'merch_link',
            'cta_text', 'cta_link', 'cta_secondary_text', 'cta_secondary_link',
        ];

        foreach ( $text_fields as $field ) {
            $key = $this->prefix . $field;
            if ( isset( $_POST['event_' . $field] ) ) {
                update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST['event_' . $field] ) ) );
            }
        }

        // HTML fields (allow safe HTML including iframes)
        $html_fields = ['map_embed', 'long_desc', 'parking'];
        foreach ( $html_fields as $field ) {
            $key = $this->prefix . $field;
            if ( isset( $_POST['event_' . $field] ) ) {
                // wp_kses_post allows safe HTML but STRIPS iframes by default
                // Use wp_kses with custom allowed tags to preserve iframes
                $allowed_html = wp_kses_allowed_html( 'post' );
                
                // Add iframe and its attributes to allowed tags
                $allowed_html['iframe'] = [
                    'src'             => true,
                    'width'           => true,
                    'height'          => true,
                    'frameborder'     => true,
                    'allowfullscreen' => true,
                    'allow'           => true,
                    'style'           => true,
                    'class'           => true,
                    'id'              => true,
                    'name'            => true,
                    'sandbox'         => true,
                    'scrolling'       => true,
                    'title'           => true,
                ];
                
                // Also allow these tags just in case
                $allowed_html['script'] = [
                    'src' => true,
                    'type' => true,
                ];
                
                update_post_meta( $post_id, $key, wp_kses( wp_unslash( $_POST['event_' . $field] ), $allowed_html ) );
            }
        }

        // Repeater fields
        $arrays = ['schedule', 'bring', 'leave', 'faq', 'sponsors'];
        foreach ( $arrays as $arr ) {
            $key = $this->prefix . $arr;
            if ( isset( $_POST['event_' . $arr] ) && is_array( $_POST['event_' . $arr] ) ) {
                $clean = [];
                foreach ( wp_unslash( $_POST['event_' . $arr] ) as $item ) {
                    if ( is_array( $item ) ) {
                        $clean[] = array_map( 'sanitize_text_field', $item );
                    }
                }
                update_post_meta( $post_id, $key, $clean );
            } else {
                delete_post_meta( $post_id, $key );
            }
        }
    }
}

new Roanoke_Event_Meta_Box();

/**
 * ----------------------------------------------------
 * 1. REGISTER & CONDITIONALLY ENQUEUE INTERIOR PAGE CSS
 * ----------------------------------------------------
 * Only loads on pages using the "Interior Page — Full Featured" template.
 */
function visit_roanoke_enqueue_interior_styles() {
    // Only enqueue on singular pages (pages, not posts)
    if ( ! is_singular( 'page' ) ) {
        return;
    }

    // Get the current page template
    $template = get_page_template_slug( get_the_ID() );

    // Only load for our interior template
    if ( $template !== 'page.php' ) {
        return;
    }

    // Enqueue the interior page stylesheet
    wp_enqueue_style(
        'visit-roanoke-interior',
        get_template_directory_uri() . '/assets/css/page.css',
        array(),
        VISIT_ROANOKE_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'visit_roanoke_enqueue_interior_styles', 20 );


/**
 * ----------------------------------------------------
 * 2. ADD CUSTOM META BOX FOR INTERIOR PAGE SETTINGS
 * ----------------------------------------------------
 */
add_action( 'add_meta_boxes', function( $post_type, $post ) {
    if ( $post_type !== 'page' ) {
        return;
    }

    $template = visit_roanoke_get_effective_template( $post->ID );
    if ( $template !== 'page.php' ) {
        return;
    }

    add_meta_box(
        'interior_page_settings',
        'Interior Page Settings',
        'render_interior_page_meta_box',
        'page',
        'normal',
        'high'
    );
}, 10, 2 );

function render_interior_page_meta_box( $post ) {
    wp_nonce_field( 'interior_page_nonce', 'interior_page_nonce' );

    // Hero settings
    $hero_tagline = get_post_meta( $post->ID, 'page_hero_tagline', true );

    // Filter bar
    $show_filters = get_post_meta( $post->ID, 'page_show_filters', true );
    $filters      = get_post_meta( $post->ID, 'page_filters', true );

    // Intro block
    $intro_text = get_post_meta( $post->ID, 'page_intro_text', true );
    $stat_1     = get_post_meta( $post->ID, 'page_stat_1', true );
    $stat_2     = get_post_meta( $post->ID, 'page_stat_2', true );
    $stat_3     = get_post_meta( $post->ID, 'page_stat_3', true );

    // Callout
    $callout = get_post_meta( $post->ID, 'page_callout', true );

    // FAQ
    $faq = get_post_meta( $post->ID, 'page_faq', true );

    // Marquee
    $marquee_text = get_post_meta( $post->ID, 'page_marquee_text', true );

    // Bottom CTA
    $cta_heading   = get_post_meta( $post->ID, 'page_cta_heading', true );
    $cta_text      = get_post_meta( $post->ID, 'page_cta_text', true );
    $cta_btn_1     = get_post_meta( $post->ID, 'page_cta_btn_1_text', true );
    $cta_btn_1_url = get_post_meta( $post->ID, 'page_cta_btn_1_url', true );
    $cta_btn_2     = get_post_meta( $post->ID, 'page_cta_btn_2_text', true );
    $cta_btn_2_url = get_post_meta( $post->ID, 'page_cta_btn_2_url', true );

    ?>
    <style>
        .vr-meta-section { background:#f0f6fc; padding:15px; border-radius:4px; margin-bottom:16px; border-left:4px solid #2271b1; }
        .vr-meta-section h4 { margin:0 0 12px 0; font-size:14px; color:#1d2327; }
        .vr-meta-field { margin-bottom:14px; }
        .vr-meta-field label { display:block; font-weight:600; margin-bottom:4px; font-size:13px; }
        .vr-meta-field input[type="text"],
        .vr-meta-field input[type="url"],
        .vr-meta-field textarea { width:100%; max-width:600px; padding:6px 8px; }
        .vr-meta-field textarea { min-height:80px; font-family:monospace; font-size:12px; }
        .vr-meta-field .description { color:#666; font-size:12px; margin-top:3px; font-style:italic; }
        .vr-meta-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        @media(max-width:782px){ .vr-meta-row { grid-template-columns:1fr; } }
        .vr-meta-field input[type="checkbox"] { margin-right:6px; }
    </style>

    <!-- HERO -->
    <div class="vr-meta-section">
        <h4>&#127968; Hero Settings</h4>
        <div class="vr-meta-field">
            <label for="page_hero_tagline">Hero Tagline (badge above title)</label>
            <input type="text" id="page_hero_tagline" name="page_hero_tagline" value="<?php echo esc_attr( $hero_tagline ); ?>" placeholder="e.g. Explore Roanoke">
            <p class="description">Leave empty to use "Explore Roanoke"</p>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="vr-meta-section">
        <h4>&#128269; Filter Bar (Optional)</h4>
        <div class="vr-meta-field">
            <label>
                <input type="checkbox" name="page_show_filters" value="1" <?php checked( $show_filters, '1' ); ?>>
                Show filter pills below hero
            </label>
        </div>
        <div class="vr-meta-field">
            <label for="page_filters">Filter Categories (comma-separated)</label>
            <input type="text" id="page_filters" name="page_filters" value="<?php echo esc_attr( $filters ); ?>" placeholder="e.g. Outdoor, Dining, Shopping, Arts & Culture">
            <p class="description">Example: Outdoor, Dining, Shopping, Family Fun</p>
        </div>
    </div>

    <!-- INTRO BLOCK -->
    <div class="vr-meta-section">
        <h4>&#128196; Intro Block</h4>
        <div class="vr-meta-field">
            <label for="page_intro_text">Intro Paragraph</label>
            <textarea id="page_intro_text" name="page_intro_text" rows="4"><?php echo esc_textarea( $intro_text ); ?></textarea>
            <p class="description">Shown in the colored intro box below the hero. Supports basic HTML.</p>
        </div>
        <div class="vr-meta-row">
            <div class="vr-meta-field">
                <label for="page_stat_1">Stat 1</label>
                <input type="text" id="page_stat_1" name="page_stat_1" value="<?php echo esc_attr( $stat_1 ); ?>" placeholder="e.g. 40+ Restaurants">
            </div>
            <div class="vr-meta-field">
                <label for="page_stat_2">Stat 2</label>
                <input type="text" id="page_stat_2" name="page_stat_2" value="<?php echo esc_attr( $stat_2 ); ?>" placeholder="e.g. 12 Miles of Trails">
            </div>
        </div>
        <div class="vr-meta-field">
            <label for="page_stat_3">Stat 3</label>
            <input type="text" id="page_stat_3" name="page_stat_3" value="<?php echo esc_attr( $stat_3 ); ?>" placeholder="e.g. Year-Round Events">
        </div>
    </div>

    <!-- CALLOUT -->
    <div class="vr-meta-section">
        <h4>&#128227; Callout Box</h4>
        <div class="vr-meta-field">
            <label for="page_callout">Callout Content</label>
            <textarea id="page_callout" name="page_callout" rows="3"><?php echo esc_textarea( $callout ); ?></textarea>
            <p class="description">Highlighted box with orange left border. Leave empty to hide.</p>
        </div>
    </div>

    <!-- FAQ -->
    <div class="vr-meta-section">
        <h4>&#10067; FAQ Accordion</h4>
        <div class="vr-meta-field">
            <label for="page_faq">FAQ Items</label>
            <textarea id="page_faq" name="page_faq" rows="6"><?php echo esc_textarea( $faq ); ?></textarea>
            <p class="description">Format: Question on first line, answer on next lines. Separate items with a blank line.<br>Example:<br>What are your hours?<br>We are open Monday-Friday 8am-5pm.<br><br>Where do I park?<br>Free parking is available downtown.</p>
        </div>
    </div>

    <!-- MARQUEE -->
    <div class="vr-meta-section">
        <h4>&#128172; Marquee Banner Text</h4>
        <div class="vr-meta-field">
            <label for="page_marquee_text">Marquee Text (use &#9733; for star separators)</label>
            <input type="text" id="page_marquee_text" name="page_marquee_text" value="<?php echo esc_attr( $marquee_text ); ?>" placeholder="Visit Roanoke &#9733; Unique Dining Capital &#9733; Small Town Charm">
            <p class="description">Leave empty for default brand messaging.</p>
        </div>
    </div>

    <!-- BOTTOM CTA -->
    <div class="vr-meta-section">
        <h4>&#127919; Bottom CTA Strip</h4>
        <div class="vr-meta-row">
            <div class="vr-meta-field">
                <label for="page_cta_heading">CTA Heading</label>
                <input type="text" id="page_cta_heading" name="page_cta_heading" value="<?php echo esc_attr( $cta_heading ); ?>" placeholder="Ready to Explore Roanoke?">
            </div>
            <div class="vr-meta-field">
                <label for="page_cta_text">CTA Description</label>
                <input type="text" id="page_cta_text" name="page_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" placeholder="Short description text">
            </div>
        </div>
        <div class="vr-meta-row">
            <div class="vr-meta-field">
                <label for="page_cta_btn_1_text">Primary Button Text</label>
                <input type="text" id="page_cta_btn_1_text" name="page_cta_btn_1_text" value="<?php echo esc_attr( $cta_btn_1 ); ?>" placeholder="Plan Your Visit">
            </div>
            <div class="vr-meta-field">
                <label for="page_cta_btn_1_url">Primary Button URL</label>
                <input type="url" id="page_cta_btn_1_url" name="page_cta_btn_1_url" value="<?php echo esc_attr( $cta_btn_1_url ); ?>" placeholder="/plan-your-visit">
            </div>
        </div>
        <div class="vr-meta-row">
            <div class="vr-meta-field">
                <label for="page_cta_btn_2_text">Secondary Button Text</label>
                <input type="text" id="page_cta_btn_2_text" name="page_cta_btn_2_text" value="<?php echo esc_attr( $cta_btn_2 ); ?>" placeholder="Contact Us">
            </div>
            <div class="vr-meta-field">
                <label for="page_cta_btn_2_url">Secondary Button URL</label>
                <input type="url" id="page_cta_btn_2_url" name="page_cta_btn_2_url" value="<?php echo esc_attr( $cta_btn_2_url ); ?>" placeholder="/contact">
            </div>
        </div>
    </div>
    <?php
}

add_action( 'save_post', function( $post_id ) {
    if ( ! isset( $_POST['interior_page_nonce'] ) || ! wp_verify_nonce( $_POST['interior_page_nonce'], 'interior_page_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    // Defensive: only save when on the interior template
    $template = visit_roanoke_get_effective_template( $post_id );
    if ( $template !== 'page.php' ) {
        return;
    }

    $text_fields = array(
        'page_hero_tagline',
        'page_filters',
        'page_intro_text',
        'page_stat_1',
        'page_stat_2',
        'page_stat_3',
        'page_callout',
        'page_faq',
        'page_marquee_text',
        'page_cta_heading',
        'page_cta_text',
        'page_cta_btn_1_text',
        'page_cta_btn_1_url',
        'page_cta_btn_2_text',
        'page_cta_btn_2_url',
    );

    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }

    $show_filters = isset( $_POST['page_show_filters'] ) ? '1' : '';
    update_post_meta( $post_id, 'page_show_filters', $show_filters );
});


/**
 * Get the effective template filename for a page.
 * WordPress returns '' for the default template; we normalize it to 'page.php'.
 */
function visit_roanoke_get_effective_template( $post_id ) {
    $slug = get_page_template_slug( $post_id );
    return empty( $slug ) ? 'page.php' : $slug;
}

// Newsletter Customizer Settings
function newsletter_customizer_settings( $wp_customize ) {
    
    // Add Section
    $wp_customize->add_section( 'newsletter_section', array(
        'title'       => __( 'Newsletter Section', 'visit-roanoke' ),
        'priority'    => 30,
        'description' => __( 'Configure the newsletter signup form in the footer.', 'visit-roanoke' ),
    ));

    // Enable/Disable
    $wp_customize->add_setting( 'newsletter_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'newsletter_enabled', array(
        'label'       => __( 'Enable Newsletter Section', 'visit-roanoke' ),
        'section'     => 'newsletter_section',
        'type'        => 'checkbox',
    ));

    // Title
    $wp_customize->add_setting( 'newsletter_title', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'newsletter_title', array(
        'label'       => __( 'Title', 'visit-roanoke' ),
        'section'     => 'newsletter_section',
        'type'        => 'text',
        'description' => __( 'Main heading (e.g., "Stay in the Loop")', 'visit-roanoke' ),
    ));

    // Description
    $wp_customize->add_setting( 'newsletter_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'newsletter_description', array(
        'label'       => __( 'Description', 'visit-roanoke' ),
        'section'     => 'newsletter_section',
        'type'        => 'textarea',
        'description' => __( 'Subtitle text below the title', 'visit-roanoke' ),
    ));

    // Custom Embed Code
    $wp_customize->add_setting( 'newsletter_embed_code', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control( 'newsletter_embed_code', array(
        'label'       => __( 'Custom Embed Code', 'visit-roanoke' ),
        'section'     => 'newsletter_section',
        'type'        => 'textarea',
        'description' => __( 'Add HTML, shortcodes, or scripts (e.g., Mailchimp embed form)', 'visit-roanoke' ),
    ));
}
add_action( 'customize_register', 'newsletter_customizer_settings' );