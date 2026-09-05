<?php
/**
 * Template Name: Interior Page
 * Description: Clean interior page template with hero, content, and sidebar.
 */

get_header(); ?>
<!-- ==================== PAGE HERO ==================== -->
<?php
// Use featured image as hero background, fallback to site color
$hero_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$hero_style = $hero_image ? 'background-image: url(' . esc_url( $hero_image ) . ');' : 'background-color: var(--navy);';
$subtitle = get_post_meta( get_the_ID(), 'page_subtitle', true );
?>
<section class="vr-page-hero" style="<?php echo $hero_style; ?>">
    <div class="vr-hero-overlay"></div>
    
    <div class="vr-hero-content">
        <div class="vr-container">
            <h1 class="vr-hero-title"><?php the_title(); ?></h1>
            <?php if ( $subtitle ) : ?>
                <p class="vr-hero-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ==================== MAIN CONTENT + SIDEBAR ==================== -->
<div class="vr-main-wrap">
    <div class="vr-container">
        <div class="vr-grid">

            <!-- ===== MAIN CONTENT ===== -->
            <main class="vr-main">
                <?php while ( have_posts() ) : the_post(); ?>
                    
                    <div class="vr-prose">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    // Page navigation for multipage posts
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
                    <!-- Default sidebar content -->
                    <div class="vr-sidebar-card">
                        <h3 class="vr-sidebar-title"><?php _e( 'Search', 'visit-roanoke' ); ?></h3>
                        <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" class="vr-search-form">
                            <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search...', 'visit-roanoke' ); ?>" class="vr-search-input">
                            <button type="submit" class="vr-search-btn" aria-label="<?php esc_attr_e( 'Search', 'visit-roanoke' ); ?>">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </aside>

        </div>
    </div>
</div>

<?php get_footer(); ?>