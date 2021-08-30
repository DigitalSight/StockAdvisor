<?php
include_once dirname( __FILE__ ) . '/Classes/Clients/FMPClient.php';

get_header();
?>

<div class="wrap">

<?php
if (!class_exists('ACF')) {
?><h3>Page Not Available</h3><?php
    return;
} ?>

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

        if($pageNumber > 1) {
            $newsArgs['post_type'] = get_post_types( ['name' => 'stock-recommendation', 'public' => false], 'names', 'not' );
        }

        $exchange = get_field('exchange', $ticker->taxonomy . '_' . $ticker->term_id);
        $symbol = get_field('symbol', $ticker->taxonomy . '_' . $ticker->term_id);

        $client = new FMPClient();
        $profile = $client->getCompanyProfile($symbol);
        $quote = $client->getCompanyQuote($symbol);

        if(!is_wp_error($profile) AND count($profile) > 0) {
            $profile = reset($profile);
            ?><img src="<?php echo $profile["image"]; ?>" /><?php
        ?><h1 class="page-title"><?php echo $profile["companyName"]; ?></h1><?php

        } else {
            ?><h1 class="page-title"><?php echo $ticker->name; ?> Information is not available</h1><?php
        }

        ?>
    </header><!-- .page-header -->
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <p class="entry-content">
                <p><?php echo $profile["description"] ?? ''; ?></p>
                <?php
                if(!is_wp_error($quote) AND count($quote) > 0) {
                    $quote = reset($quote);
                    ?>
                    <h2>Financial Data</h2>
                    <table>
                        <tr>
                            <td class="bold-title">Price</td>
                            <td><?php echo $quote["price"] ?></td>
                        </tr>
                        <tr>
                            <td class="bold-title">Price Change</td>
                            <td><?php echo $quote["change"] ?></td>
                        </tr>
                        <tr>
                            <td class="bold-title">Price Change Percentage</td>
                            <td><?php echo $quote["changesPercentage"] * 100 ?>%</td>
                        </tr>
                        <tr>
                            <td class="bold-title">Price Change Percentage</td>
                            <td><?php echo $quote["changesPercentage"] * 100 ?>%</td>
                        </tr>
                        <tr>
                            <td class="bold-title">52 Week Range</td>
                            <td><?php echo $quote["yearHigh"] ?> - <?php echo $quote["yearLow"] ?> </td>
                        </tr>
                        <tr>
                            <td class="bold-title">Beta</td>
                            <td><?php echo $profile["beta"] ?? ''; ?></td>
                        </tr>
                        <tr>
                            <td class="bold-title">Volume Average</td>
                            <td><?php echo number_format($quote["avgVolume"]) ?></td>
                        </tr>
                        <tr>
                            <td class="bold-title">Market Capitalisation</td>
                            <td><?php echo number_format($quote["marketCap"]) ?></td>
                        </tr>
                        <tr>
                            <td class="bold-title">Last Dividend</td>
                            <td><?php echo $profile["lastDiv"] ?? 'N/A'; ?></td>
                        </tr>
                    </table>
                    <?php
                }
                ?>
            <hr />
            <?php
            $recommendQuery = new WP_Query($recommendArgs);
            if($recommendQuery->have_posts()) {
                ?><h2>Recommendations</h2>
                <ul class="more-content-list">
                    <?php
                    while($recommendQuery->have_posts()) : $recommendQuery->the_post();
                        ?><li><h3 class="entry-title"><a href="<?php echo get_the_permalink()?>"><?php echo get_the_title() ?></a></h3></li><?php
                    endwhile;
                    ?>
                </ul>
                <?php
            }
            ?>
            <hr />
            <?php
            $newsQuery = new WP_Query($newsArgs);
            if($newsQuery->have_posts()) {
                ?><h2>Other Coverage</h2>
                <ul class="more-content-list">
                    <?php
                    while($newsQuery->have_posts()) : $newsQuery->the_post();
                        ?><li><h3 class="entry-title"><a href="<?php echo get_the_permalink()?>"><?php echo get_the_title() ?></a></h3></li><?php
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


