<?php
/**
 * The main template file
 */
get_header(); ?>

<main id="main-content" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="mb-12">
                    <h2 class="font-headline text-2xl font-bold text-navy mb-2">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="text-navy/70 font-body">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p class="text-navy/60">No posts found.</p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>