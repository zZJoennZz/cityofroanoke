<?php
/**
 * Template Name: Interior Page — Full Featured
 * Description: Reusable interior page template with hero, sidebar, decorative elements, and animated accents.
 * Use for: Things to Do, Dining, Events, Hotels, Plan Your Trip, and any regular content page.
 */

get_header(); ?>

<!-- ==================== PAGE HERO ==================== -->
<?php
$hero_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$hero_fallback = get_template_directory_uri() . '/assets/images/page-hero-default.jpg';
$hero_bg = $hero_image ? $hero_image : $hero_fallback;
$hero_tagline = get_post_meta( get_the_ID(), 'page_hero_tagline', true );
$hero_tagline = $hero_tagline ? $hero_tagline : __( 'Explore Roanoke', 'visit-roanoke' );
?>
<section class="vr-page-hero">
    <div class="vr-hero-bg" style="background-image: url('<?php echo esc_url( $hero_bg ); ?>');"></div>
    <div class="vr-hero-overlay"></div>

    <!-- Decorative floating boxes -->
    <div class="vr-deco-box vr-deco-1"></div>
    <div class="vr-deco-box vr-deco-2"></div>
    <div class="vr-deco-box vr-deco-3"></div>
    <div class="vr-deco-box vr-deco-4" style="border-radius:50%"></div>

    <!-- Rotating decorative ring -->
    <div class="vr-hero-ring">
        <div class="vr-ring-dot vr-ring-dot-1"></div>
        <div class="vr-ring-dot vr-ring-dot-2"></div>
    </div>

    <div class="vr-hero-content">
        <!-- Breadcrumbs -->
        <nav class="vr-breadcrumbs" aria-label="Breadcrumb">
            <ol>
                <li><a href="<?php echo esc_url( home_url('/') ); ?>"><?php _e( 'Home', 'visit-roanoke' ); ?></a></li>
                <li>
                    <svg class="vr-bc-sep" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <?php
                $ancestors = get_post_ancestors( get_the_ID() );
                if ( $ancestors ) :
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) : ?>
                        <li><a href="<?php echo esc_url( get_permalink( $ancestor ) ); ?>"><?php echo esc_html( get_the_title( $ancestor ) ); ?></a></li>
                        <li><svg class="vr-bc-sep" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <?php endforeach;
                endif; ?>
                <li class="vr-bc-current"><?php the_title(); ?></li>
            </ol>
        </nav>

        <div class="vr-hero-text">
            <span class="vr-hero-badge"><?php echo esc_html( $hero_tagline ); ?></span>
            <h1 class="vr-hero-title"><?php the_title(); ?></h1>
            <?php if ( has_excerpt() ) : ?>
                <p class="vr-hero-desc"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom wave -->
    <div class="vr-hero-wave">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 80V40C240 80 480 0 720 40C960 80 1200 0 1440 40V80H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- ==================== QUICK FILTER BAR (Optional) ==================== -->
<?php
$show_filters = get_post_meta( get_the_ID(), 'page_show_filters', true );
$filters = get_post_meta( get_the_ID(), 'page_filters', true );
if ( $show_filters && ! empty( $filters ) ) :
    $filter_items = array_map( 'trim', explode( ',', $filters ) );
?>
<section class="vr-filter-bar">
    <div class="vr-container">
        <div class="vr-filter-pills">
            <button class="vr-filter-pill vr-filter-pill--active" data-filter="all"><?php _e( 'All', 'visit-roanoke' ); ?></button>
            <?php foreach ( $filter_items as $filter ) : ?>
                <button class="vr-filter-pill" data-filter="<?php echo esc_attr( sanitize_title( $filter ) ); ?>"><?php echo esc_html( $filter ); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== MAIN CONTENT + SIDEBAR ==================== -->
<div class="vr-main-wrap">
    <div class="vr-container">
        <div class="vr-grid">

            <!-- ===== MAIN CONTENT ===== -->
            <main class="vr-main">

                <?php while ( have_posts() ) : the_post(); ?>

                <!-- Intro Block -->
                <?php
                $intro_text = get_post_meta( get_the_ID(), 'page_intro_text', true );
                $stat_1 = get_post_meta( get_the_ID(), 'page_stat_1', true );
                $stat_1_icon = get_post_meta( get_the_ID(), 'page_stat_1_icon', true );
                $stat_2 = get_post_meta( get_the_ID(), 'page_stat_2', true );
                $stat_2_icon = get_post_meta( get_the_ID(), 'page_stat_2_icon', true );
                $stat_3 = get_post_meta( get_the_ID(), 'page_stat_3', true );
                $stat_3_icon = get_post_meta( get_the_ID(), 'page_stat_3_icon', true );
                if ( $intro_text ) : ?>
                <div class="vr-intro-block vr-reveal">
                    <div class="vr-deco-corner vr-deco-corner-tl"></div>
                    <div class="vr-deco-corner vr-deco-corner-br"></div>
                    <h2 class="vr-intro-title"><?php the_title(); ?></h2>
                    <div class="vr-intro-text"><?php echo wp_kses_post( wpautop( $intro_text ) ); ?></div>
                    <?php if ( $stat_1 || $stat_2 || $stat_3 ) : ?>
                    <div class="vr-stats-row">
                        <?php if ( $stat_1 ) : ?>
                        <div class="vr-stat">
                            <div class="vr-stat-icon">
                                <?php if ( $stat_1_icon ) echo wp_kses_post( $stat_1_icon ); ?>
                            </div>
                            <span class="vr-stat-label"><?php echo esc_html( $stat_1 ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $stat_2 ) : ?>
                        <div class="vr-stat">
                            <div class="vr-stat-icon vr-stat-icon--blue">
                                <?php if ( $stat_2_icon ) echo wp_kses_post( $stat_2_icon ); ?>
                            </div>
                            <span class="vr-stat-label"><?php echo esc_html( $stat_2 ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $stat_3 ) : ?>
                        <div class="vr-stat">
                            <div class="vr-stat-icon vr-stat-icon--burnt">
                                <?php if ( $stat_3_icon ) echo wp_kses_post( $stat_3_icon ); ?>
                            </div>
                            <span class="vr-stat-label"><?php echo esc_html( $stat_3 ); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Primary Content -->
                <section class="vr-content-section">
                    <?php if ( ! $intro_text && ! has_excerpt() ) : ?>
                        <h2 class="vr-content-title"><?php the_title(); ?></h2>
                    <?php endif; ?>
                    <div class="vr-prose">
                        <?php the_content(); ?>
                    </div>
                </section>

                <!-- Child Pages Grid -->
                <?php
                $children = get_pages( array( 'child_of' => get_the_ID(), 'sort_column' => 'menu_order' ) );
                if ( ! empty( $children ) ) : ?>
                <section class="vr-children-grid vr-reveal">
                    <h2 class="vr-section-title"><?php _e( 'Related Highlights', 'visit-roanoke' ); ?></h2>
                    <div class="vr-children-cards">
                        <?php foreach ( $children as $child ) :
                            $child_thumb = get_the_post_thumbnail_url( $child->ID, 'medium_large' );
                        ?>
                        <a href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>" class="vr-child-card">
                            <div class="vr-child-img-wrap">
                                <?php if ( $child_thumb ) : ?>
                                    <img src="<?php echo esc_url( $child_thumb ); ?>" alt="<?php echo esc_attr( $child->post_title ); ?>" loading="lazy">
                                <?php else : ?>
                                    <div class="vr-child-img-placeholder">
                                        <span><?php _e( 'Image', 'visit-roanoke' ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="vr-child-body">
                                <h4 class="vr-child-title"><?php echo esc_html( $child->post_title ); ?></h4>
                                <?php if ( $child->post_excerpt ) : ?>
                                    <p class="vr-child-excerpt"><?php echo esc_html( wp_trim_words( $child->post_excerpt, 15 ) ); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="vr-child-accent"></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Callout Box -->
                <?php
                $callout = get_post_meta( get_the_ID(), 'page_callout', true );
                if ( ! empty( $callout ) ) : ?>
                <section class="vr-callout vr-reveal">
                    <div class="vr-callout-inner">
                        <h3 class="vr-callout-title"><?php _e( 'Important Callout', 'visit-roanoke' ); ?></h3>
                        <div class="vr-callout-text"><?php echo wp_kses_post( wpautop( $callout ) ); ?></div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- FAQ Accordion -->
                <?php
                $faq = get_post_meta( get_the_ID(), 'page_faq', true );
                if ( ! empty( $faq ) ) :
                    $faq_items = explode( "\n\n", $faq );
                ?>
                <section class="vr-faq-section vr-reveal">
                    <h2 class="vr-section-title"><?php _e( 'Common Questions', 'visit-roanoke' ); ?></h2>
                    <div class="vr-faq-list">
                        <?php foreach ( $faq_items as $item ) :
                            $parts = explode( "\n", $item, 2 );
                            if ( count( $parts ) === 2 ) :
                                $question = trim( $parts[0] );
                                $answer = trim( $parts[1] );
                        ?>
                        <details class="vr-faq-item">
                            <summary class="vr-faq-question">
                                <?php echo esc_html( $question ); ?>
                                <svg class="vr-faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </summary>
                            <div class="vr-faq-answer"><?php echo wp_kses_post( wpautop( $answer ) ); ?></div>
                        </details>
                        <?php endif; endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Page Navigation -->
                <?php
                wp_link_pages( array(
                    'before' => '<div class="vr-page-nav"><span class="vr-page-nav-label">' . __( 'Pages:', 'visit-roanoke' ) . '</span>',
                    'after'  => '</div>',
                    'link_before' => '<span class="vr-page-nav-link">',
                    'link_after'  => '</span>',
                ) );
                ?>

                <?php endwhile; ?>

            </main>

            <!-- ===== SIDEBAR ===== -->
            <aside class="vr-sidebar">

                <?php if ( is_active_sidebar( 'page-sidebar' ) ) : ?>
                    <?php dynamic_sidebar( 'page-sidebar' ); ?>
                <?php else : ?>

                    <!-- Search -->
                    <div class="vr-sidebar-card vr-reveal">
                        <h3 class="vr-sidebar-title"><?php _e( 'Search', 'visit-roanoke' ); ?></h3>
                        <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" class="vr-search-form">
                            <input type="search" name="s" placeholder="<?php esc_attr_e( 'What are you looking for?', 'visit-roanoke' ); ?>" class="vr-search-input">
                            <button type="submit" class="vr-search-btn" aria-label="<?php esc_attr_e( 'Search', 'visit-roanoke' ); ?>">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- In This Section -->
                    <div class="vr-sidebar-card vr-sidebar-card--dark vr-reveal">
                        <div class="vr-sidebar-deco-tr"></div>
                        <div class="vr-sidebar-deco-bl"></div>
                        <h3 class="vr-sidebar-title vr-sidebar-title--light"><?php _e( 'In This Section', 'visit-roanoke' ); ?></h3>
                        <nav class="vr-sidebar-nav">
                            <?php
                            $current_parent = wp_get_post_parent_id( get_the_ID() );
                            $siblings = get_pages( array(
                                'child_of' => $current_parent ? $current_parent : get_the_ID(),
                                'sort_column' => 'menu_order',
                                'exclude' => get_the_ID(),
                            ) );
                            if ( $siblings ) :
                                foreach ( $siblings as $sibling ) : ?>
                                <a href="<?php echo esc_url( get_permalink( $sibling->ID ) ); ?>" class="vr-sidebar-link">
                                    <?php echo esc_html( $sibling->post_title ); ?>
                                </a>
                                <?php endforeach;
                            else : ?>
                                <span class="vr-sidebar-empty"><?php _e( 'No related pages found.', 'visit-roanoke' ); ?></span>
                            <?php endif; ?>
                        </nav>
                    </div>

                    <!-- Contact CTA -->
                    <div class="vr-sidebar-card vr-sidebar-card--cta vr-reveal">
                        <div class="vr-sidebar-deco-cta-tr"></div>
                        <div class="vr-sidebar-deco-cta-bl"></div>
                        <h3 class="vr-sidebar-title vr-sidebar-title--light"><?php _e( 'Get in Touch', 'visit-roanoke' ); ?></h3>
                        <p class="vr-sidebar-text"><?php _e( 'We are committed to building a strong community. Reach out for help planning your visit.', 'visit-roanoke' ); ?></p>
                        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="vr-sidebar-btn"><?php _e( 'Contact Us', 'visit-roanoke' ); ?></a>
                    </div>

                    <!-- Featured Image -->
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="vr-sidebar-card vr-sidebar-card--image vr-reveal">
                        <div class="vr-sidebar-img-wrap">
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'vr-sidebar-img', 'alt' => get_the_title() ) ); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Resources -->
                    <div class="vr-sidebar-card vr-reveal">
                        <h3 class="vr-sidebar-title"><?php _e( 'Resources', 'visit-roanoke' ); ?></h3>
                        <ul class="vr-sidebar-resources">
                            <li>
                                <a href="#" class="vr-resource-link">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <?php _e( 'Downloadable PDF', 'visit-roanoke' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="vr-resource-link">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <?php _e( 'Visitor Guide', 'visit-roanoke' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="vr-resource-link">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7"/></svg>
                                    <?php _e( 'View Map', 'visit-roanoke' ); ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                <?php endif; ?>

            </aside>
        </div>
    </div>
</div>

<!-- ==================== MARQUEE BANNER ==================== -->
<?php
$marquee_text = get_post_meta( get_the_ID(), 'page_marquee_text', true );
if ( ! $marquee_text ) {
    $marquee_text = __( 'Visit Roanoke ★ Unique Dining Capital ★ Small Town Charm ★ Big Texas Experiences', 'visit-roanoke' );
}
$marquee_items = array_map( 'trim', explode( '★', $marquee_text ) );
?>
<div class="vr-marquee">
    <div class="vr-marquee-fade vr-marquee-fade--left"></div>
    <div class="vr-marquee-fade vr-marquee-fade--right"></div>
    <div class="vr-marquee-track">
        <?php for ( $i = 0; $i < 2; $i++ ) : ?>
            <?php foreach ( $marquee_items as $item ) : ?>
                <span class="vr-marquee-text"><?php echo esc_html( trim( $item ) ); ?></span>
                <span class="vr-marquee-star">&#9733;</span>
            <?php endforeach; ?>
        <?php endfor; ?>
    </div>
</div>

<!-- ==================== FULL-WIDTH CTA STRIP ==================== -->
<?php
$cta_heading = get_post_meta( get_the_ID(), 'page_cta_heading', true );
$cta_heading = $cta_heading ? $cta_heading : __( 'Ready to Explore Roanoke?', 'visit-roanoke' );
$cta_text = get_post_meta( get_the_ID(), 'page_cta_text', true );
$cta_text = $cta_text ? $cta_text : __( 'Whether you are planning a day trip or a weekend getaway, we have got everything you need to make the most of your visit.', 'visit-roanoke' );
$cta_btn_1 = get_post_meta( get_the_ID(), 'page_cta_btn_1_text', true );
$cta_btn_1 = $cta_btn_1 ? $cta_btn_1 : __( 'Plan Your Visit', 'visit-roanoke' );
$cta_btn_1_url = get_post_meta( get_the_ID(), 'page_cta_btn_1_url', true );
$cta_btn_1_url = $cta_btn_1_url ? $cta_btn_1_url : home_url('/plan-your-visit');
$cta_btn_2 = get_post_meta( get_the_ID(), 'page_cta_btn_2_text', true );
$cta_btn_2 = $cta_btn_2 ? $cta_btn_2 : __( 'Contact Us', 'visit-roanoke' );
$cta_btn_2_url = get_post_meta( get_the_ID(), 'page_cta_btn_2_url', true );
$cta_btn_2_url = $cta_btn_2_url ? $cta_btn_2_url : home_url('/contact');
?>
<section class="vr-bottom-cta">
    <div class="vr-cta-bg" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/cta-background.jpg' ); ?>');"></div>
    <div class="vr-cta-overlay"></div>
    <div class="vr-deco-float vr-deco-float-1"></div>
    <div class="vr-deco-float vr-deco-float-2"></div>
    <div class="vr-deco-pulse vr-deco-pulse-1"></div>
    <div class="vr-deco-pulse vr-deco-pulse-2" style="animation-delay:1s"></div>
    <div class="vr-cta-content vr-reveal">
        <span class="vr-cta-badge"><?php _e( 'Start Your Adventure', 'visit-roanoke' ); ?></span>
        <h2 class="vr-cta-title"><?php echo esc_html( $cta_heading ); ?></h2>
        <p class="vr-cta-desc"><?php echo esc_html( $cta_text ); ?></p>
        <div class="vr-cta-btns">
            <a href="<?php echo esc_url( $cta_btn_1_url ); ?>" class="vr-cta-btn vr-cta-btn--primary"><?php echo esc_html( $cta_btn_1 ); ?></a>
            <a href="<?php echo esc_url( $cta_btn_2_url ); ?>" class="vr-cta-btn vr-cta-btn--ghost"><?php echo esc_html( $cta_btn_2 ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>