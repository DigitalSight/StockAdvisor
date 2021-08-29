<?php

use Classes\Theme;

require dirname( __FILE__ ) . '/Classes/Theme.php';

$theme = new Theme();

//Enqueue Styles
$theme->addStyle('parent-theme-styles', get_template_directory_uri() . '/style.css');


function excludeRecommendFromSecondPage( $query ) {
    if(is_tax('stocks') AND isset($query->query["paged"]) AND  $query->query["paged"] > 1) {
        echo 'hitting';
        $query->set('post_type', get_post_types( ['name' => 'stock-recommendation', 'public' => false], 'names', 'not' ));
    }
}
add_action( 'pre_get_posts', 'excludeRecommendFromSecondPage' );
