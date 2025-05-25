<?php
/**
 * Plugin Name: AI WooCommerce Product Recommendation
 * Description: Generates WooCommerce product suggestions using ChatGPT. Provides a shortcode for displaying recommendations.
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
 * Initialize the plugin after ensuring WooCommerce is active.
 */
function ai_prp_init() {
    if ( ! ai_prp_check_woocommerce() ) {
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
    // For WooCommerce submenu, the settings page is usually under admin.php?page=ai-prp
    $url     = admin_url( 'admin.php?page=ai-prp' );
    $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'ai-prp' ) . '</a>';
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ai_prp_settings_link' );
