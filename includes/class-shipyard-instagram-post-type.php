<?php

final class Shipyard_Instagram_Post_Type {

    /**
     * Public instance of the class.
     */
    public static $instance;

    /**
     * @var string Post type.
     */
    public $post_type = 'instagram-image';

    /**
     * Creates or returns an instance of this class.
     *
     * @return A single instance of this class.
     */
    public static function get() {
        if ( self::$instance === null ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Class constructor.
     */
    private function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }


    public function register_post_type() {
        register_post_type( $this->post_type, array(
            'labels'            => array(
                'name'                => __( 'Instagram images', 'shipyard-instagram' ),
                'singular_name'       => __( 'Instagram image', 'shipyard-instagram' ),
                'all_items'           => __( 'Instagram images', 'shipyard-instagram' ),
                'new_item'            => __( 'New instagram image', 'shipyard-instagram' ),
                'add_new'             => __( 'Add New', 'shipyard-instagram' ),
                'add_new_item'        => __( 'Add New instagram image', 'shipyard-instagram' ),
                'edit_item'           => __( 'Edit instagram image', 'shipyard-instagram' ),
                'view_item'           => __( 'View instagram image', 'shipyard-instagram' ),
                'search_items'        => __( 'Search instagram images', 'shipyard-instagram' ),
                'not_found'           => __( 'No instagram images found', 'shipyard-instagram' ),
                'not_found_in_trash'  => __( 'No instagram images found in trash', 'shipyard-instagram' ),
                'parent_item_colon'   => __( 'Parent instagram image', 'shipyard-instagram' ),
                'menu_name'           => __( 'Instagram images', 'shipyard-instagram' ),
            ),
            'public'            => true,
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'supports'          => array( 'title' ),
            'has_archive'       => false,
            'rewrite'           => false,
            'query_var'         => true,
            'menu_icon'         => 'dashicons-format-image',
        ) );

    }


    /**
     * Get the image post obejcts.
     *
     * @param int $posts_per_page Num posts per page to display.
     *
     * @return WP_Query instance.
     */
    public function get_images( $posts_per_page = 9 ) {
        return new WP_Query( array(
            'post_type'              => $this->post_type,
            'posts_per_page'         => absint( $posts_per_page ),
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
        ) );
    }

}

