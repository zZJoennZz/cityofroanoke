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
        $participate  = $this->get_arr($post->ID, 'participate');

        if (empty($schedule)) {
            $schedule = [
                [
                    'name'  => '',
                    'items' => [
                        [
                            'time'     => '',
                            'activity' => '',
                            'location' => '',
                        ],
                    ],
                ],
            ];
        } elseif (isset($schedule[0]['time']) || isset($schedule[0]['activity']) || isset($schedule[0]['description'])) {
            // Convert old flat schedule items into one schedule block.
            $legacy_items = [];

            foreach ($schedule as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $legacy_items[] = [
                    'time'     => $item['time'] ?? '',
                    'activity' => $item['activity'] ?? '',
                    'location' => $item['location'] ?? ($item['description'] ?? ''),
                ];
            }

            $schedule = [
                [
                    'name'  => '',
                    'items' => !empty($legacy_items)
                        ? $legacy_items
                        : [
                            [
                                'time'     => '',
                                'activity' => '',
                                'location' => '',
                            ],
                        ],
                ],
            ];
        }
        if (empty($bring))    $bring    = [['item' => '']];
        if (empty($leave))    $leave    = [['item' => '']];
        if (empty($faq))      $faq      = [['question' => '', 'answer' => '']];
        if (empty($sponsors)) $sponsors = [['image_id' => '', 'name' => '', 'link' => '', 'bio' => '', 'facebook' => '', 'instagram' => '', 'tiktok' => '', 'twitter' => '', 'youtube' => '']];
        if (empty($participate)) $participate = [['title' => '', 'description' => '', 'link_text' => '', 'link_url' => '', 'color_scheme' => 'primary']];
        
        $color_schemes = [
            'primary'          => 'Primary (Event Primary Color)',
            'secondary'        => 'Secondary (Event Secondary Color)',
            'accent'           => 'Accent (Event Accent Color)',
            'background'       => 'Background (Event Background Color)',
            'dark_text'        => 'Dark Text (Event Dark Text Color)',
            'optional_accent'  => 'Optional Accent (Event Optional Accent Color)',
            'white'            => 'White',
        ];
        ?>

        <style>
        /* Schedule Blocks */
        .roanoke-schedule-block {
            background: #f6f7f7;
            border: 1px solid #c3c4c7;
            padding: 15px;
            margin-bottom: 15px;
        }

        .roanoke-schedule-block-header {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            padding-bottom: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #dcdcde;
        }

        .roanoke-schedule-block-header input {
            width: 100%;
            box-sizing: border-box;
        }

        .roanoke-schedule-block-remove {
            background: #d63638 !important;
            color: #fff !important;
            border-color: #d63638 !important;
        }

        .roanoke-schedule-item {
            background: #fff;
            border: 1px solid #dcdcde;
            padding: 12px;
            margin-bottom: 8px;
        }

        .roanoke-schedule-item-fields {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .roanoke-schedule-item-fields label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .roanoke-schedule-item-fields input {
            width: 100%;
            box-sizing: border-box;
        }

        .roanoke-schedule-item-remove {
            background: #d63638 !important;
            color: #fff !important;
            border-color: #d63638 !important;
        }

        .roanoke-schedule-item-add {
            margin-top: 5px !important;
        }

        .roanoke-schedule-block-add {
            margin-top: 5px !important;
        }

        @media (max-width: 782px) {
            .roanoke-schedule-block-header {
                flex-direction: column;
                align-items: stretch;
            }

            .roanoke-schedule-item-fields {
                grid-template-columns: 1fr;
            }
        }
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
        .roanoke-repeater-item--sponsor { flex-direction: column; align-items: stretch; gap: 8px; padding: 12px; background: #f0f0f1; border: 1px solid #b0b0b0; margin-bottom: 10px; }
        .roanoke-repeater-item--sponsor .roanoke-media-field { justify-content: flex-start; flex-wrap: wrap; }
        .roanoke-repeater-item--sponsor input,
        .roanoke-repeater-item--sponsor textarea { width: 100%; box-sizing: border-box; margin-bottom: 4px; }
        .roanoke-repeater-item--sponsor .sponsor-social-row { display: flex; gap: 8px; flex-wrap: wrap; }
        .roanoke-repeater-item--sponsor .sponsor-social-row input { width: calc(33.33% - 6px); min-width: 120px; }
        .roanoke-repeater-item--sponsor .sponsor-bio-row textarea { width: 100%; }
        .roanoke-repeater-item--sponsor .roanoke-repeater-remove { align-self: flex-end; }
        .roanoke-repeater-item--participate {
            flex-direction: column;
            align-items: stretch;
            padding: 12px;
            background: #f0f8ff;
            border: 1px solid #b0d4e8;
            margin-bottom: 10px;
        }
        .roanoke-repeater-item--participate input,
        .roanoke-repeater-item--participate textarea,
        .roanoke-repeater-item--participate select {
            width: 100%;
            box-sizing: border-box;
        }
        .roanoke-repeater-item--participate .roanoke-repeater-remove {
            align-self: flex-end;
            margin-top: 8px;
        }
        .roanoke-repeater-remove { background: #d63638 !important; color: #fff !important; border-color: #d63638 !important; min-width: 32px; cursor: pointer; }
        .roanoke-gallery-preview { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
        .roanoke-gallery-preview img { width: 80px; height: 80px; object-fit: cover; border: 2px solid #c3c4c7; }
        .roanoke-repeater-add { margin-top: 6px !important; }
        .sponsor-social-label { font-size: 11px; font-weight: 600; color: #555; display: block; margin-top: 2px; }
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
                            <tr><th>Background</th><td><input type="color" name="event_color_background" value="<?php echo esc_attr($this->get($post->ID, 'color_background', '#FFFFFF')); ?>" style="width:60px;height:36px;"></td></tr>
                            <tr><th>Dark Text</th><td><input type="color" name="event_color_dark_text" value="<?php echo esc_attr($this->get($post->ID, 'color_dark_text', '#1F2937')); ?>" style="width:60px;height:36px;"></td></tr>
                            <tr><th>Optional Accent</th><td><input type="color" name="event_color_optional_accent" value="<?php echo esc_attr($this->get($post->ID, 'color_optional_accent', '#3B82F6')); ?>" style="width:60px;height:36px;"></td></tr>
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

                <div class="roanoke-schedule-blocks">

                    <?php foreach ($schedule as $block_index => $block) : ?>

                        <div class="roanoke-schedule-block">

                            <div class="roanoke-schedule-block-header">
                                <div style="flex:1;">
                                    <label style="display:block;font-weight:600;margin-bottom:5px;">
                                        Schedule Block Name
                                    </label>

                                    <input
                                        type="text"
                                        name="event_schedule[<?php echo esc_attr($block_index); ?>][name]"
                                        value="<?php echo esc_attr($block['name'] ?? ''); ?>"
                                        placeholder="e.g. Main Stage, Kids Zone, Food & Entertainment"
                                        class="large-text"
                                    >
                                </div>

                                <button
                                    type="button"
                                    class="button roanoke-schedule-block-remove"
                                >
                                    Remove Block
                                </button>
                            </div>

                            <div
                                class="roanoke-schedule-items"
                                data-block-index="<?php echo esc_attr($block_index); ?>"
                            >

                                <?php
                                $items = isset($block['items']) && is_array($block['items'])
                                    ? $block['items']
                                    : [];

                                if (empty($items)) {
                                    $items = [
                                        [
                                            'time'     => '',
                                            'activity' => '',
                                            'location' => '',
                                        ],
                                    ];
                                }
                                ?>

                                <?php foreach ($items as $item_index => $item) : ?>

                                    <div class="roanoke-schedule-item">

                                        <div class="roanoke-schedule-item-fields">

                                            <div>
                                                <label>Time Range</label>
                                                <input
                                                    type="text"
                                                    name="event_schedule[<?php echo esc_attr($block_index); ?>][items][<?php echo esc_attr($item_index); ?>][time]"
                                                    value="<?php echo esc_attr($item['time'] ?? ''); ?>"
                                                    placeholder="5:00 PM - 6:00 PM"
                                                    class="regular-text"
                                                >
                                            </div>

                                            <div>
                                                <label>Activity / Entertainment</label>
                                                <input
                                                    type="text"
                                                    name="event_schedule[<?php echo esc_attr($block_index); ?>][items][<?php echo esc_attr($item_index); ?>][activity]"
                                                    value="<?php echo esc_attr($item['activity'] ?? ''); ?>"
                                                    placeholder="Live Music"
                                                    class="regular-text"
                                                >
                                            </div>

                                            <div>
                                                <label>Location</label>
                                                <input
                                                    type="text"
                                                    name="event_schedule[<?php echo esc_attr($block_index); ?>][items][<?php echo esc_attr($item_index); ?>][location]"
                                                    value="<?php echo esc_attr($item['location'] ?? ''); ?>"
                                                    placeholder="Main Stage"
                                                    class="regular-text"
                                                >
                                            </div>

                                        </div>

                                        <button
                                            type="button"
                                            class="button roanoke-schedule-item-remove"
                                        >
                                            Remove
                                        </button>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <button
                                type="button"
                                class="button roanoke-schedule-item-add"
                            >
                                + Add Timeline Item
                            </button>

                        </div>

                    <?php endforeach; ?>

                </div>

                <button
                    type="button"
                    class="button button-primary roanoke-schedule-block-add"
                >
                    + Add Schedule Block
                </button>

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
                <h3>Participate - Call to Action Cards</h3>
                <p class="description">Add cards for vendors, volunteers, sponsorships, or any other participation opportunities.</p>
                
                <div class="roanoke-repeater" data-field="participate">
                    <div class="roanoke-repeater-items">
                        <?php foreach ($participate as $i => $item) : ?>
                        <div class="roanoke-repeater-item roanoke-repeater-item--participate">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;width:100%;">
                                <input type="text" name="event_participate[<?php echo $i; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Card Title" class="regular-text" style="grid-column:1/3;">
                                
                                <textarea name="event_participate[<?php echo $i; ?>][description]" rows="2" placeholder="Card Description" class="large-text" style="grid-column:1/3;"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                
                                <input type="text" name="event_participate[<?php echo $i; ?>][link_text]" value="<?php echo esc_attr($item['link_text'] ?? ''); ?>" placeholder="Button Text (e.g. Apply Now)" class="regular-text">
                                
                                <input type="url" name="event_participate[<?php echo $i; ?>][link_url]" value="<?php echo esc_url($item['link_url'] ?? ''); ?>" placeholder="Button URL" class="regular-text">
                                
                                <select name="event_participate[<?php echo $i; ?>][color_scheme]" style="grid-column:1/3;width:100%;">
                                    <?php foreach ($color_schemes as $key => $label) : ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php selected($item['color_scheme'] ?? 'primary', $key); ?>><?php echo esc_html($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" class="button roanoke-repeater-remove">&times;</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button roanoke-repeater-add">+ Add Participation Card</button>
                </div>
            </div>

            <!-- SPONSORS -->
            <div class="roanoke-meta-section">
                <h3>Sponsors</h3>
                <table class="form-table">
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
                                        <input type="url" name="event_sponsors[<?php echo $i; ?>][link]" value="<?php echo esc_attr($item['link'] ?? ''); ?>" placeholder="Website URL" class="regular-text">
                                        
                                        <!-- Business Bio -->
                                        <div class="sponsor-bio-row">
                                            <textarea name="event_sponsors[<?php echo $i; ?>][bio]" rows="2" class="large-text" placeholder="Business bio / description"><?php echo esc_textarea($item['bio'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <!-- Social Media Links -->
                                        <div class="sponsor-social-row">
                                            <div style="flex:1;min-width:150px;">
                                                <label class="sponsor-social-label">Facebook</label>
                                                <input type="url" name="event_sponsors[<?php echo $i; ?>][facebook]" value="<?php echo esc_attr($item['facebook'] ?? ''); ?>" placeholder="https://facebook.com/..." class="regular-text">
                                            </div>
                                            <div style="flex:1;min-width:150px;">
                                                <label class="sponsor-social-label">Instagram</label>
                                                <input type="url" name="event_sponsors[<?php echo $i; ?>][instagram]" value="<?php echo esc_attr($item['instagram'] ?? ''); ?>" placeholder="https://instagram.com/..." class="regular-text">
                                            </div>
                                            <div style="flex:1;min-width:150px;">
                                                <label class="sponsor-social-label">TikTok</label>
                                                <input type="url" name="event_sponsors[<?php echo $i; ?>][tiktok]" value="<?php echo esc_attr($item['tiktok'] ?? ''); ?>" placeholder="https://tiktok.com/@..." class="regular-text">
                                            </div>
                                        </div>
                                        <div class="sponsor-social-row">
                                            <div style="flex:1;min-width:150px;">
                                                <label class="sponsor-social-label">X (Twitter)</label>
                                                <input type="url" name="event_sponsors[<?php echo $i; ?>][twitter]" value="<?php echo esc_attr($item['twitter'] ?? ''); ?>" placeholder="https://x.com/..." class="regular-text">
                                            </div>
                                            <div style="flex:1;min-width:150px;">
                                                <label class="sponsor-social-label">YouTube</label>
                                                <input type="url" name="event_sponsors[<?php echo $i; ?>][youtube]" value="<?php echo esc_attr($item['youtube'] ?? ''); ?>" placeholder="https://youtube.com/..." class="regular-text">
                                            </div>
                                            <div style="flex:1;min-width:150px;"></div>
                                        </div>
                                        
                                        <button type="button" class="button roanoke-repeater-remove">&times;</button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="button roanoke-repeater-add">+ Add Sponsor</button>
                            </div>
                        </td>
                    </tr>
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

            // Repeater add (schedule, bring, leave, faq, sponsors, participate)
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
                } else if (field === 'sponsors') {
                    html = '<div class="roanoke-repeater-item roanoke-repeater-item--sponsor">' +
                           '<div class="roanoke-media-field">' +
                           '<input type="hidden" name="event_sponsors[' + index + '][image_id]">' +
                           '<div class="roanoke-media-preview" style="max-width:100px;max-height:100px;"></div>' +
                           '<button type="button" class="button roanoke-repeater-media-upload">Select Logo</button>' +
                           '<button type="button" class="button roanoke-repeater-media-remove" style="display:none;">Remove</button>' +
                           '</div>' +
                           '<input type="text" name="event_sponsors[' + index + '][name]" placeholder="Sponsor Name" class="regular-text">' +
                           '<input type="url" name="event_sponsors[' + index + '][link]" placeholder="Website URL" class="regular-text">' +
                           '<div class="sponsor-bio-row">' +
                           '<textarea name="event_sponsors[' + index + '][bio]" rows="2" class="large-text" placeholder="Business bio / description"></textarea>' +
                           '</div>' +
                           '<div class="sponsor-social-row">' +
                           '<div style="flex:1;min-width:150px;"><label class="sponsor-social-label">Facebook</label><input type="url" name="event_sponsors[' + index + '][facebook]" placeholder="https://facebook.com/..." class="regular-text"></div>' +
                           '<div style="flex:1;min-width:150px;"><label class="sponsor-social-label">Instagram</label><input type="url" name="event_sponsors[' + index + '][instagram]" placeholder="https://instagram.com/..." class="regular-text"></div>' +
                           '<div style="flex:1;min-width:150px;"><label class="sponsor-social-label">TikTok</label><input type="url" name="event_sponsors[' + index + '][tiktok]" placeholder="https://tiktok.com/@..." class="regular-text"></div>' +
                           '</div>' +
                           '<div class="sponsor-social-row">' +
                           '<div style="flex:1;min-width:150px;"><label class="sponsor-social-label">X (Twitter)</label><input type="url" name="event_sponsors[' + index + '][twitter]" placeholder="https://x.com/..." class="regular-text"></div>' +
                           '<div style="flex:1;min-width:150px;"><label class="sponsor-social-label">YouTube</label><input type="url" name="event_sponsors[' + index + '][youtube]" placeholder="https://youtube.com/..." class="regular-text"></div>' +
                           '<div style="flex:1;min-width:150px;"></div>' +
                           '</div>' +
                           '<button type="button" class="button roanoke-repeater-remove">&times;</button></div>';
                } else if (field === 'participate') {
                    html = '<div class="roanoke-repeater-item roanoke-repeater-item--participate">' +
                           '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;width:100%;">' +
                           '<input type="text" name="event_participate[' + index + '][title]" placeholder="Card Title" class="regular-text" style="grid-column:1/3;">' +
                           '<textarea name="event_participate[' + index + '][description]" rows="2" placeholder="Card Description" class="large-text" style="grid-column:1/3;"></textarea>' +
                           '<input type="text" name="event_participate[' + index + '][link_text]" placeholder="Button Text (e.g. Apply Now)" class="regular-text">' +
                           '<input type="url" name="event_participate[' + index + '][link_url]" placeholder="Button URL" class="regular-text">' +
                           '<select name="event_participate[' + index + '][color_scheme]" style="grid-column:1/3;width:100%;">' +
                           '<option value="primary">Primary (Event Primary Color)</option>' +
                           '<option value="secondary">Secondary (Event Secondary Color)</option>' +
                           '<option value="accent">Accent (Event Accent Color)</option>' +
                           '<option value="background">Background (Event Background Color)</option>' +
                           '<option value="dark_text">Dark Text (Event Dark Text Color)</option>' +
                           '<option value="optional_accent">Optional Accent (Event Optional Accent Color)</option>' +
                           '<option value="white">White</option>' +
                           '</select>' +
                           '</div>' +
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

            /**
             * ----------------------------------------------------
             * EVENT SCHEDULE BLOCKS
             * ----------------------------------------------------
             *
             * Structure:
             *
             * Schedule Block
             *   - Block Name
             *   - Timeline Item
             *       - Time Range
             *       - Activity / Entertainment
             *       - Location
             *   - Timeline Item
             *   - ...
             *
             * Schedule Block
             *   - Block Name
             *   - Timeline Item
             *   - ...
             */

            // Add Schedule Block
            $(document).on('click', '.roanoke-schedule-block-add', function(e) {
                e.preventDefault();

                var blocksContainer = $('.roanoke-schedule-blocks');

                // Use timestamp to avoid duplicate indexes after removing blocks.
                var blockIndex = Date.now();

                var html =
                    '<div class="roanoke-schedule-block">' +

                        '<div class="roanoke-schedule-block-header">' +

                            '<div style="flex:1;">' +
                                '<label style="display:block;font-weight:600;margin-bottom:5px;">' +
                                    'Schedule Block Name' +
                                '</label>' +

                                '<input type="text" ' +
                                    'name="event_schedule[' + blockIndex + '][name]" ' +
                                    'placeholder="e.g. Main Stage, Kids Zone, Food & Entertainment" ' +
                                    'class="large-text">' +
                            '</div>' +

                            '<button type="button" class="button roanoke-schedule-block-remove">' +
                                'Remove Block' +
                            '</button>' +

                        '</div>' +

                        '<div class="roanoke-schedule-items" ' +
                            'data-block-index="' + blockIndex + '">' +

                            createRoanokeScheduleItem(blockIndex, Date.now() + 1) +

                        '</div>' +

                        '<button type="button" class="button roanoke-schedule-item-add">' +
                            '+ Add Timeline Item' +
                        '</button>' +

                    '</div>';

                blocksContainer.append(html);
            });


            // Add Timeline Item
            $(document).on('click', '.roanoke-schedule-item-add', function(e) {
                e.preventDefault();

                var button = $(this);
                var block = button.closest('.roanoke-schedule-block');
                var itemsContainer = block.find('.roanoke-schedule-items');
                var blockIndex = itemsContainer.data('block-index');

                // Use timestamp to avoid duplicate indexes after removing items.
                var itemIndex = Date.now();

                itemsContainer.append(
                    createRoanokeScheduleItem(blockIndex, itemIndex)
                );
            });


            // Remove Timeline Item
            $(document).on('click', '.roanoke-schedule-item-remove', function(e) {
                e.preventDefault();

                var item = $(this).closest('.roanoke-schedule-item');
                var itemsContainer = item.closest('.roanoke-schedule-items');

                item.remove();

                // Always keep at least one timeline item in a block.
                if (itemsContainer.children('.roanoke-schedule-item').length === 0) {
                    var blockIndex = itemsContainer.data('block-index');

                    itemsContainer.append(
                        createRoanokeScheduleItem(blockIndex, Date.now())
                    );
                }
            });


            // Remove Schedule Block
            $(document).on('click', '.roanoke-schedule-block-remove', function(e) {
                e.preventDefault();

                var block = $(this).closest('.roanoke-schedule-block');

                block.remove();

                // Always keep at least one schedule block.
                if ($('.roanoke-schedule-blocks .roanoke-schedule-block').length === 0) {

                    var blocksContainer = $('.roanoke-schedule-blocks');
                    var blockIndex = Date.now();

                    var html =
                        '<div class="roanoke-schedule-block">' +

                            '<div class="roanoke-schedule-block-header">' +

                                '<div style="flex:1;">' +
                                    '<label style="display:block;font-weight:600;margin-bottom:5px;">' +
                                        'Schedule Block Name' +
                                    '</label>' +

                                    '<input type="text" ' +
                                        'name="event_schedule[' + blockIndex + '][name]" ' +
                                        'placeholder="e.g. Main Stage, Kids Zone, Food & Entertainment" ' +
                                        'class="large-text">' +
                                '</div>' +

                                '<button type="button" class="button roanoke-schedule-block-remove">' +
                                    'Remove Block' +
                                '</button>' +

                            '</div>' +

                            '<div class="roanoke-schedule-items" ' +
                                'data-block-index="' + blockIndex + '">' +

                                createRoanokeScheduleItem(blockIndex, Date.now() + 1) +

                            '</div>' +

                            '<button type="button" class="button roanoke-schedule-item-add">' +
                                '+ Add Timeline Item' +
                            '</button>' +

                        '</div>';

                    blocksContainer.append(html);
                }
            });


            // Create Timeline Item HTML
            function createRoanokeScheduleItem(blockIndex, itemIndex) {

                return (
                    '<div class="roanoke-schedule-item">' +

                        '<div class="roanoke-schedule-item-fields">' +

                            '<div>' +
                                '<label>Time Range</label>' +
                                '<input type="text" ' +
                                    'name="event_schedule[' + blockIndex + '][items][' + itemIndex + '][time]" ' +
                                    'placeholder="5:00 PM - 6:00 PM" ' +
                                    'class="regular-text">' +
                            '</div>' +

                            '<div>' +
                                '<label>Activity / Entertainment</label>' +
                                '<input type="text" ' +
                                    'name="event_schedule[' + blockIndex + '][items][' + itemIndex + '][activity]" ' +
                                    'placeholder="Live Music" ' +
                                    'class="regular-text">' +
                            '</div>' +

                            '<div>' +
                                '<label>Location</label>' +
                                '<input type="text" ' +
                                    'name="event_schedule[' + blockIndex + '][items][' + itemIndex + '][location]" ' +
                                    'placeholder="Main Stage" ' +
                                    'class="regular-text">' +
                            '</div>' +

                        '</div>' +

                        '<button type="button" class="button roanoke-schedule-item-remove">' +
                            'Remove' +
                        '</button>' +

                    '</div>'
                );
            }

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
            'color_scheme', 'color_primary', 'color_secondary', 'color_accent', 'color_background', 'color_dark_text', 'color_optional_accent',
            'logo_id', 'tagline',
            'hero_image_id', 'hero_video', 'short_desc',
            'date', 'time', 'end_time', 'location', 'address', 'cost', 'countdown',
            'map_image_id',
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
        $arrays = ['bring', 'leave', 'faq', 'sponsors', 'participate'];

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


        /**
         * ----------------------------------------------------
         * EVENT SCHEDULE
         * ----------------------------------------------------
         *
         * Nested structure:
         *
         * [
         *     [
         *         'name' => 'Main Stage',
         *         'items' => [
         *             [
         *                 'time' => '5:00 PM - 6:00 PM',
         *                 'activity' => 'Live Music',
         *                 'location' => 'Main Stage',
         *             ],
         *         ],
         *     ],
         * ]
         */
        $schedule_key = $this->prefix . 'schedule';

        if (
            isset( $_POST['event_schedule'] ) &&
            is_array( $_POST['event_schedule'] )
        ) {

            $clean_schedule = [];

            foreach ( wp_unslash( $_POST['event_schedule'] ) as $block ) {

                if ( ! is_array( $block ) ) {
                    continue;
                }

                $clean_block = [
                    'name'  => sanitize_text_field( $block['name'] ?? '' ),
                    'items' => [],
                ];

                if (
                    isset( $block['items'] ) &&
                    is_array( $block['items'] )
                ) {

                    foreach ( $block['items'] as $item ) {

                        if ( ! is_array( $item ) ) {
                            continue;
                        }

                        $clean_block['items'][] = [
                            'time'     => sanitize_text_field( $item['time'] ?? '' ),
                            'activity' => sanitize_text_field( $item['activity'] ?? '' ),
                            'location' => sanitize_text_field( $item['location'] ?? '' ),
                        ];
                    }
                }

                /*
                * Keep the block even if it currently has no timeline items.
                * This allows the admin to create the block first and populate
                * the timeline later.
                */
                $clean_schedule[] = $clean_block;
            }

            update_post_meta( $post_id, $schedule_key, $clean_schedule );

        } else {

            delete_post_meta( $post_id, $schedule_key );
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
    $subtitle = get_post_meta( $post->ID, 'page_subtitle', true );
    ?>
    <p>
        <label for="page_subtitle"><?php _e( 'Subtitle (displayed below the page title in hero):', 'visit-roanoke' ); ?></label>
        <input type="text" id="page_subtitle" name="page_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" style="width:100%;max-width:500px;" placeholder="e.g. Discover the best of Roanoke">
    </p>
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
        'page_subtitle',
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

/**
 * Register Event Custom Post Type
 */
function visit_roanoke_register_event_post_type() {
    
    $labels = array(
        'name'                  => _x( 'Events', 'Post type general name', 'visit-roanoke' ),
        'singular_name'         => _x( 'Event', 'Post type singular name', 'visit-roanoke' ),
        'menu_name'             => _x( 'Events', 'Admin Menu text', 'visit-roanoke' ),
        'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'visit-roanoke' ),
        'add_new'               => __( 'Add New Event', 'visit-roanoke' ),
        'add_new_item'          => __( 'Add New Event', 'visit-roanoke' ),
        'new_item'              => __( 'New Event', 'visit-roanoke' ),
        'edit_item'             => __( 'Edit Event', 'visit-roanoke' ),
        'view_item'             => __( 'View Event', 'visit-roanoke' ),
        'all_items'             => __( 'All Events', 'visit-roanoke' ),
        'search_items'          => __( 'Search Events', 'visit-roanoke' ),
        'parent_item_colon'     => __( 'Parent Events:', 'visit-roanoke' ),
        'not_found'             => __( 'No events found.', 'visit-roanoke' ),
        'not_found_in_trash'    => __( 'No events found in Trash.', 'visit-roanoke' ),
        'featured_image'        => _x( 'Event Image', 'Overrides the "Featured Image" phrase', 'visit-roanoke' ),
        'set_featured_image'    => _x( 'Set event image', 'Overrides the "Set featured image" phrase', 'visit-roanoke' ),
        'remove_featured_image' => _x( 'Remove event image', 'Overrides the "Remove featured image" phrase', 'visit-roanoke' ),
        'use_featured_image'    => _x( 'Use as event image', 'Overrides the "Use as featured image" phrase', 'visit-roanoke' ),
        'archives'              => _x( 'Event Archives', 'The post type archive label used in nav menus', 'visit-roanoke' ),
        'insert_into_item'      => _x( 'Insert into event', 'Overrides the "Insert into post" phrase', 'visit-roanoke' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the "Uploaded to this post" phrase', 'visit-roanoke' ),
        'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen', 'visit-roanoke' ),
        'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'visit-roanoke' ),
        'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen', 'visit-roanoke' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'events' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true, // Enable Gutenberg
    );

    register_post_type( 'event', $args );

    // Register Event Category Taxonomy
    $tax_labels = array(
        'name'              => _x( 'Event Categories', 'taxonomy general name', 'visit-roanoke' ),
        'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'visit-roanoke' ),
        'search_items'      => __( 'Search Event Categories', 'visit-roanoke' ),
        'all_items'         => __( 'All Event Categories', 'visit-roanoke' ),
        'parent_item'       => __( 'Parent Event Category', 'visit-roanoke' ),
        'parent_item_colon' => __( 'Parent Event Category:', 'visit-roanoke' ),
        'edit_item'         => __( 'Edit Event Category', 'visit-roanoke' ),
        'update_item'       => __( 'Update Event Category', 'visit-roanoke' ),
        'add_new_item'      => __( 'Add New Event Category', 'visit-roanoke' ),
        'new_item_name'     => __( 'New Event Category Name', 'visit-roanoke' ),
        'menu_name'         => __( 'Event Categories', 'visit-roanoke' ),
    );

    $tax_args = array(
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
        'show_in_rest'      => true, // Enable Gutenberg
    );

    register_taxonomy( 'event_category', array( 'event' ), $tax_args );
}
add_action( 'init', 'visit_roanoke_register_event_post_type' );

/**
 * Add Event Details Meta Box
 */
function visit_roanoke_event_meta_box() {
    add_meta_box(
        'event_details_meta',
        __( 'Event Details', 'visit-roanoke' ),
        'visit_roanoke_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'visit_roanoke_event_meta_box' );

function visit_roanoke_event_meta_box_callback( $post ) {
    wp_nonce_field( 'event_details_nonce', 'event_details_nonce' );

    $event_date = get_post_meta( $post->ID, '_event_date', true );
    $event_time = get_post_meta( $post->ID, '_event_time', true );
    $event_short_desc = get_post_meta( $post->ID, '_event_short_desc', true );
    $event_page_id = get_post_meta( $post->ID, '_event_page_id', true );
    $featured = get_post_meta( $post->ID, '_event_featured', true );
    
    // Get all pages for dropdown
    $pages = get_pages( array(
        'sort_order'   => 'ASC',
        'sort_column'  => 'post_title',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'number'       => 0, // Get all pages
    ) );
    ?>
    <style>
        .event-meta-field { margin-bottom: 15px; }
        .event-meta-field label { display: block; font-weight: 600; margin-bottom: 4px; }
        .event-meta-field input[type="text"],
        .event-meta-field input[type="date"],
        .event-meta-field select,
        .event-meta-field textarea { width: 100%; max-width: 500px; padding: 6px 8px; }
        .event-meta-field textarea { min-height: 80px; }
        .event-meta-field .description { color: #666; font-size: 12px; margin-top: 3px; }
        .event-meta-field select { max-width: 500px; }
        .event-meta-field select option { padding: 4px; }
        .event-meta-field select optgroup { font-weight: 700; }
    </style>

    <div class="event-meta-field">
        <label for="event_date"><?php _e( 'Event Date', 'visit-roanoke' ); ?></label>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>">
        <p class="description"><?php _e( 'Select the date of the event.', 'visit-roanoke' ); ?></p>
    </div>

    <div class="event-meta-field">
        <label for="event_time"><?php _e( 'Event Time', 'visit-roanoke' ); ?></label>
        <input type="text" id="event_time" name="event_time" value="<?php echo esc_attr( $event_time ); ?>" placeholder="e.g. 6:00 PM - 9:00 PM">
        <p class="description"><?php _e( 'Enter the time of the event.', 'visit-roanoke' ); ?></p>
    </div>

    <div class="event-meta-field">
        <label for="event_short_desc"><?php _e( 'Short Description', 'visit-roanoke' ); ?></label>
        <textarea id="event_short_desc" name="event_short_desc" rows="3" placeholder="Brief description for the frontpage"><?php echo esc_textarea( $event_short_desc ); ?></textarea>
        <p class="description"><?php _e( 'A short description shown on the frontpage. Max 150 characters recommended.', 'visit-roanoke' ); ?></p>
    </div>

    <div class="event-meta-field">
        <label for="event_page_id"><?php _e( 'Internal Page Link', 'visit-roanoke' ); ?></label>
        <select id="event_page_id" name="event_page_id">
            <option value=""><?php _e( '— Select a page —', 'visit-roanoke' ); ?></option>
            <?php 
            if ( $pages ) {
                foreach ( $pages as $page ) {
                    // Add indentation for child pages
                    $indent = '';
                    if ( $page->post_parent ) {
                        $ancestors = get_post_ancestors( $page->ID );
                        $indent = str_repeat( '&nbsp;&nbsp;&nbsp;', count( $ancestors ) );
                    }
                    ?>
                    <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $event_page_id, $page->ID ); ?>>
                        <?php echo $indent . esc_html( $page->post_title ); ?>
                    </option>
                    <?php
                }
            }
            ?>
        </select>
        <p class="description"><?php _e( 'Select the page users will be directed to when clicking "Learn More".', 'visit-roanoke' ); ?></p>
    </div>

    <!-- <div class="event-meta-field">
        <label>
            <input type="checkbox" name="event_featured" value="1" <?php checked( $featured, '1' ); ?>>
            <?php _e( 'Feature this event on the frontpage', 'visit-roanoke' ); ?>
        </label>
        <p class="description"><?php _e( 'Featured events will appear in the frontpage events section.', 'visit-roanoke' ); ?></p>
    </div> -->
    <?php
}

/**
 * Save Event Meta Box Data
 */
function visit_roanoke_save_event_meta( $post_id ) {
    if ( ! isset( $_POST['event_details_nonce'] ) || ! wp_verify_nonce( $_POST['event_details_nonce'], 'event_details_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'event' ) {
        return;
    }

    // Save event date
    if ( isset( $_POST['event_date'] ) ) {
        update_post_meta( $post_id, '_event_date', sanitize_text_field( $_POST['event_date'] ) );
    }

    // Save event time
    if ( isset( $_POST['event_time'] ) ) {
        update_post_meta( $post_id, '_event_time', sanitize_text_field( $_POST['event_time'] ) );
    }

    // Save short description
    if ( isset( $_POST['event_short_desc'] ) ) {
        update_post_meta( $post_id, '_event_short_desc', sanitize_textarea_field( $_POST['event_short_desc'] ) );
    }

    // Save page ID
    if ( isset( $_POST['event_page_id'] ) && ! empty( $_POST['event_page_id'] ) ) {
        update_post_meta( $post_id, '_event_page_id', intval( $_POST['event_page_id'] ) );
    } else {
        delete_post_meta( $post_id, '_event_page_id' );
    }

    // Save featured status
    $featured = isset( $_POST['event_featured'] ) ? '1' : '';
    update_post_meta( $post_id, '_event_featured', $featured );
}
add_action( 'save_post', 'visit_roanoke_save_event_meta' );

/**
 * Add Dining Spotlight Customizer Settings
 */
function visit_roanoke_dining_spotlight_customizer( $wp_customize ) {
    
    // Add Section
    $wp_customize->add_section( 'dining_spotlight_section', array(
        'title'       => __( 'Dining Spotlight', 'visit-roanoke' ),
        'priority'    => 35,
        'description' => __( 'Configure the dining spotlight section on the frontpage.', 'visit-roanoke' ),
    ));

    // Section Title
    $wp_customize->add_setting( 'dining_spotlight_title', array(
        'default'           => __( 'Dining Spotlight', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'dining_spotlight_title', array(
        'label'       => __( 'Section Title', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'text',
        'description' => __( 'Main heading for the dining spotlight section.', 'visit-roanoke' ),
    ));

    // Section Subtitle
    $wp_customize->add_setting( 'dining_spotlight_subtitle', array(
        'default'           => __( 'A taste of what makes Roanoke the Unique Dining Capital of Texas. From casual eats to fine dining, downtown has it all.', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'dining_spotlight_subtitle', array(
        'label'       => __( 'Section Subtitle', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'textarea',
        'description' => __( 'Subtitle text below the main heading.', 'visit-roanoke' ),
    ));

    // Restaurant 1
    $wp_customize->add_setting( 'dining_spotlight_restaurant_1', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'dining_spotlight_restaurant_1', array(
        'label'       => __( 'Restaurant 1', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'dropdown-pages',
        'description' => __( 'Select a restaurant page to feature.', 'visit-roanoke' ),
    ));

    // Restaurant 2
    $wp_customize->add_setting( 'dining_spotlight_restaurant_2', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'dining_spotlight_restaurant_2', array(
        'label'       => __( 'Restaurant 2', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'dropdown-pages',
        'description' => __( 'Select a restaurant page to feature.', 'visit-roanoke' ),
    ));

    // Restaurant 3
    $wp_customize->add_setting( 'dining_spotlight_restaurant_3', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'dining_spotlight_restaurant_3', array(
        'label'       => __( 'Restaurant 3', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'dropdown-pages',
        'description' => __( 'Select a restaurant page to feature.', 'visit-roanoke' ),
    ));

    // "View All" Button Text
    $wp_customize->add_setting( 'dining_spotlight_button_text', array(
        'default'           => __( 'View All Restaurants', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'dining_spotlight_button_text', array(
        'label'       => __( 'Button Text', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'text',
        'description' => __( 'Text for the "View All" button.', 'visit-roanoke' ),
    ));

    // "View All" Button URL
    $wp_customize->add_setting( 'dining_spotlight_button_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'dining_spotlight_button_url', array(
        'label'       => __( 'Button URL', 'visit-roanoke' ),
        'section'     => 'dining_spotlight_section',
        'type'        => 'url',
        'description' => __( 'URL for the "View All" button. Leave empty to use the dining page.', 'visit-roanoke' ),
    ));
}
add_action( 'customize_register', 'visit_roanoke_dining_spotlight_customizer' );

/**
 * Add Featured Experience Customizer Settings
 */
function visit_roanoke_featured_experience_customizer( $wp_customize ) {
    
    // Add Section
    $wp_customize->add_section( 'featured_experience_section', array(
        'title'       => __( 'Featured Experience', 'visit-roanoke' ),
        'priority'    => 40,
        'description' => __( 'Configure the featured experience section on the frontpage.', 'visit-roanoke' ),
    ));

    // Section Badge (label above title)
    $wp_customize->add_setting( 'featured_experience_badge', array(
        'default'           => __( 'Experience', 'visit-roanoke' ),
        'sanitize_callback' => 'wp_kses_post', // Allow HTML
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'featured_experience_badge', array(
        'label'       => __( 'Badge/Label', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'text',
        'description' => __( 'The small label above the main title. HTML allowed.', 'visit-roanoke' ),
    ));

    // Section Title
    $wp_customize->add_setting( 'featured_experience_title', array(
        'default'           => __( 'The Unique Dining Capital of Texas', 'visit-roanoke' ),
        'sanitize_callback' => 'wp_kses_post', // Allow HTML
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'featured_experience_title', array(
        'label'       => __( 'Main Title', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'textarea',
        'description' => __( 'The main heading. HTML allowed (e.g., <span>highlight</span>).', 'visit-roanoke' ),
    ));

    // Description Paragraph 1
    $wp_customize->add_setting( 'featured_experience_desc_1', array(
        'default'           => __( "From craft breweries to upscale steakhouses, Roanoke's dining scene is unlike anywhere else in the Metroplex. Explore our walkable downtown packed with local flavor, community pride, and energetic event culture.", 'visit-roanoke' ),
        'sanitize_callback' => 'wp_kses_post', // Allow HTML
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'featured_experience_desc_1', array(
        'label'       => __( 'Description Paragraph 1', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'textarea',
        'description' => __( 'First paragraph of description. HTML allowed.', 'visit-roanoke' ),
    ));

    // Description Paragraph 2
    $wp_customize->add_setting( 'featured_experience_desc_2', array(
        'default'           => __( 'With over 41 unique restaurants in our compact downtown, every meal is an adventure. Whether you are craving Texas BBQ, authentic Mexican, or innovative fusion cuisine, Roanoke delivers big flavors with small-town hospitality.', 'visit-roanoke' ),
        'sanitize_callback' => 'wp_kses_post', // Allow HTML
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'featured_experience_desc_2', array(
        'label'       => __( 'Description Paragraph 2', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'textarea',
        'description' => __( 'Second paragraph of description. HTML allowed.', 'visit-roanoke' ),
    ));

    // Image
    $wp_customize->add_setting( 'featured_experience_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'featured_experience_image', array(
        'label'       => __( 'Featured Image', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'description' => __( 'Upload an image for the featured experience section.', 'visit-roanoke' ),
        'mime_type'   => 'image',
    ) ) );

    // Button Text
    $wp_customize->add_setting( 'featured_experience_btn_text', array(
        'default'           => __( 'Explore Dining', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'featured_experience_btn_text', array(
        'label'       => __( 'Button Text', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'text',
        'description' => __( 'Text for the call-to-action button.', 'visit-roanoke' ),
    ));

    // Button URL
    $wp_customize->add_setting( 'featured_experience_btn_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'featured_experience_btn_url', array(
        'label'       => __( 'Button URL', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'url',
        'description' => __( 'URL for the button. Leave empty to use the dining page.', 'visit-roanoke' ),
    ));

    // Image Alignment
    $wp_customize->add_setting( 'featured_experience_image_position', array(
        'default'           => 'left',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'featured_experience_image_position', array(
        'label'       => __( 'Image Position', 'visit-roanoke' ),
        'section'     => 'featured_experience_section',
        'type'        => 'select',
        'choices'     => array(
            'left'  => __( 'Left', 'visit-roanoke' ),
            'right' => __( 'Right', 'visit-roanoke' ),
        ),
        'description' => __( 'Choose whether the image appears on the left or right.', 'visit-roanoke' ),
    ));
}
add_action( 'customize_register', 'visit_roanoke_featured_experience_customizer' );

// Add selective refresh for live preview
add_action( 'customize_preview_init', function() {
    wp_enqueue_script(
        'featured-experience-customizer',
        VISIT_ROANOKE_URI . '/assets/js/customizer.js',
        array( 'jquery', 'customize-preview' ),
        VISIT_ROANOKE_VERSION,
        true
    );
});

/**
 * Add Eat/Play/Stay Customizer Settings
 */
function visit_roanoke_experience_cards_customizer( $wp_customize ) {
    
    // Add Section
    $wp_customize->add_section( 'experience_cards_section', array(
        'title'       => __( 'Eat / Play / Stay Cards', 'visit-roanoke' ),
        'priority'    => 45,
        'description' => __( 'Configure the three experience cards on the frontpage.', 'visit-roanoke' ),
    ));

    // Section Badge
    $wp_customize->add_setting( 'experience_cards_badge', array(
        'default'           => __( 'Explore', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_cards_badge', array(
        'label'       => __( 'Section Badge', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'The small label above the main title.', 'visit-roanoke' ),
    ));

    // Section Title
    $wp_customize->add_setting( 'experience_cards_title', array(
        'default'           => __( 'Experience Roanoke!', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_cards_title', array(
        'label'       => __( 'Section Title', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Main heading for the section.', 'visit-roanoke' ),
    ));

    // Section Description
    $wp_customize->add_setting( 'experience_cards_description', array(
        'default'           => __( 'Whether you are here for a day or a weekend, there is something for everyone in the Unique Dining Capital of Texas.', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_cards_description', array(
        'label'       => __( 'Section Description', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'textarea',
        'description' => __( 'Subtitle text below the main heading.', 'visit-roanoke' ),
    ));

    // ========================================
    // CARD 1 - DINE
    // ========================================
    $wp_customize->add_setting( 'experience_card_1_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'experience_card_1_image', array(
        'label'       => __( 'Card 1 - Image', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'description' => __( 'Image for the Dine card.', 'visit-roanoke' ),
        'mime_type'   => 'image',
    ) ) );

    $wp_customize->add_setting( 'experience_card_1_tag', array(
        'default'           => __( 'Dine', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_card_1_tag', array(
        'label'       => __( 'Card 1 - Tag/Label', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Tag text for the Dine card.', 'visit-roanoke' ),
    ));

    $wp_customize->add_setting( 'experience_card_1_title', array(
        'default'           => __( 'Over 41 Unique Restaurants', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_card_1_title', array(
        'label'       => __( 'Card 1 - Title', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Title text for the Dine card.', 'visit-roanoke' ),
    ));

    $wp_customize->add_setting( 'experience_card_1_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_1_url', array(
        'label'       => __( 'Card 1 - URL', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'url',
        'description' => __( 'URL for the Dine card. Leave empty to make it non-clickable.', 'visit-roanoke' ),
    ));

    // Card 1 Accent Colors
    $wp_customize->add_setting( 'experience_card_1_accent_tl', array(
        'default'           => 'accent-orange',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_1_accent_tl', array(
        'label'       => __( 'Card 1 - Top Left Accent', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'select',
        'choices'     => array(
            'accent-orange' => __( 'Orange', 'visit-roanoke' ),
            'accent-blue'   => __( 'Blue', 'visit-roanoke' ),
            'accent-burnt'  => __( 'Burnt Orange', 'visit-roanoke' ),
            'accent-navy'   => __( 'Navy', 'visit-roanoke' ),
        ),
    ));

    $wp_customize->add_setting( 'experience_card_1_accent_br', array(
        'default'           => 'accent-blue',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_1_accent_br', array(
        'label'       => __( 'Card 1 - Bottom Right Accent', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'select',
        'choices'     => array(
            'accent-orange' => __( 'Orange', 'visit-roanoke' ),
            'accent-blue'   => __( 'Blue', 'visit-roanoke' ),
            'accent-burnt'  => __( 'Burnt Orange', 'visit-roanoke' ),
            'accent-navy'   => __( 'Navy', 'visit-roanoke' ),
        ),
    ));

    // ========================================
    // CARD 2 - PLAY
    // ========================================
    $wp_customize->add_setting( 'experience_card_2_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'experience_card_2_image', array(
        'label'       => __( 'Card 2 - Image', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'description' => __( 'Image for the Play card.', 'visit-roanoke' ),
        'mime_type'   => 'image',
    ) ) );

    $wp_customize->add_setting( 'experience_card_2_tag', array(
        'default'           => __( 'Play', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_card_2_tag', array(
        'label'       => __( 'Card 2 - Tag/Label', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Tag text for the Play card.', 'visit-roanoke' ),
    ));

    $wp_customize->add_setting( 'experience_card_2_title', array(
        'default'           => __( 'Adventure Awaits', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_card_2_title', array(
        'label'       => __( 'Card 2 - Title', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Title text for the Play card.', 'visit-roanoke' ),
    ));

    $wp_customize->add_setting( 'experience_card_2_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_2_url', array(
        'label'       => __( 'Card 2 - URL', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'url',
        'description' => __( 'URL for the Play card. Leave empty to make it non-clickable.', 'visit-roanoke' ),
    ));

    // Card 2 Accent Colors
    $wp_customize->add_setting( 'experience_card_2_accent_tl', array(
        'default'           => 'accent-blue',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_2_accent_tl', array(
        'label'       => __( 'Card 2 - Top Left Accent', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'select',
        'choices'     => array(
            'accent-orange' => __( 'Orange', 'visit-roanoke' ),
            'accent-blue'   => __( 'Blue', 'visit-roanoke' ),
            'accent-burnt'  => __( 'Burnt Orange', 'visit-roanoke' ),
            'accent-navy'   => __( 'Navy', 'visit-roanoke' ),
        ),
    ));

    $wp_customize->add_setting( 'experience_card_2_accent_br', array(
        'default'           => 'accent-burnt',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_2_accent_br', array(
        'label'       => __( 'Card 2 - Bottom Right Accent', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'select',
        'choices'     => array(
            'accent-orange' => __( 'Orange', 'visit-roanoke' ),
            'accent-blue'   => __( 'Blue', 'visit-roanoke' ),
            'accent-burnt'  => __( 'Burnt Orange', 'visit-roanoke' ),
            'accent-navy'   => __( 'Navy', 'visit-roanoke' ),
        ),
    ));

    // ========================================
    // CARD 3 - STAY
    // ========================================
    $wp_customize->add_setting( 'experience_card_3_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'experience_card_3_image', array(
        'label'       => __( 'Card 3 - Image', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'description' => __( 'Image for the Stay card.', 'visit-roanoke' ),
        'mime_type'   => 'image',
    ) ) );

    $wp_customize->add_setting( 'experience_card_3_tag', array(
        'default'           => __( 'Stay', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_card_3_tag', array(
        'label'       => __( 'Card 3 - Tag/Label', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Tag text for the Stay card.', 'visit-roanoke' ),
    ));

    $wp_customize->add_setting( 'experience_card_3_title', array(
        'default'           => __( 'Comfortable Hotels', 'visit-roanoke' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control( 'experience_card_3_title', array(
        'label'       => __( 'Card 3 - Title', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'text',
        'description' => __( 'Title text for the Stay card.', 'visit-roanoke' ),
    ));

    $wp_customize->add_setting( 'experience_card_3_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_3_url', array(
        'label'       => __( 'Card 3 - URL', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'url',
        'description' => __( 'URL for the Stay card. Leave empty to make it non-clickable.', 'visit-roanoke' ),
    ));

    // Card 3 Accent Colors
    $wp_customize->add_setting( 'experience_card_3_accent_tl', array(
        'default'           => 'accent-burnt',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_3_accent_tl', array(
        'label'       => __( 'Card 3 - Top Left Accent', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'select',
        'choices'     => array(
            'accent-orange' => __( 'Orange', 'visit-roanoke' ),
            'accent-blue'   => __( 'Blue', 'visit-roanoke' ),
            'accent-burnt'  => __( 'Burnt Orange', 'visit-roanoke' ),
            'accent-navy'   => __( 'Navy', 'visit-roanoke' ),
        ),
    ));

    $wp_customize->add_setting( 'experience_card_3_accent_br', array(
        'default'           => 'accent-orange',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control( 'experience_card_3_accent_br', array(
        'label'       => __( 'Card 3 - Bottom Right Accent', 'visit-roanoke' ),
        'section'     => 'experience_cards_section',
        'type'        => 'select',
        'choices'     => array(
            'accent-orange' => __( 'Orange', 'visit-roanoke' ),
            'accent-blue'   => __( 'Blue', 'visit-roanoke' ),
            'accent-burnt'  => __( 'Burnt Orange', 'visit-roanoke' ),
            'accent-navy'   => __( 'Navy', 'visit-roanoke' ),
        ),
    ));
}
add_action( 'customize_register', 'visit_roanoke_experience_cards_customizer' );