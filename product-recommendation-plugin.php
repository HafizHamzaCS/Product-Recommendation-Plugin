<?php
/*
Plugin Name: Product Recommendation Plugin
Description: MVP product recommendation system. Registers a Product custom post type and provides shortcode for recommendations by category.
Version: 1.0.0
Author: OpenAI Codex
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Register custom post type
function prp_register_product_cpt() {
    $labels = array(
        'name'               => __( 'Products', 'prp' ),
        'singular_name'      => __( 'Product', 'prp' ),
        'add_new'            => __( 'Add New Product', 'prp' ),
        'add_new_item'       => __( 'Add New Product', 'prp' ),
        'edit_item'          => __( 'Edit Product', 'prp' ),
        'new_item'           => __( 'New Product', 'prp' ),
        'all_items'          => __( 'All Products', 'prp' ),
        'view_item'          => __( 'View Product', 'prp' ),
        'search_items'       => __( 'Search Products', 'prp' ),
        'not_found'          => __( 'No products found', 'prp' ),
        'not_found_in_trash' => __( 'No products found in Trash', 'prp' ),
        'menu_name'          => __( 'Products', 'prp' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'supports'           => array( 'title', 'editor' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'product', $args );
}
add_action( 'init', 'prp_register_product_cpt' );

// Add meta boxes for product fields
function prp_add_product_metaboxes() {
    add_meta_box( 'prp_product_name', __( 'Product Name', 'prp' ), 'prp_product_name_callback', 'product', 'normal', 'default' );
    add_meta_box( 'prp_product_category', __( 'Product Category', 'prp' ), 'prp_product_category_callback', 'product', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'prp_add_product_metaboxes' );

function prp_product_name_callback( $post ) {
    $value = get_post_meta( $post->ID, '_prp_product_name', true );
    echo '<input type="text" name="prp_product_name" value="' . esc_attr( $value ) . '" style="width:100%;" />';
}

function prp_product_category_callback( $post ) {
    $value = get_post_meta( $post->ID, '_prp_product_category', true );
    echo '<input type="text" name="prp_product_category" value="' . esc_attr( $value ) . '" style="width:100%;" />';
}

// Save meta box data
function prp_save_product_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['prp_product_name'] ) ) {
        update_post_meta( $post_id, '_prp_product_name', sanitize_text_field( $_POST['prp_product_name'] ) );
    }

    if ( isset( $_POST['prp_product_category'] ) ) {
        update_post_meta( $post_id, '_prp_product_category', sanitize_text_field( $_POST['prp_product_category'] ) );
    }
}
add_action( 'save_post_product', 'prp_save_product_meta' );

// Shortcode for displaying product recommendations by category
function prp_product_recommendations_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'category' => '',
    ), $atts, 'product_recommendations' );

    if ( empty( $atts['category'] ) ) {
        return '';
    }

    $args = array(
        'post_type'  => 'product',
        'meta_key'   => '_prp_product_category',
        'meta_value' => sanitize_text_field( $atts['category'] ),
        'posts_per_page' => -1,
    );

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<p>No product recommendations found.</p>';
    }

    $output = '<ul class="prp-recommendations">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $name = get_post_meta( get_the_ID(), '_prp_product_name', true );
        $output .= '<li>' . esc_html( $name ? $name : get_the_title() ) . '</li>';
    }
    $output .= '</ul>';

    wp_reset_postdata();
    return $output;
}
add_shortcode( 'product_recommendations', 'prp_product_recommendations_shortcode' );

?>
