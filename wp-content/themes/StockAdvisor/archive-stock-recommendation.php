<?php
get_header(); ?>

<div class="wrap">

    <?php if ( have_posts() ) : ?>
        <header class="page-header">
            <?php
            the_archive_title( '<h1 class="page-title">', '</h1>' );
            the_archive_description( '<div class="taxonomy-description">', '</div>' );
            ?>
        </header><!-- .page-header -->
    <?php endif; ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php
            if ( have_posts() ) :
                ?>
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    $ticketOutput = '';
                    if (class_exists('ACF')) {
                        $tickers = get_the_terms(get_the_ID(), 'stocks');
                        if($tickers) {
                            foreach ($tickers as $ticker) {
                                $exchange = get_field('exchange', $ticker->taxonomy . '_' . $ticker->term_id);
                                $symbol = get_field('symbol', $ticker->taxonomy . '_' . $ticker->term_id);
                                if ($exchange and $symbol) {
                                    if (!empty($ticketOutput)) {
                                        $ticketOutput .= ', ';
                                    }
                                    $ticketOutput .= $exchange . ':' . $symbol;
                                }
                            }
                        }
                    }
                    the_date('F j, Y',);
                    $after = empty($ticketOutput) ? '</a></h3>' : ' ('. $ticketOutput .')</a></h3>';
                    the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', $after  );

                endwhile;

                the_posts_pagination(
                    array(
                        'prev_text'          => '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
                        'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>',
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
                    )
                );

            else :

                ?><h3>No Stock Recommendation Posts</h3><?php

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
