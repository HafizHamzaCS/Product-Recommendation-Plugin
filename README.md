# AI Product Recommendation Plugin

This plugin provides an MVP product recommendation system powered by OpenAI's ChatGPT API. It requires WooCommerce to be active because recommendations are based on WooCommerce products.

## Features

- Shortcode `[ai_product_recommendations]` generates personalized product suggestions.
- Settings page to configure the OpenAI API key, prompt template, and number of products to display.
- Recommendations are displayed in a responsive list.
- Requires WooCommerce to be installed and active.

## Usage

1. Copy the plugin files to your WordPress `wp-content/plugins` directory.
2. Ensure that WooCommerce is installed and active.
3. Activate **AI Product Recommendation Plugin** from the admin Plugins screen.
4. Visit **Settings > AI Recommendations** to enter your API key and customize the prompt.
5. Insert the shortcode into any post or page.

The prompt template supports `{preferences}` and `{count}` placeholders which will be replaced with user preference data and the number of products to show.
