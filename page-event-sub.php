<?php
/**
 * Template Name: Event Sub-Page
 * 
 * A clean template for interior pages
 * 
 */

get_header();

$post_id = get_the_ID();

// Optional: page-specific subtitle
$subtitle = get_post_meta( $post_id, 'event_subtitle', true ) ?: get_the_title();

// Get parent page info for breadcrumb
$parent_id   = wp_get_post_parent_id( $post_id );
$parent_url  = $parent_id ? get_permalink( $parent_id ) : home_url();
$parent_title = $parent_id ? get_the_title( $parent_id ) : 'Home';
?>

<!-- Small Header Banner -->
<section class="relative h-[280px] md:h-[340px] flex items-center justify-center overflow-hidden bg-navy">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="absolute inset-0">
        <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover opacity-40' ) ); ?>
        <div class="absolute inset-0 bg-gradient-to-t from-navy/80 to-navy/40"></div>
    </div>
    <?php endif; ?>

    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <span class="inline-block bg-white/20 backdrop-blur text-white text-xs font-headline font-bold px-4 py-1.5 rounded-sm mb-3 uppercase tracking-widest border border-white/30">
            <?php echo esc_html( $parent_title ); ?>
        </span>
        <h1 class="font-headline text-4xl md:text-5xl font-bold text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
            <?php the_title(); ?>
        </h1>
        <?php if ( $subtitle !== get_the_title() ) : ?>
        <p class="mt-3 text-white/80 text-base md:text-lg font-body">
            <?php echo esc_html( $subtitle ); ?>
        </p>
        <?php endif; ?>
    </div>
</section>

<!-- Breadcrumb / Back Link -->
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <a href="<?php echo esc_url( $parent_url ); ?>" class="text-sm text-gray-500 hover:text-orange transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to <?php echo esc_html( $parent_title ); ?>
        </a>
    </div>
</div>

<!-- Content Area -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</section>

<?php get_footer(); ?>