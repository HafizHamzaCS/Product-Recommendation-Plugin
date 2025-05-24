# AI Product Recommendation Plugin

This plugin provides an MVP product recommendation system powered by OpenAI's ChatGPT API and integrated with WooCommerce.

## Features

- Shortcode `[ai_product_recommendations]` generates personalized product suggestions from your WooCommerce catalog.
- Settings page to configure the OpenAI API key, prompt template, and number of products to display.
- Recommendations are displayed in a responsive list.

## Usage

1. Copy the plugin files to your WordPress `wp-content/plugins` directory and ensure WooCommerce is installed.
2. Activate **AI Product Recommendation Plugin** from the admin Plugins screen.
3. Visit **Settings > AI Recommendations** to enter your API key and customize the prompt.
4. Insert the shortcode into any post or page.

The prompt template supports `{preferences}`, `{count}`, and `{products}` placeholders which will be replaced with user preference data, the number of products to show, and a list of available products.
