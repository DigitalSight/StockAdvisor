<?php

namespace Classes;

class CustomPostType
{
    private $type;
    private $slug;
    private $name;
    private $singular_name;
    private $args;
    private $labels;

    public function __construct(string $type, string $slug, string $name, string $singular_name, ?array $args = [])
    {
        $this->type             = $type;
        $this->slug             = $slug;
        $this->name             = $name;
        $this->singular_name    = $singular_name;
        $this->setLabels();
        $this->setArgs($args);

        add_action('init', array($this, 'register'));
        add_filter( 'manage_edit-'.$this->type.'_columns',        array($this, 'set_columns'), 10, 1) ;
        add_action( 'manage_'.$this->type.'_posts_custom_column', array($this, 'edit_columns'), 10, 2 );
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
            'public'                => true,
            'publicly_queryable'    => true,
            'rewrite'               => array( 'slug' => $this->slug , 'with_front' => false ),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => true,
            'menu_position'         => 8,
            'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail'),
        );

        $this->args = array_replace($argsDefault, $args);
    }

    public function register()
    {
        register_post_type( $this->type, $this->args );
        flush_rewrite_rules();
    }

    public function set_columns($columns) {
        // Set/unset post type table columns here

        return $columns;
    }

    public function edit_columns($column, $post_id) {
        // Post type table column content code here
    }
}
