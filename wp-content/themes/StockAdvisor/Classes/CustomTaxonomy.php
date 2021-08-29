<?php

namespace Classes;

class CustomTaxonomy
{
    private $type;
    private $slug;
    private $name;
    private $singular_name;
    private $args;
    private $labels;
    private $associatedCPT;

    public function __construct(string $type, string $slug, string $name, string $singular_name, array $associatedCPT, ?array $args = [])
    {
        $this->type             = $type;
        $this->slug             = $slug;
        $this->name             = $name;
        $this->singular_name    = $singular_name;
        $this->associatedCPT    = $associatedCPT;
        $this->setLabels();
        $this->setArgs($args);

        add_action( 'init', [$this, 'register'], 0 );
    }

    public function register()
    {
        register_taxonomy( $this->slug, $this->associatedCPT, $this->args );
    }
    private function setLabels()
    {
        $this->labels = array(
            'name'                  => $this->name,
            'singular_name'         => $this->singular_name,
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New '   . $this->singular_name,
            'edit_item'             => 'Edit '      . $this->singular_name,
            'new_item'              => 'New '       . $this->singular_name,
            'all_items'             => 'All '       . $this->name,
            'view_item'             => 'View '      . $this->name,
            'search_items'          => 'Search '    . $this->name,
            'not_found'             => 'No '        . strtolower($this->name) . ' found',
            'not_found_in_trash'    => 'No '        . strtolower($this->name) . ' found in Trash',
            'parent_item_colon'     => '',
            'menu_name'             => $this->name
        );
    }

    private function setArgs(?array $args)
    {
        $argsDefault = array(
            'labels'                => $this->labels,
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'show_in_nav_menus'     => true,
            'show_tagcloud'         => true,
            'show_in_rest'          => true,
        );

        $this->args = array_replace($argsDefault, $args);
    }
}
