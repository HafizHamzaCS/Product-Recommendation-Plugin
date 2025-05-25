# AI WooCommerce Product Recommendation Plugin

This plugin provides an MVP product recommendation system for WooCommerce powered by OpenAI's ChatGPT API.

## Features

- Works only when WooCommerce is active
- Shortcode `[ai_product_recommendations]` shows personalized product suggestions
- Settings page to configure the OpenAI API key, prompt template, and number of items to display
- Recommended products are matched against your WooCommerce catalog and linked

## Usage

1. Copy the plugin files to your WordPress `wp-content/plugins` directory.
2. Activate **AI WooCommerce Product Recommendation Plugin** from the Plugins screen.
3. Go to **WooCommerce > AI Recommendations** to enter your API key and configure the prompt.
4. Insert the shortcode in any post or page.

The prompt template supports `{preferences}` and `{count}` placeholders which are replaced with user preferences and the number of products to display.
