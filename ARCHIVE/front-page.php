<?php
/**
 * The front page template
 */
get_header(); ?>

<!-- ==================== HERO WITH VIDEO BACKGROUND ==================== -->
<section class="relative h-[500px] md:h-[600px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="video-background absolute inset-0 w-full h-full overflow-hidden">
            <?php 
            $video_id = vr_homepage_mod( 'hero_video_id', 'q6mRCx-MLMw' );
            ?>
            <iframe 
                src="https://www.youtube.com/embed/<?php echo esc_attr( $video_id ); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo esc_attr( $video_id ); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1"
                title="Roanoke Video"
                allow="autoplay; encrypted-media"
                allowfullscreen
                class="absolute top-1/2 left-1/2 w-[100vw] h-[56.25vw] min-h-[100vh] min-w-[177.77vh] -translate-x-1/2 -translate-y-1/2 border-0">
            </iframe>
        </div>
    </div>
    <div class="absolute inset-0 bg-navy/60 z-[1]"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-navy/70 via-transparent to-navy/40 z-[1]"></div>

    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <p class="text-orange font-headline font-bold text-sm uppercase tracking-[0.2em] mb-3 drop-shadow-lg">
            <?php echo esc_html( vr_homepage_mod( 'hero_tagline', 'The Unique Dining Capital of Texas' ) ); ?>
        </p>
        <h1 class="font-headline text-5xl md:text-7xl font-bold text-white mb-5 drop-shadow-[0_2px_12px_rgba(0,0,0,0.5)] leading-[1.1]">
            <?php echo esc_html( vr_homepage_mod( 'hero_title', 'Visit Roanoke, Texas' ) ); ?>
        </h1>
        <p class="text-lg md:text-xl text-white/95 mb-10 drop-shadow-[0_1px_4px_rgba(0,0,0,0.6)] max-w-2xl mx-auto font-body leading-relaxed">
            <?php echo esc_html( vr_homepage_mod( 'hero_description', 'Small town charm. Big Texas experiences. Discover dining, events, and adventure in a community where historic roots meet modern growth.' ) ); ?>
        </p>
        <?php 
        $cta_text = vr_homepage_mod( 'hero_cta_text', 'Plan Your Visit' );
        $cta_url  = vr_homepage_mod( 'hero_cta_url', '' );
        if ( empty( $cta_url ) ) {
            $plan_visit = get_page_by_path( 'plan-your-visit' );
            $cta_url = $plan_visit ? get_permalink( $plan_visit ) : '#';
        }
        ?>
        <a href="<?php echo esc_url( $cta_url ); ?>" class="inline-block bg-orange text-white px-10 py-4 rounded-sm font-headline font-bold text-sm uppercase tracking-wider hover:bg-burnt-orange transition shadow-lg shadow-navy/40">
            <?php echo esc_html( $cta_text ); ?>
        </a>
    </div>
</section>

<!-- ==================== UPCOMING EVENTS STRIP ==================== -->
<section class="bg-navy py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-white text-2xl font-headline font-bold tracking-wide">Upcoming Events</h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>" class="text-orange text-sm font-headline font-bold uppercase tracking-wide hover:text-white underline underline-offset-4 transition">View All Events</a>
        </div>
        
        <?php
        $events = new WP_Query( array(
            'post_type'      => 'event',
            'posts_per_page' => 4,
            'meta_key'       => 'event_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'event_date',
                    'value'   => date('Ymd'),
                    'compare' => '>=',
                ),
            ),
        ) );

        if ( $events->have_posts() ) : ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php while ( $events->have_posts() ) : $events->the_post(); 
                $event_date  = get_post_meta( get_the_ID(), 'event_date', true );
                $event_page  = get_field( 'event_page', get_the_ID() );
                $event_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );

                // Handle ACF Link field array
                $event_url    = is_array( $event_page ) && isset( $event_page['url'] ) ? $event_page['url'] : '';
                $event_target = is_array( $event_page ) && isset( $event_page['target'] ) ? $event_page['target'] : '_self';
                
                // Fallback to the event's own permalink if no link is set
                if ( empty( $event_url ) ) {
                    $event_url = get_permalink();
                }
            ?>
            <a href="<?php echo esc_url( $event_url ); ?>" target="<?php echo esc_attr( $event_target ); ?>" class="block group">
                <div class="bg-white/5 backdrop-blur rounded-sm overflow-hidden border border-white/10 hover:border-orange/50 hover:bg-white/10 transition">
                    <div class="w-full h-40 overflow-hidden">
                        <?php if ( $event_image ) : ?>
                            <img src="<?php echo esc_url( $event_image ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php else : ?>
                            <div class="h-full bg-navy-700 flex items-center justify-center">
                                <span class="text-roanoke-gray text-xs font-headline uppercase tracking-wider">[Event Image]</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-5">
                        <p class="text-orange text-xs font-headline font-bold uppercase tracking-widest mb-1">
                            <?php echo esc_html( $event_date ? date( 'F j, Y', strtotime( $event_date ) ) : 'TBD' ); ?>
                        </p>
                        <h3 class="text-white font-headline font-bold text-lg"><?php the_title(); ?></h3>
                    </div>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p class="text-roanoke-blue-200 text-center py-8">No upcoming events found. Check back soon!</p>
        <?php endif; ?>
    </div>
</section>

<!-- ==================== QUICK LINKS / ICON BAR ==================== -->
<section class="py-16 bg-white border-b border-roanoke-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php 
        $quicklinks_count = vr_homepage_mod( 'quicklinks_count', 5 );
        $quicklinks_count = min( max( absint( $quicklinks_count ), 1 ), 5 );
        $grid_cols = $quicklinks_count >= 5 ? 'md:grid-cols-5' : ( $quicklinks_count >= 4 ? 'md:grid-cols-4' : ( $quicklinks_count >= 3 ? 'md:grid-cols-3' : 'md:grid-cols-2' ) );
        ?>
        <div class="grid grid-cols-2 <?php echo esc_attr( $grid_cols ); ?> gap-10">
            <?php
            for ( $i = 1; $i <= $quicklinks_count; $i++ ) {
                $label = vr_homepage_mod( "quicklink_{$i}_text", '' );
                $icon  = vr_homepage_mod( "quicklink_{$i}_icon", '' );
                $url   = vr_homepage_mod( "quicklink_{$i}_url", '' );
                
                // Auto-link based on label if no URL set
                if ( empty( $url ) && ! empty( $label ) ) {
                    $slug = sanitize_title( $label );
                    $page = null;
                    if ( strpos( $slug, 'things-to-do' ) !== false || $slug === 'things-to-do' ) {
                        $page = get_page_by_path( 'things-to-do' );
                    } elseif ( strpos( $slug, 'event' ) !== false ) {
                        $url = get_post_type_archive_link( 'event' );
                    } elseif ( strpos( $slug, 'dining' ) !== false ) {
                        $page = get_page_by_path( 'dining' );
                    } elseif ( strpos( $slug, 'hotel' ) !== false || strpos( $slug, 'stay' ) !== false ) {
                        $page = get_page_by_path( 'hotels' );
                    } elseif ( strpos( $slug, 'plan' ) !== false || strpos( $slug, 'trip' ) !== false ) {
                        $page = get_page_by_path( 'plan-your-visit' );
                    }
                    if ( ! empty( $page ) && empty( $url ) ) {
                        $url = get_permalink( $page );
                    }
                }
                
                if ( empty( $url ) ) $url = '#';
                if ( empty( $label ) ) continue;
                ?>
                <a href="<?php echo esc_url( $url ); ?>" class="flex flex-col items-center group">
                    <div class="w-20 h-20 rounded-sm bg-roanoke-blue-50 border-2 border-roanoke-blue-100 flex items-center justify-center mb-4 group-hover:bg-orange group-hover:border-orange transition duration-300">
                        <?php if ( ! empty( $icon ) ) : ?>
                            <svg class="w-8 h-8 text-roanoke-blue group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo esc_attr( $icon ); ?>"/></svg>
                        <?php else : ?>
                            <svg class="w-8 h-8 text-roanoke-blue group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <?php endif; ?>
                    </div>
                    <span class="text-sm font-headline font-bold text-navy uppercase tracking-wide group-hover:text-orange transition text-center"><?php echo esc_html( $label ); ?></span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<!-- ==================== FEATURED EXPERIENCE ==================== -->
<section class="py-20 bg-roanoke-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-14 items-center">
            <div class="relative rounded-sm overflow-hidden shadow-xl border-4 border-white">
                <?php 
                $featured_img = vr_homepage_mod( 'featured_image', '' );
                if ( $featured_img ) : ?>
                    <img src="<?php echo esc_url( $featured_img ); ?>" alt="<?php echo esc_attr( vr_homepage_mod( 'featured_title', 'Featured Experience' ) ); ?>" class="w-full h-80 md:h-[28rem] object-cover">
                <?php else : ?>
                    <img src="https://www.roanoketexas.gov/ImageRepository/Document?documentID=4983" alt="Downtown Roanoke" class="w-full h-80 md:h-[28rem] object-cover">
                <?php endif; ?>
            </div>
            <div>
                <span class="text-orange text-xs font-headline font-bold tracking-[0.2em] uppercase mb-3 block">
                    <?php echo esc_html( vr_homepage_mod( 'featured_badge', 'Featured' ) ); ?>
                </span>
                <h2 class="font-headline text-4xl md:text-5xl font-bold text-navy mb-5 leading-[1.1]">
                    <?php echo esc_html( vr_homepage_mod( 'featured_title', 'The Unique Dining Capital of Texas' ) ); ?>
                </h2>
                <p class="text-navy/70 mb-8 leading-relaxed text-lg font-body">
                    <?php echo esc_html( vr_homepage_mod( 'featured_description', 'From craft breweries to upscale steakhouses, Roanoke\'s dining scene is unlike anywhere else in the Metroplex. Explore our walkable downtown packed with local flavor, community pride, and energetic event culture.' ) ); ?>
                </p>
                <?php 
                $featured_cta_text = vr_homepage_mod( 'featured_cta_text', 'Explore Dining' );
                $featured_cta_url  = vr_homepage_mod( 'featured_cta_url', '' );
                if ( empty( $featured_cta_url ) ) {
                    $dining = get_page_by_path( 'dining' );
                    $featured_cta_url = $dining ? get_permalink( $dining ) : '#';
                }
                ?>
                <a href="<?php echo esc_url( $featured_cta_url ); ?>" class="inline-flex items-center text-roanoke-blue font-headline font-bold text-sm uppercase tracking-wider hover:text-burnt-orange transition group">
                    <?php echo esc_html( $featured_cta_text ); ?>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== WHY ROANOKE (EAT / PLAY / STAY) ==================== -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="font-headline text-4xl md:text-5xl font-bold text-navy mb-4">
                <?php echo esc_html( vr_homepage_mod( 'experience_title', 'Experience Roanoke' ) ); ?>
            </h2>
            <p class="text-roanoke-gray-600 text-lg max-w-2xl mx-auto font-body">
                <?php echo esc_html( vr_homepage_mod( 'experience_description', 'Whether you\'re here for a day or a weekend, there\'s something for everyone in our vibrant, welcoming community.' ) ); ?>
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            for ( $i = 1; $i <= 3; $i++ ) {
                $card_title = vr_homepage_mod( "experience_{$i}_title", '' );
                $card_desc  = vr_homepage_mod( "experience_{$i}_description", '' );
                $card_img   = vr_homepage_mod( "experience_{$i}_image", '' );
                $card_url   = vr_homepage_mod( "experience_{$i}_url", '' );
                
                // Auto-link based on title
                if ( empty( $card_url ) && ! empty( $card_title ) ) {
                    $slug = sanitize_title( $card_title );
                    $page = null;
                    if ( $slug === 'eat' || strpos( $slug, 'dining' ) !== false || strpos( $slug, 'food' ) !== false ) {
                        $page = get_page_by_path( 'dining' );
                    } elseif ( $slug === 'play' || strpos( $slug, 'things-to-do' ) !== false || strpos( $slug, 'adventure' ) !== false ) {
                        $page = get_page_by_path( 'things-to-do' );
                    } elseif ( $slug === 'stay' || strpos( $slug, 'hotel' ) !== false ) {
                        $page = get_page_by_path( 'hotels' );
                    }
                    if ( ! empty( $page ) ) {
                        $card_url = get_permalink( $page );
                    }
                }
                
                if ( empty( $card_url ) ) $card_url = '#';
                if ( empty( $card_title ) ) continue;
                ?>
                <a href="<?php echo esc_url( $card_url ); ?>" class="group cursor-pointer rounded-sm overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-roanoke-gray-100 hover:border-orange/30 block no-underline">
                    <div class="w-full h-64 overflow-hidden">
                        <?php if ( $card_img ) : ?>
                            <img src="<?php echo esc_url( $card_img ); ?>" alt="<?php echo esc_attr( $card_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        <?php else : ?>
                            <div class="w-full h-full bg-roanoke-gray-200 flex items-center justify-center">
                                <span class="text-roanoke-gray text-sm font-headline uppercase tracking-wider">[Image]</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-7">
                        <h3 class="font-headline text-2xl font-bold text-navy mb-3"><?php echo esc_html( $card_title ); ?></h3>
                        <p class="text-navy/60 leading-relaxed font-body"><?php echo esc_html( $card_desc ); ?></p>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<!-- ==================== NEWSLETTER / SOCIAL ==================== -->
<section class="newsletter-section">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-headline text-3xl md:text-4xl font-bold text-navy mb-3">Stay in the Loop</h2>
        <p class="text-navy/60 mb-10 text-lg font-body">Get the latest events, dining news, and trip ideas delivered to your inbox.</p>
        
        <?php 
        if ( shortcode_exists( 'fluentform' ) ) {
            echo do_shortcode( '[fluentform id="2"]' );
        } else {
            // Fallback if Fluent Forms is deactivated
            echo '<p class="text-red-500 text-sm font-body">Newsletter form temporarily unavailable. Please check back later.</p>';
        }
        ?>
        
        <div class="flex justify-center space-x-6">
            <?php
            $socials = array(
                array( 'Facebook', 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z', '#' ),
                array( 'Instagram', 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z', '#' ),
                array( 'YouTube', 'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z', '#' ),
            );
            foreach ( $socials as $social ) :
            ?>
            <a href="<?php echo esc_url( $social[2] ); ?>" aria-label="<?php echo esc_attr( $social[0] ); ?>" class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-orange hover:text-white transition group border border-roanoke-gray-100">
                <svg class="w-5 h-5 text-navy group-hover:text-white transition" fill="currentColor" viewBox="0 0 24 24"><path d="<?php echo esc_attr( $social[1] ); ?>"/></svg>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>