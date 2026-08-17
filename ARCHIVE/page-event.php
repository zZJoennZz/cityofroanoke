<?php
/**
 * Template Name: Event Page — Hybrid
 * 
 * Top sections (Hero, Stats, What to Expect) use native meta fields.
 * Everything below uses the standard WordPress content editor / page builder.
 * 
 * Setup:
 * 1. Select "Event Page — Hybrid" template.
 * 2. Fill out "Event Hero & Quick Stats" meta box below editor.
 * 3. Set a Featured Image for the hero background.
 * 4. Use your page builder (Elementor/Divi/etc.) for everything else below.
 */

get_header(); 

// --- Fetch meta fields ---
$post_id = get_the_ID();

$subtitle      = get_post_meta( $post_id, 'event_subtitle', true ) ?: 'Free Admission • Family Friendly';
$date          = get_post_meta( $post_id, 'event_date', true ) ?: 'Friday, July 3, 2026';
$time_location = get_post_meta( $post_id, 'event_time_location', true ) ?: 'Downtown Roanoke • 5:00 PM – 10:00 PM';
$presented_by  = get_post_meta( $post_id, 'event_presented_by', true ) ?: 'City of Roanoke';
$cost          = get_post_meta( $post_id, 'event_cost', true ) ?: 'Free Admission';
$audience      = get_post_meta( $post_id, 'event_audience', true ) ?: 'All Ages Welcome';
$hashtag       = get_post_meta( $post_id, 'event_hashtag', true ) ?: '#RoanokeJuly3rd';

// CTA Buttons (up to 2)
$cta_1_text = get_post_meta( $post_id, 'event_cta_1_text', true ) ?: 'Learn More';
$cta_1_url  = get_post_meta( $post_id, 'event_cta_1_url', true ) ?: '#content';
$cta_1_style = get_post_meta( $post_id, 'event_cta_1_style', true ) ?: 'primary';
$cta_2_text = get_post_meta( $post_id, 'event_cta_2_text', true ) ?: 'Event Details';
$cta_2_url  = get_post_meta( $post_id, 'event_cta_2_url', true ) ?: '#content';
$cta_2_style = get_post_meta( $post_id, 'event_cta_2_style', true ) ?: 'secondary';

// Custom HTML bar
$custom_bar = get_post_meta( $post_id, 'event_custom_bar', true );

// Split location from time_location for "Where" stat
$location_parts = explode( '•', $time_location );
$where = trim( $location_parts[0] ) ?: 'Downtown Roanoke';
?>

<!-- ==================== EVENT HERO ==================== -->
<section class="relative min-h-[620px] md:min-h-[620px] flex items-center justify-center overflow-hidden">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="absolute inset-0">
        <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
        <div class="absolute inset-0 bg-navy/50"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-navy/70 via-navy/20 to-navy/40"></div>
    </div>
    <?php else : ?>
    <div class="absolute inset-0 bg-navy"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-navy/70 via-navy/20 to-navy/40"></div>
    <?php endif; ?>

    <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
        <span class="inline-block bg-white/20 backdrop-blur text-white text-xs font-headline font-bold px-4 py-1.5 rounded-sm mb-4 uppercase tracking-widest border border-white/30">
            <?php echo esc_html( $subtitle ); ?>
        </span>
        <h1 class="font-headline text-3xl md:text-6xl lg:text-7xl font-bold text-white mb-4 drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)] leading-[1.1]">
            <?php the_title(); ?>
        </h1>
        <p class="text-lg md:text-2xl text-white/95 mb-2 drop-shadow-[0_1px_4px_rgba(0,0,0,0.5)] font-body">
            <?php echo esc_html( $date ); ?>
        </p>
        <p class="text-base md:text-lg text-white/85 mb-6 drop-shadow-[0_1px_4px_rgba(0,0,0,0.4)] font-body">
            <?php echo esc_html( $time_location ); ?>
        </p>

        <?php if ( ! empty( $custom_bar ) ) : ?>
        <div class="mb-6 max-w-2xl mx-auto">
            <?php echo $custom_bar; ?>
        </div>
        <?php endif; ?>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <?php if ( ! empty( $cta_1_text ) ) : 
                $cta_1_class = ( $cta_1_style === 'primary' )
                    ? 'inline-block bg-orange text-white px-8 py-3 rounded-sm font-headline font-bold text-sm uppercase tracking-wider hover:bg-burnt-orange transition shadow-lg shadow-navy/30'
                    : 'inline-block bg-white/15 backdrop-blur text-white border border-white/40 px-8 py-3 rounded-sm font-headline font-bold text-sm uppercase tracking-wider hover:bg-white/25 transition';
            ?>
            <a href="<?php echo esc_url( $cta_1_url ); ?>" class="<?php echo esc_attr( $cta_1_class ); ?>">
                <?php echo esc_html( $cta_1_text ); ?>
            </a>
            <?php endif; ?>

            <?php if ( ! empty( $cta_2_text ) ) : 
                $cta_2_class = ( $cta_2_style === 'primary' )
                    ? 'inline-block bg-orange text-white px-8 py-3 rounded-sm font-headline font-bold text-sm uppercase tracking-wider hover:bg-burnt-orange transition shadow-lg shadow-navy/30'
                    : 'inline-block bg-white/15 backdrop-blur text-white border border-white/40 px-8 py-3 rounded-sm font-headline font-bold text-sm uppercase tracking-wider hover:bg-white/25 transition';
            ?>
            <a href="<?php echo esc_url( $cta_2_url ); ?>" class="<?php echo esc_attr( $cta_2_class ); ?>">
                <?php echo esc_html( $cta_2_text ); ?>
            </a>
            <?php endif; ?>
        </div>
        <p class="mt-6 text-white/60 text-xs font-headline font-medium tracking-wide">
            Presented by <span class="text-white/90 font-bold"><?php echo esc_html( $presented_by ); ?></span>
        </p>
    </div>
</section>

<!-- ==================== EVENT OVERVIEW (Quick Stats) ==================== -->
<section class="py-12 bg-white border-b border-roanoke-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-roanoke-blue-50 rounded-sm flex items-center justify-center border border-roanoke-blue-100">
                    <svg class="w-6 h-6 text-roanoke-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs text-roanoke-gray-600 uppercase tracking-wider font-headline font-bold">When</p>
                <p class="text-sm font-bold text-navy mt-0.5 font-body"><?php echo esc_html( $date ); ?></p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-roanoke-blue-50 rounded-sm flex items-center justify-center border border-roanoke-blue-100">
                    <svg class="w-6 h-6 text-roanoke-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-xs text-roanoke-gray-600 uppercase tracking-wider font-headline font-bold">Where</p>
                <p class="text-sm font-bold text-navy mt-0.5 font-body"><?php echo esc_html( $where ); ?></p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-roanoke-blue-50 rounded-sm flex items-center justify-center border border-roanoke-blue-100">
                    <svg class="w-6 h-6 text-roanoke-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs text-roanoke-gray-600 uppercase tracking-wider font-headline font-bold">Cost</p>
                <p class="text-sm font-bold text-navy mt-0.5 font-body"><?php echo esc_html( $cost ); ?></p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-roanoke-blue-50 rounded-sm flex items-center justify-center border border-roanoke-blue-100">
                    <svg class="w-6 h-6 text-roanoke-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="text-xs text-roanoke-gray-600 uppercase tracking-wider font-headline font-bold">Who</p>
                <p class="text-sm font-bold text-navy mt-0.5 font-body"><?php echo esc_html( $audience ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PAGE BUILDER CONTENT AREA ==================== -->
<section id="content" class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</section>

<?php get_footer(); ?>