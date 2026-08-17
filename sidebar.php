<?php
/**
 * The sidebar for regular pages
 */
if ( ! is_active_sidebar( 'page-sidebar' ) ) {
    return;
}
?>
<<aside class="widget-area space-y-8" role="complementary">
    <?php dynamic_sidebar( 'page-sidebar' ); ?>
</aside>