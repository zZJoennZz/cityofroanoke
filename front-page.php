<?php
/**
 * The template for displaying the front page
 *
 * @package Visit_Roanoke
 */

get_header();
?>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section">
    <div class="hero-media">
        <div class="hero-video active" style="position:absolute; inset:0; overflow:hidden;">
            <iframe 
                src="https://www.youtube.com/embed/bIHv9YqRB24?autoplay=1&mute=1&loop=1&playlist=bIHv9YqRB24&controls=0&playsinline=1&rel=0" 
                style="
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 100vw;
                    height: 56.25vw;      /* 9/16 of viewport width */
                    min-height: 100vh;    /* force full height on portrait screens */
                    min-width: 177.78vh;  /* 16/9 of viewport height — crops the sides on mobile */
                    transform: translate(-50%, -50%);
                    border: 0;
                    pointer-events: none; /* lets clicks pass through to CTAs */
                "
                allow="autoplay; fullscreen"
                loading="eager"
                title="Roanoke Hero Video">
            </iframe>
        </div>
        <div class="hero-slide active" style="background-image: url('<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/hero-1.jpg' ); ?>');"></div>
        <div class="hero-slide" style="background-image: url('<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/hero-2.jpg' ); ?>');"></div>
        <div class="hero-slide" style="background-image: url('<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/hero-3.jpg' ); ?>');"></div>
        <div class="hero-slide" style="background-image: url('<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/hero-4.jpg' ); ?>');"></div>
    </div>

    <!-- Decorative floating shapes -->
    <div class="deco-shape deco-square deco-orange float-slow" style="top: 15%; left: 8%; width: 40px; height: 40px; opacity: 0.7;"></div>
    <div class="deco-shape deco-rect deco-blue float-medium" style="top: 25%; right: 10%; width: 80px; height: 30px; opacity: 0.5;"></div>
    <div class="deco-shape deco-circle deco-burnt float-fast" style="bottom: 20%; left: 12%; width: 35px; height: 35px; opacity: 0.6;"></div>
    <div class="deco-shape deco-square deco-navy float-slow" style="bottom: 30%; right: 8%; width: 50px; height: 50px; opacity: 0.4;"></div>

    <div class="hero-content">
        <span class="hero-badge"><?php esc_html_e( 'The Unique Dining Capital of Texas', 'visit-roanoke' ); ?></span>
        <h1 class="hero-title font-headline"><?php esc_html_e( 'Visit Roanoke, Texas', 'visit-roanoke' ); ?></h1>
        <p class="hero-subtitle font-body">
            <?php esc_html_e( 'Small town charm. Big Texas experiences. Discover dining, events, and adventure where historic roots meet modern growth.', 'visit-roanoke' ); ?>
        </p>
        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'plan-your-visit' ) ) ); ?>" class="hero-cta font-headline">
            <?php esc_html_e( 'Plan Your Visit', 'visit-roanoke' ); ?>
        </a>
    </div>

    <div class="scroll-indicator">
        <span></span>
    </div>
</section>

<!-- ==================== QUICK LINKS ==================== -->
<section class="quick-links-section">
    <div class="deco-shape deco-square deco-orange float-medium" style="top: 20%; left: 3%; width: 30px; height: 30px; opacity: 0.3;"></div>
    <div class="deco-shape deco-circle deco-blue float-slow" style="bottom: 15%; right: 5%; width: 45px; height: 45px; opacity: 0.25;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-6">

            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'things-to-do' ) ) ); ?>" class="quick-link group">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/></svg>
                </div>
                <span><?php esc_html_e( 'Things to Do', 'visit-roanoke' ); ?></span>
            </a>

            <a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>" class="quick-link group">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                </div>
                <span><?php esc_html_e( 'Fun Events', 'visit-roanoke' ); ?></span>
            </a>

            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dining' ) ) ); ?>" class="quick-link group">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/></svg>
                </div>
                <span><?php esc_html_e( 'Local Dining', 'visit-roanoke' ); ?></span>
            </a>

            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'hotels' ) ) ); ?>" class="quick-link group">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                </div>
                <span><?php esc_html_e( 'Hotels & Stays', 'visit-roanoke' ); ?></span>
            </a>

            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'plan-your-visit' ) ) ); ?>" class="quick-link group">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                </div>
                <span><?php esc_html_e( 'Plan Your Trip', 'visit-roanoke' ); ?></span>
            </a>

        </div>
    </div>
</section>

<!-- ==================== STATS BAR ==================== -->
<section class="stats-bar">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="stat-item">
                <div class="stat-number font-headline">41+</div>
                <div class="stat-label font-headline"><?php esc_html_e( 'Unique Restaurants', 'visit-roanoke' ); ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number font-headline">10K+</div>
                <div class="stat-label font-headline"><?php esc_html_e( 'Residents', 'visit-roanoke' ); ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number font-headline">30+</div>
                <div class="stat-label font-headline"><?php esc_html_e( 'Annual Events', 'visit-roanoke' ); ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number font-headline">170+</div>
                <div class="stat-label font-headline"><?php esc_html_e( 'Years of History', 'visit-roanoke' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== WHY ROANOKE ==================== -->
<section class="why-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-3 block"><?php esc_html_e( 'Discover', 'visit-roanoke' ); ?></span>
            <h2 class="font-headline text-4xl md:text-5xl font-black text-navy mb-4"><?php esc_html_e( 'Why Roanoke?', 'visit-roanoke' ); ?></h2>
            <p class="text-navy/60 text-lg max-w-2xl mx-auto font-body"><?php esc_html_e( 'A small town with big personality, located just minutes from DFW Airport and the heart of North Texas.', 'visit-roanoke' ); ?></p>
        </div>

        <div class="grid md:grid-cols-4 gap-6">

            <!-- Value Card 1 -->
            <div class="card-frame reveal reveal-delay-1">
                <div class="corner-accent corner-tl accent-orange"></div>
                <div class="corner-accent corner-br accent-blue"></div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    </div>
                    <h3 class="font-headline"><?php esc_html_e( 'Prime Location', 'visit-roanoke' ); ?></h3>
                    <p class="font-body"><?php esc_html_e( 'Just 25 miles from Dallas and 10 minutes from DFW Airport. Easy access to everything North Texas has to offer.', 'visit-roanoke' ); ?></p>
                </div>
            </div>

            <!-- Value Card 2 -->
            <div class="card-frame reveal reveal-delay-2">
                <div class="corner-accent corner-tl accent-blue"></div>
                <div class="corner-accent corner-br accent-burnt"></div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0L3 16.5m15-3.379a48.474 48.474 0 0 0-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 0 1 3 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 0 1 6 13.12M12 13.12a48.474 48.474 0 0 0 6-.371"/></svg>
                    </div>
                    <h3 class="font-headline"><?php esc_html_e( 'Award-Winning Dining', 'visit-roanoke' ); ?></h3>
                    <p class="font-body"><?php esc_html_e( 'Named the Unique Dining Capital of Texas. From BBQ to bistros, our 41+ restaurants serve up unforgettable flavors.', 'visit-roanoke' ); ?></p>
                </div>
            </div>

            <!-- Value Card 3 -->
            <div class="card-frame reveal reveal-delay-3">
                <div class="corner-accent corner-tl accent-burnt"></div>
                <div class="corner-accent corner-br accent-orange"></div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </div>
                    <h3 class="font-headline"><?php esc_html_e( 'Community Spirit', 'visit-roanoke' ); ?></h3>
                    <p class="font-body"><?php esc_html_e( 'Over 30 annual events bring neighbors together. From Taste & Tunes to Celebrate Roanoke, there is always something happening.', 'visit-roanoke' ); ?></p>
                </div>
            </div>

            <!-- Value Card 4 -->
            <div class="card-frame reveal">
                <div class="corner-accent corner-tl accent-navy"></div>
                <div class="corner-accent corner-br accent-orange"></div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3 class="font-headline"><?php esc_html_e( 'Rich History', 'visit-roanoke' ); ?></h3>
                    <p class="font-body"><?php esc_html_e( 'Founded in 1847, Roanoke blends 170+ years of Texas heritage with modern amenities and a vibrant downtown district.', 'visit-roanoke' ); ?></p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==================== UPCOMING EVENTS ==================== -->
<section class="events-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-2 block"><?php esc_html_e( "Don't Miss Out", 'visit-roanoke' ); ?></span>
                <h2 class="text-white text-3xl md:text-4xl font-headline font-black tracking-wide"><?php esc_html_e( 'Upcoming Events', 'visit-roanoke' ); ?></h2>
            </div>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>" class="text-orange text-sm font-headline font-bold uppercase tracking-wide hover:text-white transition underline underline-offset-4 decoration-2">
                <?php esc_html_e( 'View All Events', 'visit-roanoke' ); ?>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <?php
            // Query upcoming events — adjust post type / taxonomy as needed
            $events = new WP_Query( array(
                'post_type'      => 'event',
                'posts_per_page' => 4,
                'meta_key'       => 'event_date',
                'orderby'        => 'meta_value',
                'order'          => 'ASC',
                'meta_query'     => array(
                    array(
                        'key'     => 'event_date',
                        'value'   => date( 'Y-m-d' ),
                        'compare' => '>=',
                        'type'    => 'DATE',
                    ),
                ),
            ) );

            if ( $events->have_posts() ) :
                $delay = 0;
                while ( $events->have_posts() ) : $events->the_post();
                    $event_date = get_post_meta( get_the_ID(), 'event_date', true );
                    $event_year = $event_date ? date( 'Y', strtotime( $event_date ) ) : date( 'Y' );
                    $event_day  = $event_date ? date( 'M j', strtotime( $event_date ) ) : '';
                    $delay_class = $delay > 0 ? ' reveal-delay-' . $delay : '';
            ?>
                <div class="card-frame reveal<?php echo esc_attr( $delay_class ); ?>">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-blue"></div>
                    <div class="event-card">
                        <div class="img-wrap">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'roanoke-card', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/event-placeholder.jpg' ); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <?php if ( $event_day ) : ?>
                                <span class="date-badge"><?php echo esc_html( $event_day ); ?></span>
                            <?php endif; ?>
                            <span class="year-badge"><?php echo esc_html( $event_year ); ?></span>
                        </div>
                        <div class="card-body">
                            <h3 class="font-headline"><?php the_title(); ?></h3>
                            <p class="font-body"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 8 ) ); ?></p>
                        </div>
                    </div>
                </div>
            <?php
                    $delay = min( $delay + 1, 3 );
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback static events
            ?>
                <div class="card-frame reveal">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-blue"></div>
                    <div class="event-card">
                        <div class="img-wrap">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/events/taste-tunes.jpg' ); ?>" alt="<?php esc_attr_e( 'Taste & Tunes', 'visit-roanoke' ); ?>">
                            <span class="date-badge">OCT 3</span>
                            <span class="year-badge">2026</span>
                        </div>
                        <div class="card-body">
                            <h3 class="font-headline"><?php esc_html_e( 'Taste & Tunes', 'visit-roanoke' ); ?></h3>
                            <p class="font-body"><?php esc_html_e( 'Live music & local flavors downtown', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-1">
                    <div class="corner-accent corner-tl accent-blue"></div>
                    <div class="corner-accent corner-br accent-burnt"></div>
                    <div class="event-card">
                        <div class="img-wrap">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/events/hometown-holiday.jpg' ); ?>" alt="<?php esc_attr_e( 'Hometown Holiday', 'visit-roanoke' ); ?>">
                            <span class="date-badge" style="background: var(--burnt-orange); color: white;">DEC 4</span>
                            <span class="year-badge">2026</span>
                        </div>
                        <div class="card-body">
                            <h3 class="font-headline"><?php esc_html_e( 'Hometown Holiday', 'visit-roanoke' ); ?></h3>
                            <p class="font-body"><?php esc_html_e( 'Celebrate the season with the community', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-2">
                    <div class="corner-accent corner-tl accent-burnt"></div>
                    <div class="corner-accent corner-br accent-orange"></div>
                    <div class="event-card">
                        <div class="img-wrap">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/events/celebrate-roanoke.jpg' ); ?>" alt="<?php esc_attr_e( 'Celebrate Roanoke', 'visit-roanoke' ); ?>">
                            <span class="date-badge">OCT 11</span>
                            <span class="year-badge">2026</span>
                        </div>
                        <div class="card-body">
                            <h3 class="font-headline"><?php esc_html_e( 'Celebrate Roanoke 2026', 'visit-roanoke' ); ?></h3>
                            <p class="font-body"><?php esc_html_e( 'Our biggest community festival', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-3">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-navy"></div>
                    <div class="event-card">
                        <div class="img-wrap">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/events/holiday-plaza.jpg' ); ?>" alt="<?php esc_attr_e( 'Holiday in the Plaza', 'visit-roanoke' ); ?>">
                            <span class="date-badge" style="background: var(--burnt-orange); color: white;">DEC 4</span>
                            <span class="year-badge">2026</span>
                        </div>
                        <div class="card-body">
                            <h3 class="font-headline"><?php esc_html_e( 'Holiday in the Plaza', 'visit-roanoke' ); ?></h3>
                            <p class="font-body"><?php esc_html_e( 'Family fun in the square', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- ==================== DINING SPOTLIGHT ==================== -->
<section class="dining-spotlight">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12 reveal">
            <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-3 block"><?php esc_html_e( 'Local Favorites', 'visit-roanoke' ); ?></span>
            <h2 class="font-headline text-4xl md:text-5xl font-black text-white mb-4"><?php esc_html_e( 'Dining Spotlight', 'visit-roanoke' ); ?></h2>
            <p class="text-white/60 text-lg max-w-2xl mx-auto font-body"><?php esc_html_e( 'A taste of what makes Roanoke the Unique Dining Capital of Texas. From casual eats to fine dining, downtown has it all.', 'visit-roanoke' ); ?></p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <?php
            // Query featured restaurants — adjust post type as needed
            $restaurants = new WP_Query( array(
                'post_type'      => 'restaurant',
                'posts_per_page' => 3,
                'meta_key'       => 'featured',
                'meta_value'     => '1',
            ) );

            if ( $restaurants->have_posts() ) :
                $delay = 0;
                while ( $restaurants->have_posts() ) : $restaurants->the_post();
                    $cuisine  = get_post_meta( get_the_ID(), 'cuisine_type', true );
                    $rating   = get_post_meta( get_the_ID(), 'rating', true );
                    $rating   = $rating ? floatval( $rating ) : 5;
                    $delay_class = $delay > 0 ? ' reveal-delay-' . $delay : '';
            ?>
                <div class="card-frame reveal<?php echo esc_attr( $delay_class ); ?>">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-blue"></div>
                    <div class="restaurant-card">
                        <div class="rest-img">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'roanoke-card', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/dining-placeholder.jpg' ); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="rest-body">
                            <?php if ( $cuisine ) : ?>
                                <span class="cuisine-tag font-headline"><?php echo esc_html( $cuisine ); ?></span>
                            <?php endif; ?>
                            <div class="star-rating mb-2">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <?php echo $i <= $rating ? '&#9733;' : '&#9734;'; ?>
                                <?php endfor; ?>
                            </div>
                            <h4 class="font-headline"><?php the_title(); ?></h4>
                            <p class="font-body"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 12 ) ); ?></p>
                        </div>
                    </div>
                </div>
            <?php
                    $delay = min( $delay + 1, 2 );
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback static restaurants
            ?>
                <div class="card-frame reveal">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-blue"></div>
                    <div class="restaurant-card">
                        <div class="rest-img">
                            <img src="https://placehold.co/600x400/1e293b/FFF?text=Restaurant" alt="<?php esc_attr_e( 'Restaurant placeholder image', 'visit-roanoke' ); ?>">
                        </div>
                        <div class="rest-body">
                            <span class="cuisine-tag font-headline"><?php esc_html_e( 'Cuisine Type', 'visit-roanoke' ); ?></span>
                            <div class="star-rating mb-2">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
                            <h4 class="font-headline"><?php esc_html_e( 'Restaurant Name', 'visit-roanoke' ); ?></h4>
                            <p class="font-body"><?php esc_html_e( 'Description of the dining experience, menu highlights, and atmosphere goes here.', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-1">
                    <div class="corner-accent corner-tl accent-blue"></div>
                    <div class="corner-accent corner-br accent-burnt"></div>
                    <div class="restaurant-card">
                        <div class="rest-img">
                            <img src="https://placehold.co/600x400/334155/FFF?text=Dining" alt="<?php esc_attr_e( 'Restaurant placeholder image', 'visit-roanoke' ); ?>">
                        </div>
                        <div class="rest-body">
                            <span class="cuisine-tag font-headline"><?php esc_html_e( 'Dining Style', 'visit-roanoke' ); ?></span>
                            <div class="star-rating mb-2">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
                            <h4 class="font-headline"><?php esc_html_e( 'Bistro Name', 'visit-roanoke' ); ?></h4>
                            <p class="font-body"><?php esc_html_e( 'Fresh ingredients and quality service in a welcoming setting.', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-2">
                    <div class="corner-accent corner-tl accent-burnt"></div>
                    <div class="corner-accent corner-br accent-orange"></div>
                    <div class="restaurant-card">
                        <div class="rest-img">
                            <img src="https://placehold.co/600x400/475569/FFF?text=Cafe" alt="<?php esc_attr_e( 'Restaurant placeholder image', 'visit-roanoke' ); ?>">
                        </div>
                        <div class="rest-body">
                            <span class="cuisine-tag font-headline"><?php esc_html_e( 'Food Category', 'visit-roanoke' ); ?></span>
                            <div class="star-rating mb-2">&#9733; &#9733; &#9733; &#9733; &#9734;</div>
                            <h4 class="font-headline"><?php esc_html_e( 'Cafe Name', 'visit-roanoke' ); ?></h4>
                            <p class="font-body"><?php esc_html_e( 'Local flavors and seasonal menus served in a casual atmosphere.', 'visit-roanoke' ); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="text-center mt-10 reveal">
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dining' ) ) ); ?>" class="inline-flex items-center text-white font-headline font-bold text-sm uppercase tracking-wider hover:text-orange transition group">
                <?php esc_html_e( 'View All Restaurants', 'visit-roanoke' ); ?>
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ==================== PHOTO GALLERY ==================== -->
<section class="gallery-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 reveal">
            <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-3 block"><?php esc_html_e( 'Explore', 'visit-roanoke' ); ?></span>
            <h2 class="font-headline text-4xl md:text-5xl font-black text-navy mb-4"><?php esc_html_e( 'Roanoke in Pictures', 'visit-roanoke' ); ?></h2>
            <p class="text-navy/60 text-lg max-w-2xl mx-auto font-body"><?php esc_html_e( 'See why visitors fall in love with our charming downtown, vibrant events, and Texas hospitality.', 'visit-roanoke' ); ?></p>
        </div>

        <div class="gallery-grid reveal">
            <?php for ( $i = 1; $i <= 6; $i++ ) : 
                $image_id = get_theme_mod( "gallery_image_{$i}" );
                $alt      = get_theme_mod( "gallery_alt_{$i}" );
                $caption  = get_theme_mod( "gallery_caption_{$i}" );
                $layout   = get_theme_mod( "gallery_layout_{$i}" );

                if ( ! $image_id ) {
                    continue;
                }

                $image_url = wp_get_attachment_image_url( $image_id, 'large' );
                if ( ! $image_url ) {
                    continue;
                }

                // Fallback to native WP alt if custom alt is empty
                if ( empty( $alt ) ) {
                    $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                }
            ?>
            <div class="gallery-item <?php echo esc_attr( $layout ); ?>">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
                <?php if ( $caption ) : ?>
                <div class="gallery-overlay">
                    <span class="gallery-caption font-headline"><?php echo esc_html( $caption ); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- ==================== TESTIMONIALS ==================== -->
<section class="testimonials-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12 reveal">
            <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-3 block"><?php esc_html_e( 'Reviews', 'visit-roanoke' ); ?></span>
            <h2 class="font-headline text-4xl md:text-5xl font-black text-white mb-4"><?php esc_html_e( 'What Visitors Say', 'visit-roanoke' ); ?></h2>
            <p class="text-white/60 text-lg max-w-2xl mx-auto font-body"><?php esc_html_e( 'Do not just take our word for it. Here is what people are saying about Roanoke.', 'visit-roanoke' ); ?></p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="card-frame reveal">
                <div class="corner-accent corner-tl accent-orange"></div>
                <div class="corner-accent corner-br accent-blue"></div>
                <div class="testimonial-card">
                    <div class="quote-mark">&ldquo;</div>
                    <p class="font-body"><?php esc_html_e( 'We drove up from Dallas for dinner and ended up staying the whole weekend. The food scene here is incredible for such a small town. Every restaurant we tried was amazing!', 'visit-roanoke' ); ?></p>
                    <div class="testimonial-author">
                        <div class="avatar">JM</div>
                        <div>
                            <div class="name font-headline"><?php esc_html_e( 'Jessica Martinez', 'visit-roanoke' ); ?></div>
                            <div class="location font-body"><?php esc_html_e( 'Dallas, TX', 'visit-roanoke' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-frame reveal reveal-delay-1">
                <div class="corner-accent corner-tl accent-blue"></div>
                <div class="corner-accent corner-br accent-burnt"></div>
                <div class="testimonial-card">
                    <div class="quote-mark">&ldquo;</div>
                    <p class="font-body"><?php esc_html_e( 'Celebrate Roanoke was the highlight of our fall. The community spirit, the live music, the food vendors -- it felt like a true small-town Texas festival. We are coming back next year for sure.', 'visit-roanoke' ); ?></p>
                    <div class="testimonial-author">
                        <div class="avatar">RT</div>
                        <div>
                            <div class="name font-headline"><?php esc_html_e( 'Robert Thompson', 'visit-roanoke' ); ?></div>
                            <div class="location font-body"><?php esc_html_e( 'Austin, TX', 'visit-roanoke' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-frame reveal reveal-delay-2">
                <div class="corner-accent corner-tl accent-burnt"></div>
                <div class="corner-accent corner-br accent-orange"></div>
                <div class="testimonial-card">
                    <div class="quote-mark">&ldquo;</div>
                    <p class="font-body"><?php esc_html_e( 'I have lived in Roanoke for 15 years and watched it grow into something truly special. The downtown dining scene rivals anything in the Metroplex, but with genuine small-town charm you cannot fake.', 'visit-roanoke' ); ?></p>
                    <div class="testimonial-author">
                        <div class="avatar">SL</div>
                        <div>
                            <div class="name font-headline"><?php esc_html_e( 'Sarah Lawson', 'visit-roanoke' ); ?></div>
                            <div class="location font-body"><?php esc_html_e( 'Roanoke, TX', 'visit-roanoke' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==================== FEATURED EXPERIENCE ==================== -->
<section class="featured-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="card-frame featured-img-wrap">
                <div class="corner-accent corner-tl accent-orange"></div>
                <div class="corner-accent corner-br accent-blue"></div>
                <div class="corner-accent corner-tr accent-burnt"></div>
                <div class="corner-accent corner-bl accent-navy"></div>
                <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/featured-dining.jpg' ); ?>" alt="<?php esc_attr_e( 'Featured Experience', 'visit-roanoke' ); ?>" class="relative z-10" style="height: 450px;">
            </div>

            <div class="reveal">
                <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-3 block"><?php esc_html_e( 'Experience', 'visit-roanoke' ); ?></span>
                <h2 class="font-headline text-4xl md:text-5xl font-black text-navy mb-5 leading-[1.1]"><?php esc_html_e( 'The Unique Dining Capital of Texas', 'visit-roanoke' ); ?></h2>
                <p class="text-navy/70 mb-6 leading-relaxed text-lg font-body">
                    <?php esc_html_e( "From craft breweries to upscale steakhouses, Roanoke's dining scene is unlike anywhere else in the Metroplex. Explore our walkable downtown packed with local flavor, community pride, and energetic event culture.', 'visit-roanoke" ); ?>
                </p>
                <p class="text-navy/60 mb-8 leading-relaxed font-body">
                    <?php esc_html_e( 'With over 41 unique restaurants in our compact downtown, every meal is an adventure. Whether you are craving Texas BBQ, authentic Mexican, or innovative fusion cuisine, Roanoke delivers big flavors with small-town hospitality.', 'visit-roanoke' ); ?>
                </p>
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'dining' ) ) ); ?>" class="inline-flex items-center bg-navy text-white px-8 py-3.5 rounded-sm font-headline font-bold text-sm uppercase tracking-wider hover:bg-orange transition group shadow-lg">
                    <?php esc_html_e( 'Explore Dining', 'visit-roanoke' ); ?>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== EAT / PLAY / STAY ==================== -->
<section class="experience-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12 reveal">
            <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-3 block"><?php esc_html_e( 'Explore', 'visit-roanoke' ); ?></span>
            <h2 class="font-headline text-4xl md:text-5xl font-black text-white mb-4"><?php esc_html_e( 'Experience Roanoke!', 'visit-roanoke' ); ?></h2>
            <p class="text-white/60 text-lg max-w-2xl mx-auto font-body"><?php esc_html_e( 'Whether you are here for a day or a weekend, there is something for everyone in the Unique Dining Capital of Texas.', 'visit-roanoke' ); ?></p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="card-frame reveal">
                <div class="corner-accent corner-tl accent-orange"></div>
                <div class="corner-accent corner-br accent-blue"></div>
                <div class="exp-card">
                    <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/experience/dine.jpg' ); ?>" alt="<?php esc_attr_e( 'Dine', 'visit-roanoke' ); ?>">
                    <div class="overlay"></div>
                    <div class="content">
                        <span class="tag font-headline"><?php esc_html_e( 'Dine', 'visit-roanoke' ); ?></span>
                        <h3 class="font-headline"><?php esc_html_e( 'Over 41 Unique Restaurants', 'visit-roanoke' ); ?></h3>
                    </div>
                </div>
            </div>

            <div class="card-frame reveal reveal-delay-1">
                <div class="corner-accent corner-tl accent-blue"></div>
                <div class="corner-accent corner-br accent-burnt"></div>
                <div class="exp-card">
                    <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/experience/play.jpg' ); ?>" alt="<?php esc_attr_e( 'Play', 'visit-roanoke' ); ?>">
                    <div class="overlay"></div>
                    <div class="content">
                        <span class="tag font-headline"><?php esc_html_e( 'Play', 'visit-roanoke' ); ?></span>
                        <h3 class="font-headline"><?php esc_html_e( 'Adventure Awaits', 'visit-roanoke' ); ?></h3>
                    </div>
                </div>
            </div>

            <div class="card-frame reveal reveal-delay-2">
                <div class="corner-accent corner-tl accent-burnt"></div>
                <div class="corner-accent corner-br accent-orange"></div>
                <div class="exp-card">
                    <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/experience/stay.jpg' ); ?>" alt="<?php esc_attr_e( 'Stay', 'visit-roanoke' ); ?>">
                    <div class="overlay"></div>
                    <div class="content">
                        <span class="tag font-headline"><?php esc_html_e( 'Stay', 'visit-roanoke' ); ?></span>
                        <h3 class="font-headline"><?php esc_html_e( 'Comfortable Hotels', 'visit-roanoke' ); ?></h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==================== NEWS / BLOG ==================== -->
<section class="news-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10 reveal">
            <div>
                <span class="text-orange text-xs font-headline font-black tracking-[0.2em] uppercase mb-2 block"><?php esc_html_e( 'Latest', 'visit-roanoke' ); ?></span>
                <h2 class="text-navy text-3xl md:text-4xl font-headline font-black tracking-wide"><?php esc_html_e( 'News & Stories', 'visit-roanoke' ); ?></h2>
            </div>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-roanoke-blue text-sm font-headline font-bold uppercase tracking-wide hover:text-burnt-orange transition underline underline-offset-4 decoration-2">
                <?php esc_html_e( 'View All Stories', 'visit-roanoke' ); ?>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <?php
            $news = new WP_Query( array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            if ( $news->have_posts() ) :
                $delay = 0;
                while ( $news->have_posts() ) : $news->the_post();
                    $categories = get_the_category();
                    $category   = ! empty( $categories ) ? $categories[0]->name : __( 'News', 'visit-roanoke' );
                    $delay_class = $delay > 0 ? ' reveal-delay-' . $delay : '';
            ?>
                <div class="card-frame reveal<?php echo esc_attr( $delay_class ); ?>">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-blue"></div>
                    <div class="news-card">
                        <div class="news-img">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'roanoke-card', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/news-placeholder.jpg' ); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <span class="news-category font-headline"><?php echo esc_html( $category ); ?></span>
                        </div>
                        <div class="news-body">
                            <div class="news-date font-headline"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></div>
                            <h4 class="font-headline"><?php the_title(); ?></h4>
                            <p class="font-body"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="read-more font-headline">
                                <?php esc_html_e( 'Read More', 'visit-roanoke' ); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
                    $delay = min( $delay + 1, 2 );
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback static news
            ?>
                <div class="card-frame reveal">
                    <div class="corner-accent corner-tl accent-orange"></div>
                    <div class="corner-accent corner-br accent-blue"></div>
                    <div class="news-card">
                        <div class="news-img">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/news/taste-tunes.jpg' ); ?>" alt="<?php esc_attr_e( 'Taste and Tunes', 'visit-roanoke' ); ?>">
                            <span class="news-category font-headline"><?php esc_html_e( 'Events', 'visit-roanoke' ); ?></span>
                        </div>
                        <div class="news-body">
                            <div class="news-date font-headline"><?php esc_html_e( 'August 10, 2026', 'visit-roanoke' ); ?></div>
                            <h4 class="font-headline"><?php esc_html_e( 'Taste & Tunes Returns This October', 'visit-roanoke' ); ?></h4>
                            <p class="font-body"><?php esc_html_e( 'The annual food and music festival is back bigger than ever. Get ready for live performances from local bands and food from over 20 downtown restaurants.', 'visit-roanoke' ); ?></p>
                            <a href="#" class="read-more font-headline">
                                <?php esc_html_e( 'Read More', 'visit-roanoke' ); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-1">
                    <div class="corner-accent corner-tl accent-blue"></div>
                    <div class="corner-accent corner-br accent-burnt"></div>
                    <div class="news-card">
                        <div class="news-img">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/news/city-update.jpg' ); ?>" alt="<?php esc_attr_e( 'City Update', 'visit-roanoke' ); ?>">
                            <span class="news-category font-headline"><?php esc_html_e( 'City News', 'visit-roanoke' ); ?></span>
                        </div>
                        <div class="news-body">
                            <div class="news-date font-headline"><?php esc_html_e( 'July 28, 2026', 'visit-roanoke' ); ?></div>
                            <h4 class="font-headline"><?php esc_html_e( 'New Downtown Development Breaks Ground', 'visit-roanoke' ); ?></h4>
                            <p class="font-body"><?php esc_html_e( 'A major mixed-use development project promises to bring new retail, dining, and residential options to the heart of historic downtown Roanoke.', 'visit-roanoke' ); ?></p>
                            <a href="#" class="read-more font-headline">
                                <?php esc_html_e( 'Read More', 'visit-roanoke' ); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-frame reveal reveal-delay-2">
                    <div class="corner-accent corner-tl accent-burnt"></div>
                    <div class="corner-accent corner-br accent-orange"></div>
                    <div class="news-card">
                        <div class="news-img">
                            <img src="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/images/news/travel-guide.jpg' ); ?>" alt="<?php esc_attr_e( 'Travel Guide', 'visit-roanoke' ); ?>">
                            <span class="news-category font-headline"><?php esc_html_e( 'Travel', 'visit-roanoke' ); ?></span>
                        </div>
                        <div class="news-body">
                            <div class="news-date font-headline"><?php esc_html_e( 'July 15, 2026', 'visit-roanoke' ); ?></div>
                            <h4 class="font-headline"><?php esc_html_e( 'Weekend Getaway: The Perfect 48 Hours', 'visit-roanoke' ); ?></h4>
                            <p class="font-body"><?php esc_html_e( 'From sunrise coffee to late-night BBQ, here is your complete itinerary for an unforgettable weekend in the Unique Dining Capital of Texas.', 'visit-roanoke' ); ?></p>
                            <a href="#" class="read-more font-headline">
                                <?php esc_html_e( 'Read More', 'visit-roanoke' ); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- ==================== PLAN YOUR VISIT CTA ==================== -->
<section class="plan-cta-section">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="font-headline text-3xl md:text-5xl font-black text-white mb-4"><?php esc_html_e( 'Ready to Visit Roanoke?', 'visit-roanoke' ); ?></h2>
        <p class="text-white/80 text-lg mb-8 font-body max-w-2xl mx-auto"><?php esc_html_e( 'Start planning your trip today. Download our visitor guide, check hotel availability, and build your perfect itinerary.', 'visit-roanoke' ); ?></p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo esc_url( VISIT_ROANOKE_URI . '/assets/downloads/visitor-guide.pdf' ); ?>" class="plan-cta-btn font-headline" download>
                <?php esc_html_e( 'Download Visitor Guide', 'visit-roanoke' ); ?>
            </a>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'hotels' ) ) ); ?>" class="plan-cta-btn font-headline" style="background: transparent; border: 3px solid white; color: white;">
                <?php esc_html_e( 'View Hotels', 'visit-roanoke' ); ?>
            </a>
        </div>
    </div>
</section>

<!-- ==================== NEWSLETTER ==================== -->
<!-- <section class="newsletter-section">
    <div class="deco-shape deco-circle deco-orange float-slow" style="top: 20%; left: 5%; width: 40px; height: 40px; opacity: 0.2;"></div>
    <div class="deco-shape deco-square deco-blue float-medium" style="bottom: 20%; right: 8%; width: 50px; height: 50px; opacity: 0.15; transform: rotate(20deg);"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="font-headline text-3xl md:text-4xl font-black text-white mb-3"><?php esc_html_e( 'Stay in the Loop', 'visit-roanoke' ); ?></h2>
        <p class="text-white/70 mb-8 text-lg font-body"><?php esc_html_e( 'Get the latest events, dining news, and trip ideas delivered to your inbox.', 'visit-roanoke' ); ?></p>

        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto mb-10">
            <?php wp_nonce_field( 'roanoke_newsletter', 'roanoke_newsletter_nonce' ); ?>
            <input type="hidden" name="action" value="roanoke_newsletter_signup">
            <input type="email" name="subscriber_email" placeholder="<?php esc_attr_e( 'Your Email Address', 'visit-roanoke' ); ?>" required class="newsletter-input font-body">
            <button type="submit" class="newsletter-btn font-headline"><?php esc_html_e( 'Subscribe', 'visit-roanoke' ); ?></button>
        </form>

        <div class="flex justify-center space-x-4">
            <?php
            $socials = array( 'facebook', 'instagram', 'youtube' );
            foreach ( $socials as $social ) {
                $url = visit_roanoke_get_social_link( $social );
                if ( $url ) {
                    visit_roanoke_social_icon( $social, $url );
                }
            }
            ?>
        </div>
    </div>
</section> -->

<script>
(function() {
    'use strict';

    // Hero Slider
    var slides = document.querySelectorAll('.hero-slide');
    var currentIndex = 0;

    function showSlide(index) {
        slides.forEach(function(slide, i) {
            slide.classList.remove('active');
            slide.style.animation = 'none';
            if (i === index) {
                slide.offsetHeight; // force reflow
                slide.classList.add('active');
                slide.style.animation = '';
            }
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }

    if (slides.length > 1) {
        setInterval(nextSlide, 6000);
    }
    showSlide(0);

    // Scroll Reveal
    var reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        reveals.forEach(function(el) {
            revealObserver.observe(el);
        });
    } else {
        // Fallback for older browsers
        reveals.forEach(function(el) {
            el.classList.add('visible');
        });
    }
})();
</script>

<?php
get_footer();