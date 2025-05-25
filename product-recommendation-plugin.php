<?php
/**
 * Plugin Name: AI Product Recommendation Plugin
 * Description: MVP product recommendation system using ChatGPT. Provides a shortcode for displaying recommendations.
 * Version: 1.0.0
 * Author: OpenAI Codex
 * Description: MVP product recommendation system using ChatGPT. Provides a shortcode for displaying recommendations.
 * Version: 1.0.0
 * Author: OpenAI Codex
 * Requires Plugins: WooCommerce
 * Text Domain: ai-prp
 * Domain Path: /languages
 *
 * Example usage of shortcode: [ai_product_recommendations]
 * Requires Plugins: WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';

/**
 * Show an admin notice if WooCommerce is not active.
 */
function ai_prp_wc_missing_notice() {
    echo '<div class="notice notice-error"><p>' . esc_html__( 'AI Product Recommendation Plugin requires WooCommerce to be installed and active.', 'ai-prp' ) . '</p></div>';
}

/**
 * Check for WooCommerce dependency.
 *
 * @return bool Whether WooCommerce is active.
 */
function ai_prp_check_woocommerce() {
    if ( class_exists( 'WooCommerce' ) ) {
        return true;
    }

    add_action( 'admin_notices', 'ai_prp_wc_missing_notice' );
    return false;
}

/**
 * Initialize the plugin.
 */
function ai_prp_init() {
    if ( ! ai_prp_check_woocommerce() ) {
=======
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

/**
 * Add a Settings link on the Plugins page.
 *
 * @param string[] $links Existing links.
 * @return string[] Modified links.
 */
function ai_prp_action_links( $links ) {
    $url = admin_url( 'options-general.php?page=ai-prp' );
    $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'ai-prp' ) . '</a>';
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ai_prp_action_links' );


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-product-recommendation.php';
