<?php
/**
 * Plugin Name: AI WooCommerce Product Recommendation
 * Description: Generates WooCommerce product suggestions using ChatGPT.
 * Version: 1.0.0
 * Author: OpenAI Codex
 * Text Domain: ai-prp
 * Domain Path: /languages
 *
 * Example usage of shortcode: [ai_product_recommendations]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check WooCommerce dependency and display admin notice if missing.
 */
function ai_prp_wc_check() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="error"><p>' . esc_html__( 'AI WooCommerce Product Recommendation requires WooCommerce to be installed and active.', 'ai-prp' ) . '</p></div>';
        } );
        return false;
    }
    return true;
}

/**
 * Initialize the plugin when WooCommerce is active.
 */
function ai_prp_init() {
    if ( ! ai_prp_wc_check() ) {
        return;
    }

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';
    new AI_Product_Recommendation_Plugin();
}
add_action( 'plugins_loaded', 'ai_prp_init' );

/**
 * Add settings link on the Plugins screen.
 *
 * @param array $links Existing action links.
 * @return array Modified links.
 */
function ai_prp_settings_link( $links ) {
    $url     = admin_url( 'admin.php?page=ai-prp' );
    $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'ai-prp' ) . '</a>';
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ai_prp_settings_link' );
