<?php
/**
 * Visit Roanoke Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'VISIT_ROANOKE_VERSION', '1.0.0' );

/**
 * Theme Setup
 */
function visit_roanoke_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'visit-roanoke' ),
        'footer-explore' => __( 'Footer: Explore', 'visit-roanoke' ),
        'footer-plan'    => __( 'Footer: Plan', 'visit-roanoke' ),
    ) );

    // Register sidebar
    add_action( 'widgets_init', 'visit_roanoke_widgets_init' );
}
add_action( 'after_setup_theme', 'visit_roanoke_setup' );

/**
 * Widget Areas
 */
function visit_roanoke_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Page Sidebar', 'visit-roanoke' ),
        'id'            => 'page-sidebar',
        'description'   => __( 'Widgets for the regular page sidebar.', 'visit-roanoke' ),
        'before_widget' => '<div id="%1$s" class="sidebar-block %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="font-headline text-lg font-bold text-navy mb-4 uppercase tracking-wide">',
        'after_title'   => '</h3>',
    ) );
}

/**
 * Enqueue Scripts & Styles
 */
function visit_roanoke_scripts() {
    // Tailwind CDN (for rapid prototyping; recommend building for production)
    wp_enqueue_script( 'tailwind-cdn', 'https://cdn.tailwindcss.com', array(), null, false );

    // Theme stylesheet
    wp_enqueue_style( 'visit-roanoke-style', get_stylesheet_uri(), array(), VISIT_ROANOKE_VERSION );

    // Configure Tailwind colors
    wp_add_inline_script( 'tailwind-cdn', "
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#173158', 50: '#E8EBF0', 100: '#D1D7E1', 200: '#A3B0C3', 300: '#7589A5', 400: '#476287', 500: '#173158', 600: '#122A4A', 700: '#0E223B', 800: '#09192D', 900: '#05111E' },
                        'roanoke-blue': { DEFAULT: '#3F7EA7', 50: '#EDF3F8', 100: '#DBE7F1', 200: '#B7CFE3', 300: '#93B7D5', 400: '#6F9FC7', 500: '#3F7EA7', 600: '#356A8D', 700: '#2B5673', 800: '#214259', 900: '#172E3F' },
                        'roanoke-gray': { DEFAULT: '#AAAAA9', 50: '#F5F5F5', 100: '#EBEBEB', 200: '#D6D6D6', 300: '#C2C2C1', 400: '#ADADAC', 500: '#AAAAA9', 600: '#8C8C8B', 700: '#6E6E6D', 800: '#505050', 900: '#323232' },
                        orange: { DEFAULT: '#F7942D', 50: '#FEF3E2', 100: '#FDE7C5', 200: '#FBCF8B', 300: '#F9B751', 400: '#F79F17', 500: '#F7942D', 600: '#D47A1E', 700: '#B16016', 800: '#8E460E', 900: '#6B2C06' },
                        'burnt-orange': { DEFAULT: '#DF5B26', 50: '#FCE8E0', 100: '#F9D1C1', 200: '#F3A383', 300: '#ED7545', 400: '#E7581C', 500: '#DF5B26', 600: '#BB4C1F', 700: '#973D18', 800: '#732E11', 900: '#4F1F0A' }
                    }
                }
            }
        }
    " );

    // Google Fonts
    //wp_enqueue_style( 'visit-roanoke-fonts', 'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Merryweather:ital,wght@0,300;0,400;0,700;1,400&display=swap', array(), null );

    // Adobe Typekit Fonts
    wp_enqueue_style( 'visit-roanoke-typekit', 'https://use.typekit.net/xon1mex.css', array(), null );
}
add_action( 'wp_enqueue_scripts', 'visit_roanoke_scripts' );

/**
 * Custom Excerpt Length
 */
function visit_roanoke_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'visit_roanoke_excerpt_length', 999 );

/**
 * Breadcrumbs
 */
function visit_roanoke_breadcrumbs() {
    if ( is_front_page() ) return;

    $sep = '<li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>';

    echo '<nav class="mb-4"><ol class="flex items-center space-x-2 text-sm font-body text-roanoke-blue-200 flex-wrap">';

    echo '<li><a href="' . esc_url( home_url('/') ) . '" class="hover:text-white transition">Home</a></li>';
    echo $sep;

    if ( is_page() ) {
        $ancestors = get_post_ancestors( get_the_ID() );
        if ( $ancestors ) {
            $ancestors = array_reverse( $ancestors );
            foreach ( $ancestors as $ancestor ) {
                echo '<li><a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="hover:text-white transition">' . esc_html( get_the_title( $ancestor ) ) . '</a></li>';
                echo $sep;
            }
        }
        echo '<li class="text-white font-medium">' . esc_html( get_the_title() ) . '</li>';
    } else {
        echo '<li class="text-white font-medium">' . esc_html( get_the_title() ) . '</li>';
    }

    echo '</ol></nav>';
}
/**
 * Custom Walker for multilevel menus with Tailwind dropdowns
 */
class Visit_Roanoke_Walker_Nav_Menu extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent  = str_repeat( "\t", $depth );
        
        // Level 0: dropdown below parent. No margin gap — padding creates visual space.
        $classes = 'sub-menu absolute left-0 top-full -mt-0 w-56 bg-white border border-roanoke-gray-200 shadow-lg rounded-sm pt-2 pb-2 z-50';
        
        // Level 1+: flyout to the right
        if ( $depth > 0 ) {
            $classes = 'sub-menu absolute left-full top-0 w-56 bg-white border border-roanoke-gray-200 shadow-lg rounded-sm py-2 z-50';
        }

        $output .= "\n$indent<ul class=\"$classes\">\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $has_children = in_array( 'menu-item-has-children', $item->classes );
        $is_current   = in_array( 'current-menu-item', $item->classes ) || in_array( 'current_page_item', $item->classes );

        // Base <li> classes
        $li_classes = 'relative group list-none menu-item';
        if ( $has_children ) $li_classes .= ' menu-item-has-children';
        if ( $is_current )   $li_classes .= ' current-menu-item';

        $output .= $indent . '<li class="' . esc_attr( $li_classes ) . '">';

        // Link attributes
        $atts = array(
            'href'  => ! empty( $item->url ) ? $item->url : '',
            'class' => 'text-navy hover:text-orange text-sm font-headline font-medium uppercase tracking-wide transition flex items-center gap-1 py-2',
        );

        // Submenu links look different
        if ( $depth > 0 ) {
            $atts['class'] = 'block px-4 py-2 text-sm font-body text-navy/80 hover:text-orange hover:bg-roanoke-gray-50 transition';
        }

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $item_output  = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= ( $args->link_before ?? '' ) . $title . ( $args->link_after ?? '' );

        // Desktop dropdown arrow (top level only)
        if ( $has_children && $depth === 0 ) {
            $item_output .= '<svg class="w-3 h-3 text-roanoke-blue group-hover:text-orange transition hidden md:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>';
        }

        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        // Mobile accordion toggle (top level only)
        if ( $has_children && $depth === 0 ) {
            $item_output .= '<button type="button" class="mobile-submenu-toggle md:hidden ml-auto p-1 text-navy hover:text-orange" aria-label="Toggle submenu">';
            $item_output .= '<svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
            $item_output .= '</button>';
        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Fallback menu — recursive page tree with multilevel support
 */
function visit_roanoke_fallback_menu( $args ) {
    $pages = get_pages( array(
        'sort_column'  => 'menu_order, post_title',
        'sort_order'   => 'ASC',
        'hierarchical' => 0,
    ) );

    if ( empty( $pages ) ) return;

    $front_page = (int) get_option( 'page_on_front' );
    $tree       = array();
    $children   = array();

    foreach ( $pages as $page ) {
        if ( $page->ID === $front_page ) continue;
        if ( $page->post_parent == 0 ) {
            $tree[] = $page;
        } else {
            $children[ $page->post_parent ][] = $page;
        }
    }

    echo '<ul class="' . esc_attr( $args['menu_class'] ) . '">';
    foreach ( $tree as $page ) {
        visit_roanoke_render_fallback_item( $page, $children, 0, $args );
    }
    echo '</ul>';
}

function visit_roanoke_render_fallback_item( $page, $children, $depth, $args ) {
    $has_children = ! empty( $children[ $page->ID ] );
    $li_class     = 'relative group list-none menu-item';
    if ( $has_children ) $li_class .= ' menu-item-has-children';

    $a_class = ( $depth === 0 )
        ? 'text-navy hover:text-orange text-sm font-headline font-medium uppercase tracking-wide transition flex items-center gap-1 py-2'
        : 'block px-4 py-2 text-sm font-body text-navy/80 hover:text-orange hover:bg-roanoke-gray-50 transition';

    echo '<li class="' . esc_attr( $li_class ) . '">';
    echo '<a href="' . esc_url( get_permalink( $page->ID ) ) . '" class="' . esc_attr( $a_class ) . '">';
    echo esc_html( $page->post_title );

    if ( $has_children && $depth === 0 ) {
        echo '<svg class="w-3 h-3 text-roanoke-blue group-hover:text-orange transition hidden md:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>';
    }
    echo '</a>';

    // Mobile toggle
    if ( $has_children && $depth === 0 ) {
        echo '<button type="button" class="mobile-submenu-toggle md:hidden ml-auto p-1 text-navy hover:text-orange" aria-label="Toggle submenu">';
        echo '<svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
        echo '</button>';
    }

    if ( $has_children ) {
        $ul_class = ( $depth === 0 )
            ? 'sub-menu absolute left-0 top-full -mt-0 w-56 bg-white border border-roanoke-gray-200 shadow-lg rounded-sm pt-2 pb-2 z-50'
            : 'sub-menu absolute left-full top-0 w-56 bg-white border border-roanoke-gray-200 shadow-lg rounded-sm py-2 z-50';
        echo '<ul class="' . esc_attr( $ul_class ) . '">';
        foreach ( $children[ $page->ID ] as $child ) {
            visit_roanoke_render_fallback_item( $child, $children, $depth + 1, $args );
        }
        echo '</ul>';
    }

    echo '</li>';
}

/**
 * Add custom meta box for Event Page — Hero & Stats only
 * Bottom content uses standard WordPress editor + page builder
 */
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'event_page_hero',
        'Event Hero & Quick Stats',
        'render_event_hero_meta_box',
        'page',
        'normal',
        'high'
    );
});

function render_event_hero_meta_box( $post ) {
    wp_nonce_field( 'event_meta_nonce', 'event_meta_nonce' );
    
    $fields = array(
        'event_subtitle'      => array( 'label' => 'Event Subtitle', 'type' => 'text', 'default' => 'Free Admission • Family Friendly', 'desc' => 'Badge above title. Example: Free Admission • Family Friendly' ),
        'event_date'          => array( 'label' => 'Event Date', 'type' => 'text', 'default' => 'Friday, July 3, 2026', 'desc' => 'Shown in hero and When stat. Example: Friday, July 3, 2026' ),
        'event_time_location' => array( 'label' => 'Time & Location', 'type' => 'text', 'default' => 'Downtown Roanoke • 5:00 PM – 10:00 PM', 'desc' => 'Use • to separate. Example: Downtown Roanoke • 5:00 PM – 10:00 PM' ),
        'event_presented_by'  => array( 'label' => 'Presented By', 'type' => 'text', 'default' => 'City of Roanoke', 'desc' => 'Sponsor name below CTA buttons' ),
        'event_cost'          => array( 'label' => 'Cost', 'type' => 'text', 'default' => 'Free Admission', 'desc' => 'Shown in Cost stat card' ),
        'event_audience'      => array( 'label' => 'Audience', 'type' => 'text', 'default' => 'All Ages Welcome', 'desc' => 'Shown in Who stat card' ),
        'event_hashtag'       => array( 'label' => 'Social Hashtag', 'type' => 'text', 'default' => '#RoanokeJuly3rd', 'desc' => 'Used in social section if you add one in page builder' ),
    );
    
    // CTA fields
    $cta_1_text  = get_post_meta( $post->ID, 'event_cta_1_text', true ) ?: 'Learn More';
    $cta_1_url   = get_post_meta( $post->ID, 'event_cta_1_url', true ) ?: '#content';
    $cta_1_style = get_post_meta( $post->ID, 'event_cta_1_style', true ) ?: 'primary';
    $cta_2_text  = get_post_meta( $post->ID, 'event_cta_2_text', true ) ?: 'Event Details';
    $cta_2_url   = get_post_meta( $post->ID, 'event_cta_2_url', true ) ?: '#content';
    $cta_2_style = get_post_meta( $post->ID, 'event_cta_2_style', true ) ?: 'secondary';
    
    // Custom bar
    $custom_bar = get_post_meta( $post->ID, 'event_custom_bar', true );
    
    echo '<style>
        .event-meta-field { margin-bottom: 18px; }
        .event-meta-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .event-meta-field input, .event-meta-field textarea, .event-meta-field select { width: 100%; max-width: 500px; padding: 6px 8px; }
        .event-meta-field textarea { min-height: 80px; font-family: monospace; font-size: 12px; }
        .event-meta-field .description { color: #666; font-size: 12px; margin-top: 3px; font-style: italic; }
        .event-meta-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 782px) { .event-meta-row { grid-template-columns: 1fr; } }
        .event-meta-section { background: #f0f6fc; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #2271b1; }
        .event-meta-section h4 { margin: 0 0 12px 0; font-size: 14px; color: #1d2327; }
    </style>';
    
    // Basic fields
    foreach ( $fields as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true ) ?: $field['default'];
        echo '<div class="event-meta-field">';
        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label>';
        echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
        echo '<p class="description">' . esc_html( $field['desc'] ) . '</p>';
        echo '</div>';
    }
    
    // CTA Buttons
    echo '<div class="event-meta-section">';
    echo '<h4>🎯 CTA Button 1 (Primary/Orange by default)</h4>';
    echo '<div class="event-meta-row">';
    echo '<div class="event-meta-field">';
    echo '<label for="event_cta_1_text">Button Text</label>';
    echo '<input type="text" id="event_cta_1_text" name="event_cta_1_text" value="' . esc_attr( $cta_1_text ) . '">';
    echo '</div>';
    echo '<div class="event-meta-field">';
    echo '<label for="event_cta_1_url">Button URL</label>';
    echo '<input type="text" id="event_cta_1_url" name="event_cta_1_url" value="' . esc_attr( $cta_1_url ) . '">';
    echo '</div>';
    echo '</div>';
    echo '<div class="event-meta-field">';
    echo '<label for="event_cta_1_style">Button Style</label>';
    echo '<select id="event_cta_1_style" name="event_cta_1_style">';
    echo '<option value="primary" ' . selected( $cta_1_style, 'primary', false ) . '>Primary (Orange)</option>';
    echo '<option value="secondary" ' . selected( $cta_1_style, 'secondary', false ) . '>Secondary (Ghost/Outline)</option>';
    echo '</select>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="event-meta-section">';
    echo '<h4>🎯 CTA Button 2 (Ghost/Outline by default)</h4>';
    echo '<div class="event-meta-row">';
    echo '<div class="event-meta-field">';
    echo '<label for="event_cta_2_text">Button Text</label>';
    echo '<input type="text" id="event_cta_2_text" name="event_cta_2_text" value="' . esc_attr( $cta_2_text ) . '">';
    echo '</div>';
    echo '<div class="event-meta-field">';
    echo '<label for="event_cta_2_url">Button URL</label>';
    echo '<input type="text" id="event_cta_2_url" name="event_cta_2_url" value="' . esc_attr( $cta_2_url ) . '">';
    echo '</div>';
    echo '</div>';
    echo '<div class="event-meta-field">';
    echo '<label for="event_cta_2_style">Button Style</label>';
    echo '<select id="event_cta_2_style" name="event_cta_2_style">';
    echo '<option value="primary" ' . selected( $cta_2_style, 'primary', false ) . '>Primary (Orange)</option>';
    echo '<option value="secondary" ' . selected( $cta_2_style, 'secondary', false ) . '>Secondary (Ghost/Outline)</option>';
    echo '</select>';
    echo '</div>';
    echo '</div>';
    
    // Custom HTML Bar
    echo '<div class="event-meta-section">';
    echo '<h4>📝 Custom HTML Bar (appears between time/location and buttons)</h4>';
    echo '<div class="event-meta-field">';
    echo '<label for="event_custom_bar">Custom HTML / Content</label>';
    echo '<textarea id="event_custom_bar" name="event_custom_bar" rows="4">' . esc_textarea( $custom_bar ) . '</textarea>';
    echo '<p class="description">Add custom HTML, shortcodes, or text. Appears right below the time/location line. Leave empty to hide. Supports basic HTML.</p>';
    echo '</div>';
    echo '</div>';
}

add_action( 'save_post', function( $post_id ) {
    if ( ! isset( $_POST['event_meta_nonce'] ) || ! wp_verify_nonce( $_POST['event_meta_nonce'], 'event_meta_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;
    
    $fields = array( 'event_subtitle', 'event_date', 'event_time_location', 'event_presented_by', 'event_cost', 'event_audience', 'event_hashtag' );
    
    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
    
    // Save CTA fields
    $cta_fields = array( 'event_cta_1_text', 'event_cta_1_url', 'event_cta_1_style', 'event_cta_2_text', 'event_cta_2_url', 'event_cta_2_style' );
    foreach ( $cta_fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
    
    // Save custom bar (allow basic HTML)
    if ( isset( $_POST['event_custom_bar'] ) ) {
        update_post_meta( $post_id, 'event_custom_bar', $_POST['event_custom_bar'] ); // no sanitization
    }
});

/**
 * Register Customizer settings for Footer Partners
 */
add_action( 'customize_register', function( $wp_customize ) {
    
    // Partners Section
    $wp_customize->add_section( 'footer_partners', array(
        'title'    => __( 'Footer Partners', 'visit-roanoke' ),
        'priority' => 160,
    ) );
    
    // Show/hide partners
    $wp_customize->add_setting( 'show_footer_partners', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'show_footer_partners', array(
        'label'   => __( 'Show Partners Section', 'visit-roanoke' ),
        'section' => 'footer_partners',
        'type'    => 'checkbox',
    ) );
    
    // Partners heading
    $wp_customize->add_setting( 'partners_heading', array(
        'default'           => 'Proud Partners',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'partners_heading', array(
        'label'   => __( 'Partners Section Heading', 'visit-roanoke' ),
        'section' => 'footer_partners',
        'type'    => 'text',
    ) );
    
    // Number of partner slots (1-6)
    $wp_customize->add_setting( 'partners_count', array(
        'default'           => 3,
        'sanitize_callback' => function( $val ) {
            $val = absint( $val );
            return min( max( $val, 1 ), 6 );
        },
    ) );
    $wp_customize->add_control( 'partners_count', array(
        'label'       => __( 'Number of Partner Slots (Max: 6)', 'visit-roanoke' ),
        'section'     => 'footer_partners',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 6,
            'step' => 1,
        ),
        'description' => __( 'Choose how many partner logos to display (1–6).', 'visit-roanoke' ),
    ) );
    
    // Individual partner settings (max 6)
    for ( $i = 1; $i <= 6; $i++ ) {
        
        // Partner label separator
        $wp_customize->add_setting( "partner_{$i}_label", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "partner_{$i}_label", array(
            'label'       => sprintf( __( '— Partner %d —', 'visit-roanoke' ), $i ),
            'section'     => 'footer_partners',
            'type'        => 'hidden',
            'description' => '<hr style="margin: 10px 0; border-color: #ddd;">',
        ) ) );
        
        // Logo image
        $wp_customize->add_setting( "partner_{$i}_image", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "partner_{$i}_image", array(
            'label'       => sprintf( __( 'Partner %d Logo', 'visit-roanoke' ), $i ),
            'section'     => 'footer_partners',
            'description' => __( 'Upload logo image (transparent PNG recommended, ~200×100px)', 'visit-roanoke' ),
        ) ) );
        
        // Link URL
        $wp_customize->add_setting( "partner_{$i}_url", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "partner_{$i}_url", array(
            'label'       => sprintf( __( 'Partner %d Link URL', 'visit-roanoke' ), $i ),
            'section'     => 'footer_partners',
            'type'        => 'url',
            'description' => __( 'Optional: link when logo is clicked', 'visit-roanoke' ),
        ) );
        
        // Name / alt text
        $wp_customize->add_setting( "partner_{$i}_name", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "partner_{$i}_name", array(
            'label'       => sprintf( __( 'Partner %d Name / Alt Text', 'visit-roanoke' ), $i ),
            'section'     => 'footer_partners',
            'type'        => 'text',
            'description' => __( 'Used for accessibility and placeholder text', 'visit-roanoke' ),
        ) );
    }
} );

/**
 * ============================================
 * FRONT PAGE CUSTOMIZER SETTINGS
 * ============================================
 */

add_action( 'customize_register', function( $wp_customize ) {
    
    // ============================================
    // FRONT PAGE HERO
    // ============================================
    $wp_customize->add_section( 'frontpage_hero', array(
        'title'    => __( 'Front Page — Hero', 'visit-roanoke' ),
        'priority' => 120,
    ) );
    
    // Hero tagline
    $wp_customize->add_setting( 'hero_tagline', array(
        'default'           => 'The Unique Dining Capital of Texas',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_tagline', array(
        'label'   => __( 'Hero Tagline (small text above title)', 'visit-roanoke' ),
        'section' => 'frontpage_hero',
        'type'    => 'text',
    ) );
    
    // Hero title
    $wp_customize->add_setting( 'hero_title', array(
        'default'           => 'Visit Roanoke, Texas',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_title', array(
        'label'   => __( 'Hero Title', 'visit-roanoke' ),
        'section' => 'frontpage_hero',
        'type'    => 'text',
    ) );
    
    // Hero description
    $wp_customize->add_setting( 'hero_description', array(
        'default'           => 'Small town charm. Big Texas experiences. Discover dining, events, and adventure in a community where historic roots meet modern growth.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'hero_description', array(
        'label'   => __( 'Hero Description', 'visit-roanoke' ),
        'section' => 'frontpage_hero',
        'type'    => 'textarea',
        'rows'    => 3,
    ) );
    
    // Hero CTA text
    $wp_customize->add_setting( 'hero_cta_text', array(
        'default'           => 'Plan Your Visit',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_cta_text', array(
        'label'   => __( 'CTA Button Text', 'visit-roanoke' ),
        'section' => 'frontpage_hero',
        'type'    => 'text',
    ) );
    
    // Hero CTA URL
    $wp_customize->add_setting( 'hero_cta_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_cta_url', array(
        'label'       => __( 'CTA Button URL', 'visit-roanoke' ),
        'section'     => 'frontpage_hero',
        'type'        => 'url',
        'description' => __( 'Leave empty to auto-link to Plan Your Visit page', 'visit-roanoke' ),
    ) );
    
    // Hero video ID
    $wp_customize->add_setting( 'hero_video_id', array(
        'default'           => 'q6mRCx-MLMw',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_video_id', array(
        'label'       => __( 'YouTube Video ID', 'visit-roanoke' ),
        'section'     => 'frontpage_hero',
        'type'        => 'text',
        'description' => __( 'The part after youtube.com/watch?v= (e.g., q6mRCx-MLMw)', 'visit-roanoke' ),
    ) );
    
    // ============================================
    // QUICK LINKS (Things to Do, Events, Dining, Hotels, Plan Trip)
    // ============================================
    $wp_customize->add_section( 'frontpage_quicklinks', array(
        'title'    => __( 'Front Page — Quick Links', 'visit-roanoke' ),
        'priority' => 130,
    ) );
    
    // Number of quick links (1-5)
    $wp_customize->add_setting( 'quicklinks_count', array(
        'default'           => 5,
        'sanitize_callback' => function( $val ) {
            $val = absint( $val );
            return min( max( $val, 1 ), 5 );
        },
    ) );
    $wp_customize->add_control( 'quicklinks_count', array(
        'label'       => __( 'Number of Quick Links (Max: 5)', 'visit-roanoke' ),
        'section'     => 'frontpage_quicklinks',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 1, 'max' => 5, 'step' => 1 ),
    ) );
    
    // Quick link defaults
    $quick_defaults = array(
        array( 'Things to Do', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', '' ),
        array( 'Events', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', '' ),
        array( 'Dining', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', '' ),
        array( 'Hotels', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', '' ),
        array( 'Plan Trip', 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7', '' ),
    );
    
    for ( $i = 1; $i <= 5; $i++ ) {
        $default = $quick_defaults[$i-1] ?? array( 'Link ' . $i, '', '' );
        
        // Separator
        $wp_customize->add_setting( "quicklink_{$i}_label", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "quicklink_{$i}_label", array(
            'label'       => sprintf( __( '— Quick Link %d —', 'visit-roanoke' ), $i ),
            'section'     => 'frontpage_quicklinks',
            'type'        => 'hidden',
            'description' => '<hr style="margin: 10px 0; border-color: #ddd;">',
        ) ) );
        
        // Label
        $wp_customize->add_setting( "quicklink_{$i}_text", array(
            'default'           => $default[0],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "quicklink_{$i}_text", array(
            'label'   => sprintf( __( 'Quick Link %d Label', 'visit-roanoke' ), $i ),
            'section' => 'frontpage_quicklinks',
            'type'    => 'text',
        ) );
        
        // SVG Path
        $wp_customize->add_setting( "quicklink_{$i}_icon", array(
            'default'           => $default[1],
            'sanitize_callback' => 'sanitize_textarea_field',
        ) );
        $wp_customize->add_control( "quicklink_{$i}_icon", array(
            'label'       => sprintf( __( 'Quick Link %d Icon SVG Path', 'visit-roanoke' ), $i ),
            'section'     => 'frontpage_quicklinks',
            'type'        => 'textarea',
            'rows'        => 2,
            'description' => __( 'Paste the SVG path "d" attribute only. Get paths from heroicons.com or similar.', 'visit-roanoke' ),
        ) );
        
        // URL
        $wp_customize->add_setting( "quicklink_{$i}_url", array(
            'default'           => $default[2],
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "quicklink_{$i}_url", array(
            'label'       => sprintf( __( 'Quick Link %d URL', 'visit-roanoke' ), $i ),
            'section'     => 'frontpage_quicklinks',
            'type'        => 'url',
            'description' => __( 'Leave empty to auto-link based on label (Things to Do, Events, etc.)', 'visit-roanoke' ),
        ) );
    }
    
    // ============================================
    // FEATURED EXPERIENCE
    // ============================================
    $wp_customize->add_section( 'frontpage_featured', array(
        'title'    => __( 'Front Page — Featured', 'visit-roanoke' ),
        'priority' => 140,
    ) );
    
    // Featured image
    $wp_customize->add_setting( 'featured_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'featured_image', array(
        'label'       => __( 'Featured Image', 'visit-roanoke' ),
        'section'     => 'frontpage_featured',
        'description' => __( 'Recommended: 800×600px or larger', 'visit-roanoke' ),
    ) ) );
    
    // Featured badge
    $wp_customize->add_setting( 'featured_badge', array(
        'default'           => 'Featured',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'featured_badge', array(
        'label'   => __( 'Badge Text (small orange label)', 'visit-roanoke' ),
        'section' => 'frontpage_featured',
        'type'    => 'text',
    ) );
    
    // Featured title
    $wp_customize->add_setting( 'featured_title', array(
        'default'           => 'The Unique Dining Capital of Texas',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'featured_title', array(
        'label'   => __( 'Featured Title', 'visit-roanoke' ),
        'section' => 'frontpage_featured',
        'type'    => 'text',
    ) );
    
    // Featured description
    $wp_customize->add_setting( 'featured_description', array(
        'default'           => 'From craft breweries to upscale steakhouses, Roanoke\'s dining scene is unlike anywhere else in the Metroplex. Explore our walkable downtown packed with local flavor, community pride, and energetic event culture.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'featured_description', array(
        'label'   => __( 'Featured Description', 'visit-roanoke' ),
        'section' => 'frontpage_featured',
        'type'    => 'textarea',
        'rows'    => 4,
    ) );
    
    // Featured CTA text
    $wp_customize->add_setting( 'featured_cta_text', array(
        'default'           => 'Explore Dining',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'featured_cta_text', array(
        'label'   => __( 'CTA Link Text', 'visit-roanoke' ),
        'section' => 'frontpage_featured',
        'type'    => 'text',
    ) );
    
    // Featured CTA URL
    $wp_customize->add_setting( 'featured_cta_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'featured_cta_url', array(
        'label'       => __( 'CTA Link URL', 'visit-roanoke' ),
        'section'     => 'frontpage_featured',
        'type'        => 'url',
        'description' => __( 'Leave empty to auto-link to Dining page', 'visit-roanoke' ),
    ) );
    
    // ============================================
    // EXPERIENCE ROANOKE (EAT / PLAY / STAY)
    // ============================================
    $wp_customize->add_section( 'frontpage_experience', array(
        'title'    => __( 'Front Page — Experience', 'visit-roanoke' ),
        'priority' => 150,
    ) );
    
    // Section title
    $wp_customize->add_setting( 'experience_title', array(
        'default'           => 'Experience Roanoke',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'experience_title', array(
        'label'   => __( 'Section Title', 'visit-roanoke' ),
        'section' => 'frontpage_experience',
        'type'    => 'text',
    ) );
    
    // Section description
    $wp_customize->add_setting( 'experience_description', array(
        'default'           => 'Whether you\'re here for a day or a weekend, there\'s something for everyone in our vibrant, welcoming community.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'experience_description', array(
        'label'   => __( 'Section Description', 'visit-roanoke' ),
        'section' => 'frontpage_experience',
        'type'    => 'textarea',
        'rows'    => 2,
    ) );
    
    // Experience cards (3 max)
    $exp_defaults = array(
        array( 'Eat', 'Over 40 unique restaurants, from Texas BBQ to global cuisine. Outdoor patios, live music, and local craft drinks await.', 'https://s3-media0.fl.yelpcdn.com/bphoto/GTRjw2duPu6b4dT6A5rUAQ/1000s.jpg', '' ),
        array( 'Play', 'Hike the Briarwick Nature Trail, catch a game at our ball fields, or challenge friends to pickleball. Adventure is everywhere.', 'https://cloudfront.traillink.com/photos/bachman-greenbelt-trail_253719_st.jpg', '' ),
        array( 'Stay', 'Comfortable hotels minutes from downtown. A new convention center is coming soon — making Roanoke the perfect destination.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/2a/b2/34/d1/hotel-exterior.jpg?w=1200&h=-1&s=1', '' ),
    );
    
    for ( $i = 1; $i <= 3; $i++ ) {
        $default = $exp_defaults[$i-1] ?? array( 'Card ' . $i, '', '', '' );
        
        // Separator
        $wp_customize->add_setting( "experience_{$i}_label", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "experience_{$i}_label", array(
            'label'       => sprintf( __( '— Experience Card %d —', 'visit-roanoke' ), $i ),
            'section'     => 'frontpage_experience',
            'type'        => 'hidden',
            'description' => '<hr style="margin: 10px 0; border-color: #ddd;">',
        ) ) );
        
        // Card image
        $wp_customize->add_setting( "experience_{$i}_image", array(
            'default'           => $default[2],
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "experience_{$i}_image", array(
            'label'       => sprintf( __( 'Card %d Image', 'visit-roanoke' ), $i ),
            'section'     => 'frontpage_experience',
            'description' => __( 'Recommended: 600×400px', 'visit-roanoke' ),
        ) ) );
        
        // Card title
        $wp_customize->add_setting( "experience_{$i}_title", array(
            'default'           => $default[0],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "experience_{$i}_title", array(
            'label'   => sprintf( __( 'Card %d Title', 'visit-roanoke' ), $i ),
            'section' => 'frontpage_experience',
            'type'    => 'text',
        ) );
        
        // Card description
        $wp_customize->add_setting( "experience_{$i}_description", array(
            'default'           => $default[1],
            'sanitize_callback' => 'sanitize_textarea_field',
        ) );
        $wp_customize->add_control( "experience_{$i}_description", array(
            'label'   => sprintf( __( 'Card %d Description', 'visit-roanoke' ), $i ),
            'section' => 'frontpage_experience',
            'type'    => 'textarea',
            'rows'    => 3,
        ) );
        
        // Card URL
        $wp_customize->add_setting( "experience_{$i}_url", array(
            'default'           => $default[3],
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "experience_{$i}_url", array(
            'label'       => sprintf( __( 'Card %d Link URL', 'visit-roanoke' ), $i ),
            'section'     => 'frontpage_experience',
            'type'        => 'url',
            'description' => __( 'Leave empty to auto-link based on title (Eat, Play, Stay)', 'visit-roanoke' ),
        ) );
    }
    
} );

function register_event_post_type() {
    register_post_type( 'event', array(
        'labels' => array(
            'name'          => 'Events',
            'singular_name' => 'Event',
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon'    => 'dashicons-calendar-alt',
    ) );
}
add_action( 'init', 'register_event_post_type' );

/**
 * ============================================
 * HOMEPAGE CUSTOMIZER HELPERS
 * ============================================
 */

/**
 * Safely retrieve a homepage theme mod.
 * Falls back to the hardcoded default even if an empty string
 * is stored in the database (which is what breaks get_theme_mod).
 */
function vr_homepage_mod( $key, $default = '' ) {
	$value = get_theme_mod( $key, null );

	// If null, the key genuinely doesn't exist — use default.
	// If empty string, user saved blank or preview never published — use default.
	if ( null === $value || '' === $value ) {
		$value = $default;
	}

	return $value;
}

/**
 * Pre-populate homepage Customizer settings on theme activation
 * so the live site never starts with empty values.
 */
function visit_roanoke_populate_homepage_defaults() {
	$defaults = array(
		// Hero
		'hero_tagline'           => 'The Unique Dining Capital of Texas',
		'hero_title'             => 'Visit Roanoke, Texas',
		'hero_description'       => 'Small town charm. Big Texas experiences. Discover dining, events, and adventure in a community where historic roots meet modern growth.',
		'hero_cta_text'          => 'Plan Your Visit',
		'hero_cta_url'           => '',
		'hero_video_id'          => 'q6mRCx-MLMw',

		// Quick Links
		'quicklinks_count'       => 5,

		// Featured
		'featured_image'         => '',
		'featured_badge'         => 'Featured',
		'featured_title'         => 'The Unique Dining Capital of Texas',
		'featured_description'   => 'From craft breweries to upscale steakhouses, Roanoke\'s dining scene is unlike anywhere else in the Metroplex. Explore our walkable downtown packed with local flavor, community pride, and energetic event culture.',
		'featured_cta_text'      => 'Explore Dining',
		'featured_cta_url'       => '',

		// Experience
		'experience_title'       => 'Experience Roanoke',
		'experience_description' => 'Whether you\'re here for a day or a weekend, there\'s something for everyone in our vibrant, welcoming community.',
	);

	foreach ( $defaults as $key => $val ) {
		// Only write if nothing is stored yet (null or empty string)
		$existing = get_theme_mod( $key, null );
		if ( null === $existing || '' === $existing ) {
			set_theme_mod( $key, $val );
		}
	}

	// Quick link defaults
	$quick_defaults = array(
		array( 'text' => 'Things to Do', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'url' => '' ),
		array( 'text' => 'Events',       'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'url' => '' ),
		array( 'text' => 'Dining',       'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'url' => '' ),
		array( 'text' => 'Hotels',       'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'url' => '' ),
		array( 'text' => 'Plan Trip',    'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7', 'url' => '' ),
	);
	for ( $i = 1; $i <= 5; $i++ ) {
		$d = $quick_defaults[ $i - 1 ];
		if ( '' === get_theme_mod( "quicklink_{$i}_text", '' ) ) {
			set_theme_mod( "quicklink_{$i}_text", $d['text'] );
		}
		if ( '' === get_theme_mod( "quicklink_{$i}_icon", '' ) ) {
			set_theme_mod( "quicklink_{$i}_icon", $d['icon'] );
		}
	}

	// Experience card defaults
	$exp_defaults = array(
		array( 'title' => 'Eat',  'desc' => 'Over 40 unique restaurants, from Texas BBQ to global cuisine. Outdoor patios, live music, and local craft drinks await.', 'img' => 'https://s3-media0.fl.yelpcdn.com/bphoto/GTRjw2duPu6b4dT6A5rUAQ/1000s.jpg' ),
		array( 'title' => 'Play', 'desc' => 'Hike the Briarwick Nature Trail, catch a game at our ball fields, or challenge friends to pickleball. Adventure is everywhere.', 'img' => 'https://cloudfront.traillink.com/photos/bachman-greenbelt-trail_253719_st.jpg' ),
		array( 'title' => 'Stay', 'desc' => 'Comfortable hotels minutes from downtown. A new convention center is coming soon — making Roanoke the perfect destination.', 'img' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/2a/b2/34/d1/hotel-exterior.jpg?w=1200&h=-1&s=1' ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		$d = $exp_defaults[ $i - 1 ];
		if ( '' === get_theme_mod( "experience_{$i}_title", '' ) ) {
			set_theme_mod( "experience_{$i}_title", $d['title'] );
		}
		if ( '' === get_theme_mod( "experience_{$i}_description", '' ) ) {
			set_theme_mod( "experience_{$i}_description", $d['desc'] );
		}
		if ( '' === get_theme_mod( "experience_{$i}_image", '' ) ) {
			set_theme_mod( "experience_{$i}_image", $d['img'] );
		}
	}
}
add_action( 'after_switch_theme', 'visit_roanoke_populate_homepage_defaults' );