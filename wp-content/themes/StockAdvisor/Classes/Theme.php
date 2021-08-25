<?php
namespace Classes;

include_once get_template_directory() . '/Classes/CustomPostType.php';
include_once get_template_directory() . '/Classes/CustomTaxonomy.php';

class Theme
{
    public function __construct()
    {
        $this->registerCPTs();
        $this->registerTaxonomies();
    }

    private function registerCPTs()
    {
        $newsArticleCPT = new CustomPostType('news', 'news', 'News', 'News');
        $newsArticleCPT->register();

        $stockRecommendationCPT = new CustomPostType('recommendation', 'stock-recommendations', 'Stock Recommendations', 'Stock Recommendation');
        $stockRecommendationCPT->register();
    }

    private function registerTaxonomies()
    {
        new CustomTaxonomy('stocks', 'stocks', 'Stocks', 'Stock', ['news', 'recommendation']);
    }

}
