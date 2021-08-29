<?php
include_once dirname( __FILE__ ) . '/Classes/Clients/FMPClient.php';

get_header();
//echo var_dump(get_post_type(['name' => 'stock-recommendation', 'public' => false], 'names', 'not'));
//foreach ( get_post_types( ['name' => 'stock-recommendation', 'public' => false], 'names', 'not' ) as $post_type ) {
//    echo '<p>' . $post_type . '</p>';
//}
?>

<div class="wrap">

<?php
if (!class_exists('ACF')) {
?><h3>Page Not Available</h3><?php
    return;
}
if ( have_posts() ) : ?>
    <header class="page-header">
        <?php
        $ticker = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

        $recommendArgs = [
            'posts_per_page' => 10,
            'post_type' => 'stock-recommendation',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $ticker->taxonomy,
                    'field'    => 'slug',
                    'terms'    => $ticker->slug, // which'd be `horror` or `comedy`, etc
                ),
            )
        ];

        $newsArgs = $recommendArgs;
        $newsArgs['post_type'] = 'news';
        $pageNumber = get_query_var( 'paged' );
        $newsArgs['paged'] = $pageNumber;
//        echo var_dump(get_post_type());

        if($pageNumber > 1) {
//            var_dump($newsArgs);
            $newsArgs['post_type'] = get_post_types( ['name' => 'stock-recommendation', 'public' => false], 'names', 'not' );
        }

        $exchange = get_field('exchange', $ticker->taxonomy . '_' . $ticker->term_id);
        $symbol = get_field('symbol', $ticker->taxonomy . '_' . $ticker->term_id);

        $client = new FMPClient();
        $profile = $client->getCompanyProfile($symbol);
        $quote = $client->getCompanyQuote($symbol);

        if(count($profile) > 0) {
            $profile = reset($profile);
            ?><img src="<?php echo $profile["image"]; ?>" /><?php
        ?><h1 class="page-title"><?php echo $profile["companyName"]; ?></h1>
        <div class="taxonomy-description"><?php echo $profile["description"]; ?></div><?php
        }

        ?>
    </header><!-- .page-header -->
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <div class="entry-content">
                <h2>Financial Data</h2>
                <?php
                if(count($quote) > 0) {
                    $quote = reset($quote);
                    ?>
                    <table>
                        <tr>
                            <td>Price</td>
                            <td><?php echo $quote["price"] ?></td>
                        </tr>
                        <tr>
                            <td>Price Change</td>
                            <td><?php echo $quote["change"] ?></td>
                        </tr>
                        <tr>
                            <td>Price Change Percentage</td>
                            <td><?php echo $quote["changesPercentage"] * 100 ?>%</td>
                        </tr>
                        <tr>
                            <td>Price Change Percentage</td>
                            <td><?php echo $quote["changesPercentage"] * 100 ?>%</td>
                        </tr>
                        <tr>
                            <td>52 Week Range</td>
                            <td><?php echo $quote["yearHigh"] ?> - <?php echo $quote["yearLow"] ?> </td>
                        </tr>
                        <tr>
                            <td>Beta</td>
                            <td><?php echo $profile["beta"] ?? ''; ?></td>
                        </tr>
                        <tr>
                            <td>Volume Average</td>
                            <td><?php echo number_format($quote["avgVolume"]) ?></td>
                        </tr>
                        <tr>
                            <td>Market Capitalisation</td>
                            <td><?php echo number_format($quote["marketCap"]) ?></td>
                        </tr>
                        <tr>
                            <td>Last Dividend</td>
                            <td><?php echo $profile["lastDiv"] ?? 'N/A'; ?></td>
                        </tr>
                    </table>
                    <?php
                }
                ?>

            </div>

            <div class="secondary-content">
                <?php
                $recommendQuery = new WP_Query($recommendArgs);
                if($recommendQuery->have_posts()) {
                    ?><h2>Recommendations</h2>
                    <ul class="recommendation-articles">
                    <?php
                    while($recommendQuery->have_posts()) : $recommendQuery->the_post();
                        ?><li><a href="<?php echo get_the_permalink()?>"><?php echo get_the_title() ?></a></li><?php
                    endwhile;
                    ?>
                    </ul>
                    <?php
                }
                ?>
            </div>

            <div class="secondary-content">
                <?php
                $newsQuery = new WP_Query($newsArgs);
                if($newsQuery->have_posts()) {
                    ?><h2>Other Coverage</h2>
                    <ul class="recommendation-articles">
                        <?php
                        while($newsQuery->have_posts()) : $newsQuery->the_post();
                            ?><li><a href="<?php echo get_the_permalink()?>"><?php echo get_the_title() ?></a></li><?php
                        endwhile;
                        ?>
                    </ul>
                    <?php
                    the_posts_pagination(
                        array(
                            'prev_text'          => '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
                            'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>',
                            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
                        )
                    );
                }
                ?>
            </div>


        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->
    <?php endif;


    ?>


