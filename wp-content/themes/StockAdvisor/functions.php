<?php

use Classes\Theme;

require dirname( __FILE__ ) . '/Classes/Theme.php';

$theme = new Theme();

//Enqueue Styles
$theme->addStyle('parent-theme-styles', get_template_directory_uri() . '/style.css');
$theme->addStyle('theme-styles', get_stylesheet());
