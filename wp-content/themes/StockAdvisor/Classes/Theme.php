<?php
namespace Classes;

include_once dirname( __FILE__ ) . '/CustomPostType.php';
include_once dirname( __FILE__ ) . '/CustomTaxonomy.php';
include_once dirname( __FILE__ ) . '/Widgets/CompanyProfileWidget.php';

class Theme
{
    public function __construct()
    {
        $this->registerCPTs();
        $this->registerTaxonomies();
        new \Company_Profile_Widget();

        add_action('widgets_init', [$this, 'registerCustomSidebars']);
    }

    private function registerCPTs()
    {
        $newsArticleCPT = new CustomPostType('news', 'news', 'News', 'News', ['show_in_rest'=> true]);
        $newsArticleCPT->register();

        $stockRecommendationCPT = new CustomPostType('recommendation', 'stock-recommendations', 'Stock Recommendations', 'Stock Recommendation');
        $stockRecommendationCPT->register();
    }

    private function registerTaxonomies()
    {
        new CustomTaxonomy('stocks', 'stocks', 'Stocks', 'Stock', ['news', 'recommendation']);
    }

    public function registerCustomSidebars()
    {
        register_sidebar(
            array (
                'name' => __( 'Stock Recommendation Sidebar', 'stock-recommendation-sidebar' ),
                'id' => 'stock-recommendation-sidebar',
                'description' => __( 'Sidebar that will be shown on single Stock Recommendation Posts', 'StockAdvisor' ),
                'before_widget' => '<aside id="secondary" class="sidebar widget-area" role="complementary">',
                'after_widget' => "</aside>",
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            )
        );
    }

    public function addStyle(string $handle, string $src)
    {
        add_action('wp_enqueue_scripts', function () use ($handle, $src) {
            wp_enqueue_style($handle, $src);
        });
    }

    public function addScript()
    {

    }
}
