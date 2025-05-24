<?php
/**
 * Plugin Name: AI Product Recommendation Plugin
 * Description: MVP product recommendation system using ChatGPT. Provides a shortcode for displaying recommendations.
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

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';

/**
 * Initialize the plugin.
 */
function ai_prp_init() {
    new AI_Product_Recommendation_Plugin();
}
add_action( 'plugins_loaded', 'ai_prp_init' );
