<?php
get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
            <?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
			?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php  echo get_the_title(); ?></h1>
                        <div class="entry-author"><?php echo get_the_author_meta('display_name'); ?></div>
                        <div class="entry-date"><?php echo get_the_date('F j, Y'); ?></div>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; /* Start the Loop */ ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php get_sidebar(); ?>
    </div><!-- .wrap -->

<?php
get_footer();
