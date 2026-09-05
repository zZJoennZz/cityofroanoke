<?php
/**
 * The template for displaying the footer
 *
 * @package Visit_Roanoke
 */
?>

<!-- ==================== NEWSLETTER ==================== -->
<section class="newsletter-section">
        <div class="deco-shape deco-circle deco-orange float-slow" style="top: 20%; left: 5%; width: 40px; height: 40px; opacity: 0.2;"></div>
        <div class="deco-shape deco-square deco-blue float-medium" style="bottom: 20%; right: 8%; width: 50px; height: 50px; opacity: 0.15; transform: rotate(20deg);"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            
            <?php
            // Get customizer values
            $newsletter_title = get_theme_mod( 'newsletter_title', '' );
            $newsletter_description = get_theme_mod( 'newsletter_description', '' );
            $newsletter_embed_code = get_theme_mod( 'newsletter_embed_code', '' );
            $newsletter_enabled = get_theme_mod( 'newsletter_enabled', true );

            // Check if newsletter is enabled
            if ( $newsletter_enabled ) :

                // Check if required fields are filled (title and form action)
                $has_required = ! empty( $newsletter_title );
                $show_admin_notice = current_user_can( 'manage_options' ) && ! $has_required;

                // If no title or form action, show admin notice (only to admins)
                if ( $show_admin_notice ) :
                ?>
                    <!-- Admin Notice: Configure Newsletter -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left" style="background: #fefce8; border-color: #facc15;">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-yellow-800"><?php _e( 'Newsletter Configuration Required', 'visit-roanoke' ); ?></h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p><?php _e( 'The newsletter section is not fully configured. Please add the required fields in the WordPress Customizer.', 'visit-roanoke' ); ?></p>
                                    <p class="mt-2">
                                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="font-medium text-yellow-800 underline hover:text-yellow-900">
                                            <?php _e( 'Go to Customizer → Newsletter Section', 'visit-roanoke' ); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Display Newsletter Content (only if title exists) -->
                <?php if ( ! empty( $newsletter_title ) ) : ?>
                    <h2 class="font-headline text-3xl md:text-4xl font-black text-white mb-3">
                        <?php echo esc_html( $newsletter_title ); ?>
                    </h2>
                <?php endif; ?>

                <?php if ( ! empty( $newsletter_description ) ) : ?>
                    <p class="text-white/70 mb-8 text-lg font-body">
                        <?php echo esc_html( $newsletter_description ); ?>
                    </p>
                <?php endif; ?>

                <!-- Newsletter Form -->
                <?php if ( ! empty( $newsletter_form_action ) ) : ?>
                    <form action="<?php echo esc_url( $newsletter_form_action ); ?>" method="post" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto mb-10">
                        <?php wp_nonce_field( 'roanoke_newsletter', 'roanoke_newsletter_nonce' ); ?>
                        <input type="hidden" name="action" value="roanoke_newsletter_signup">
                        <input type="email" name="subscriber_email" placeholder="<?php esc_attr_e( 'Your Email Address', 'visit-roanoke' ); ?>" required class="newsletter-input font-body flex-1">
                        <button type="submit" class="newsletter-btn font-headline">
                            <?php echo ! empty( $newsletter_button_text ) ? esc_html( $newsletter_button_text ) : esc_html__( 'Subscribe', 'visit-roanoke' ); ?>
                        </button>
                    </form>
                <?php endif; ?>

                <!-- Social Icons -->
                <div class="flex justify-center space-x-4">
                    <?php
                    $socials = array( 'facebook', 'instagram', 'youtube', 'twitter' );
                    foreach ( $socials as $social ) {
                        $url = visit_roanoke_get_social_link( $social );
                        if ( $url ) {
                            visit_roanoke_social_icon( $social, $url );
                        }
                    }
                    ?>
                </div>

                <!-- ✅ Custom Embed Code Area -->
                <?php if ( ! empty( $newsletter_embed_code ) ) : ?>
                    <div class="mt-8 custom-embed-area">
                        <?php echo do_shortcode( $newsletter_embed_code ); ?>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <!-- Newsletter Disabled - Show nothing -->
            <?php endif; ?>

        </div>
    </section>

<!-- ==================== FOOTER ==================== -->
<footer class="site-footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">

            <!-- Column 1: Brand -->
            <div>
                <?php if ( has_custom_logo() ) : ?>
                    <div class="mb-4">
                        <?php
                        // Output logo with white filter for dark footer background
                        $custom_logo_id = get_theme_mod( 'custom_logo' );
                        $logo           = wp_get_attachment_image_src( $custom_logo_id, 'full' );
                        if ( $logo ) :
                        ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-12 w-auto">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="inline-block mb-4">
                        <span class="font-headline text-white font-black text-xl tracking-tight"><?php bloginfo( 'name' ); ?></span>
                    </a>
                <?php endif; ?>

                <p class="text-white/50 text-sm font-body"><?php bloginfo( 'description' ); ?></p>
                <p class="text-orange text-xs font-headline font-black uppercase tracking-widest mt-2"><?php esc_html_e( 'The Unique Dining Capital of Texas', 'visit-roanoke' ); ?></p>
            </div>

            <!-- Column 2: Footer Widget 1 -->
            <div>
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                <?php else : ?>
                    <h4 class="font-headline font-black text-sm mb-4 text-white uppercase tracking-wider"><?php esc_html_e( 'Explore', 'visit-roanoke' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location'  => 'footer',
                        'container'       => false,
                        'menu_class'      => 'space-y-2.5 text-sm font-body list-none m-0 p-0',
                        'fallback_cb'     => false,
                        'depth'           => 1,
                    ) );
                    ?>
                <?php endif; ?>
            </div>

            <!-- Column 3: Footer Widget 2 -->
            <div>
                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-2' ); ?>
                <?php else : ?>
                    <h4 class="font-headline font-black text-sm mb-4 text-white uppercase tracking-wider"><?php esc_html_e( 'Plan', 'visit-roanoke' ); ?></h4>
                    <ul class="space-y-2.5 text-sm font-body list-none m-0 p-0">
                        <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'visitor-guide' ) ) ); ?>" class="footer-link"><?php esc_html_e( 'Visitor Guide', 'visit-roanoke' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'maps' ) ) ); ?>" class="footer-link"><?php esc_html_e( 'Maps', 'visit-roanoke' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'itineraries' ) ) ); ?>" class="footer-link"><?php esc_html_e( 'Itineraries', 'visit-roanoke' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="footer-link"><?php esc_html_e( 'Contact Us', 'visit-roanoke' ); ?></a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Column 4: Footer Widget 3 / Contact -->
            <div>
                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-3' ); ?>
                <?php else : ?>
                    <h4 class="font-headline font-black text-sm mb-4 text-white uppercase tracking-wider"><?php esc_html_e( 'Contact', 'visit-roanoke' ); ?></h4>
                    <address class="not-italic text-white/50 text-sm font-body space-y-1">
                        <p><?php esc_html_e( 'City of Roanoke, Texas', 'visit-roanoke' ); ?></p>
                        <p><?php esc_html_e( '308 S. Walnut Street', 'visit-roanoke' ); ?></p>
                        <p><?php esc_html_e( 'Roanoke, TX 76262', 'visit-roanoke' ); ?></p>
                        <p><a href="tel:8174912411" class="footer-link">(817) 491-2411</a></p>
                    </address>
                <?php endif; ?>
            </div>

        </div>

        <div class="border-t border-white/10 pt-8 text-center text-white/30 text-xs font-body">
            <p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'visit-roanoke' ); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>