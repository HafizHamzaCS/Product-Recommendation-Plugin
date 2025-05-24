<?php
/**
 * Plugin Name: AI Product Recommendation Plugin
 * Description: WooCommerce product recommendation system using ChatGPT. Provides a shortcode for displaying recommendations.
 * Version: 1.1.0
 * Author: OpenAI Codex
 * Text Domain: ai-prp
 * Domain Path: /languages
 *
 * Requires: WooCommerce
 * Example usage of shortcode: [ai_product_recommendations]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';

/**
 * Initialize the plugin.
 */
function ai_prp_init() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'ai_prp_missing_wc_notice' );
        return;
    }

    new AI_Product_Recommendation_Plugin();
}

/**
 * Display admin notice if WooCommerce is not active.
 */
function ai_prp_missing_wc_notice() {
    echo '<div class="error"><p>' . esc_html__( 'AI Product Recommendation Plugin requires WooCommerce to be installed and active.', 'ai-prp' ) . '</p></div>';
}

add_action( 'plugins_loaded', 'ai_prp_init', 20 );
