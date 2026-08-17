<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Visit_Roanoke
 */

?>

    </div><!-- #content -->

    <!-- ==================== FOOTER ==================== -->
    <footer class="site-footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <?php if ( has_custom_logo() ) : ?>
                            <div class="custom-logo-link flex-shrink-0">
                                <?php 
                                // Output logo with white filter for dark footer background
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
                                if ( $logo ) :
                                ?>
                                    <img src="<?php echo esc_url( $logo[0] ); ?>" 
                                         alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" 
                                         class="h-10 w-auto object-contain"
                                         >
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="w-8 h-8 bg-orange rounded-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <!-- <div class="text-white font-headline font-bold text-lg leading-tight">Roanoke</div> -->
                    </div>
                    <p class="text-roanoke-blue-200 text-sm leading-relaxed font-body">Small town charm. Big Texas experiences.</p>
                    <p class="text-orange text-xs font-headline font-bold uppercase tracking-widest mt-2">The Unique Dining Capital of Texas</p>
                </div>

                <!-- Explore -->
                <div>
                    <h4 class="font-headline font-bold text-sm mb-5 text-white uppercase tracking-wider"><?php _e( 'Explore', 'visit-roanoke' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-explore',
                        'container'      => false,
                        'items_wrap'     => '<ul class="space-y-3 text-roanoke-blue-200 text-sm font-body">%3$s</ul>',
                        'fallback_cb'    => function() {
                            echo '<ul class="space-y-3 text-roanoke-blue-200 text-sm font-body">
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Things to Do', 'visit-roanoke' ) . '</a></li>
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Dining', 'visit-roanoke' ) . '</a></li>
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Events', 'visit-roanoke' ) . '</a></li>
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Hotels', 'visit-roanoke' ) . '</a></li>
                            </ul>';
                        },
                        'link_before'    => '<span class="hover:text-orange transition">',
                        'link_after'     => '</span>',
                    ) );
                    ?>
                </div>

                <!-- Plan -->
                <div>
                    <h4 class="font-headline font-bold text-sm mb-5 text-white uppercase tracking-wider"><?php _e( 'Plan', 'visit-roanoke' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-plan',
                        'container'      => false,
                        'items_wrap'     => '<ul class="space-y-3 text-roanoke-blue-200 text-sm font-body">%3$s</ul>',
                        'fallback_cb'    => function() {
                            echo '<ul class="space-y-3 text-roanoke-blue-200 text-sm font-body">
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Visitor Guide', 'visit-roanoke' ) . '</a></li>
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Maps', 'visit-roanoke' ) . '</a></li>
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Itineraries', 'visit-roanoke' ) . '</a></li>
                                <li><a href="#" class="hover:text-orange transition">' . __( 'Contact Us', 'visit-roanoke' ) . '</a></li>
                            </ul>';
                        },
                        'link_before'    => '<span class="hover:text-orange transition">',
                        'link_after'     => '</span>',
                    ) );
                    ?>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-headline font-bold text-sm mb-5 text-white uppercase tracking-wider"><?php _e( 'Contact', 'visit-roanoke' ); ?></h4>
                    <p class="text-roanoke-blue-200 text-sm mb-2 font-body"><?php _e( 'City of Roanoke, Texas', 'visit-roanoke' ); ?></p>
                    <p class="text-roanoke-blue-200 text-sm mb-2 font-body">308 S. Walnut Street</p>
                    <p class="text-roanoke-blue-200 text-sm mb-2 font-body">Roanoke, TX 76262</p>
                    <p class="text-roanoke-blue-200 text-sm font-body">(817) 491-2411</p>
                </div>
            </div>

            <!-- Partners Section (Dashboard Editable) -->
            <?php if ( get_theme_mod( 'show_footer_partners', true ) ) : 
                $partners_count = absint( get_theme_mod( 'partners_count', 3 ) );
                $has_partners = false;
                
                // Check if any partner images exist
                for ( $i = 1; $i <= $partners_count; $i++ ) {
                    if ( get_theme_mod( "partner_{$i}_image" ) ) {
                        $has_partners = true;
                        break;
                    }
                }
            ?>
            <div class="border-t border-navy-400 pt-8">
                <p class="text-roanoke-blue-300 text-xs text-center mb-5 font-headline font-bold uppercase tracking-[0.2em]">
                    <?php echo esc_html( get_theme_mod( 'partners_heading', 'Proud Partners' ) ); ?>
                </p>
                <div class="flex justify-center flex-wrap gap-4 md:gap-8">
                    <?php for ( $i = 1; $i <= $partners_count; $i++ ) : 
                        $img = get_theme_mod( "partner_{$i}_image" );
                        $url = get_theme_mod( "partner_{$i}_url", '' );
                        $name = get_theme_mod( "partner_{$i}_name", __( 'Partner', 'visit-roanoke' ) . ' ' . $i );
                        
                        if ( $img ) :
                            $tag = $url ? 'a' : 'div';
                            $attrs = $url ? 'href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                    <<?php echo $tag; ?> <?php echo $attrs; ?> class="w-28 h-12 bg-white/10 rounded-sm flex items-center justify-center border border-white/10 hover:bg-white/20 transition overflow-hidden">
                        <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="max-w-full max-h-full object-contain p-1">
                    </<?php echo $tag; ?>>
                    <?php else : ?>
                    <div class="w-28 h-12 bg-white/10 rounded-sm flex items-center justify-center text-roanoke-blue-300 text-xs font-headline font-medium border border-white/10">
                        [<?php echo esc_html( $name ); ?>]
                    </div>
                    <?php endif; endfor; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="mt-10 text-center text-roanoke-blue-400 text-xs font-body">
                &copy; <?php echo date('Y'); ?> <?php _e( 'City of Roanoke, Texas. All rights reserved.', 'visit-roanoke' ); ?>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>