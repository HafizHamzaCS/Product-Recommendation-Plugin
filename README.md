# AI WooCommerce Product Recommendation Plugin

This plugin provides an MVP product recommendation system for WooCommerce powered by OpenAI's ChatGPT API.

## Features

- Works only when WooCommerce is active
- Shortcode `[ai_product_recommendations]` generates personalized product suggestions
- Settings page to configure the OpenAI API key, prompt template, and number of products to display
- Recommended products are matched against your WooCommerce catalog and linked
- Recommendations are displayed in a responsive list
- A **Settings** link is available on the Plugins page for quick access

## Usage

1. Copy the plugin files to your WordPress `wp-content/plugins` directory.
2. Ensure that WooCommerce is installed and active.
3. Activate **AI WooCommerce Product Recommendation Plugin** from the Plugins screen.
4. Go to **WooCommerce > AI Recommendations** to enter your API key and configure the prompt.
5. Insert the shortcode into any post or page.

The prompt template supports `{preferences}` and `{count}` placeholders which are replaced with user preferences and the number of products to display.
