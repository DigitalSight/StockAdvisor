<?php

use Classes\Theme;

require dirname( __FILE__ ) . '/Classes/Theme.php';

$theme = new Theme();

//Enqueue Styles
$theme->addStyle('parent-theme-styles', get_template_directory_uri() . '/style.css');

//function change_slug_struct( $query ) {
//
//    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
//        return;
//    }
//
//    if ( ! empty( $query->query['name'] ) ) {
//        $query->set( 'post_type', array( 'post', 'single-link', 'page' ) );
//    } elseif ( ! empty( $query->query['pagename'] ) && false === strpos( $query->query['pagename'], '/' ) ) {
//        $query->set( 'post_type', array( 'post', 'single-link', 'page' ) );
//
//        // We also need to set the name query var since redirect_guess_404_permalink() relies on it.
//        $query->set( 'name', $query->query['pagename'] );
//    }
//}
//add_action( 'pre_get_posts', 'change_slug_struct' );
