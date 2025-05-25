<?php
/**
 * Plugin Name: AI Product Recommendation Plugin

 * Description: MVP product recommendation system using ChatGPT. Provides a shortcode for displaying recommendations.
 * Version: 1.0.0
 * Author: OpenAI Codex
 * Requires Plugins: WooCommerce
 * Text Domain: ai-prp
 * Domain Path: /languages
 *
 * Example usage of shortcode: [ai_product_recommendations]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';

/**
 * Initialize the plugin.
 */
/**
 * Display admin notice if WooCommerce is missing.
 */
function ai_prp_wc_missing_notice() {
    echo '<div class="error"><p>' . esc_html__( 'AI Product Recommendation Plugin requires WooCommerce to be installed and active.', 'ai-prp' ) . '</p></div>';
}

/**
 * Initialize the plugin after ensuring WooCommerce is active.
 */
function ai_prp_init() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'ai_prp_wc_missing_notice' );
        return;
    }

    new AI_Product_Recommendation_Plugin();
}
add_action( 'plugins_loaded', 'ai_prp_init' );



if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';