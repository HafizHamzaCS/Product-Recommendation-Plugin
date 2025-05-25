<?php
/**
 * Main plugin class for AI Product Recommendation Plugin.
 *
 * @package AI_PRP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AI Product Recommendation Plugin class.
 */
class AI_Product_Recommendation_Plugin {

    /**
     * Option name for storing settings.
     *
     * @var string
     */
    private $option_name = 'ai_prp_settings';

    /**
     * Constructor. Hooks into WordPress actions.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'init', array( $this, 'register_shortcode' ) );
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    /**
     * Load plugin textdomain for translations.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'ai-prp', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages' );
    }

    /**
     * Register the shortcode.
     */
    public function register_shortcode() {
        add_shortcode( 'ai_product_recommendations', array( $this, 'shortcode_callback' ) );
    }

    /**
     * Enqueue front-end assets.
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'ai-prp-style', plugins_url( '../assets/css/ai-product-recommendation.css', __FILE__ ), array(), '1.0.0' );
    }

    /**
     * Add settings page under the Settings menu.
     */
    public function add_settings_page() {
        add_options_page(
            __( 'AI Recommendations', 'ai-prp' ),
            __( 'AI Recommendations', 'ai-prp' ),
            'manage_options',
            'ai-prp',
            array( $this, 'settings_page' )
        );
    }

    /**
     * Register plugin settings using the Settings API.
     */
    public function register_settings() {
        register_setting(
            'ai_prp_settings',
            $this->option_name,
            array( $this, 'sanitize_settings' )
        );

        add_settings_section(
            'ai_prp_main',
            __( 'API Settings', 'ai-prp' ),
            '__return_false',
            'ai_prp'
        );

        add_settings_field( 'api_key', __( 'OpenAI API Key', 'ai-prp' ), array( $this, 'field_api_key' ), 'ai_prp', 'ai_prp_main' );
        add_settings_field( 'prompt', __( 'Prompt Template', 'ai-prp' ), array( $this, 'field_prompt' ), 'ai_prp', 'ai_prp_main' );
        add_settings_field( 'count', __( 'Number of Products', 'ai-prp' ), array( $this, 'field_count' ), 'ai_prp', 'ai_prp_main' );
    }

    /**
     * Sanitize settings before saving.
     *
     * @param array $input Raw input values.
     * @return array Sanitized values.
     */
    public function sanitize_settings( $input ) {
        $new              = array();
        $new['api_key']   = isset( $input['api_key'] ) ? sanitize_text_field( $input['api_key'] ) : '';
        $new['prompt']    = isset( $input['prompt'] ) ? sanitize_textarea_field( $input['prompt'] ) : '';
        $new['count']     = isset( $input['count'] ) ? absint( $input['count'] ) : 3;
        return $new;
    }

    /**
     * Render API key field.
     */
    public function field_api_key() {
        $options = get_option( $this->option_name );
        ?>
        <input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[api_key]" value="<?php echo isset( $options['api_key'] ) ? esc_attr( $options['api_key'] ) : ''; ?>" class="regular-text" />
        <?php
    }

    /**
     * Render prompt template field.
     */
    public function field_prompt() {
        $options = get_option( $this->option_name );
        ?>
        <textarea name="<?php echo esc_attr( $this->option_name ); ?>[prompt]" rows="5" cols="50" class="large-text"><?php echo isset( $options['prompt'] ) ? esc_textarea( $options['prompt'] ) : ''; ?></textarea>

        <p class="description"><?php esc_html_e( 'Use {preferences}, {count}, and {products} placeholders.', 'ai-prp' ); ?></p>

        <?php
    }

    /**
     * Render count field.
     */
    public function field_count() {
        $options = get_option( $this->option_name );
        ?>
        <input type="number" min="1" max="10" name="<?php echo esc_attr( $this->option_name ); ?>[count]" value="<?php echo isset( $options['count'] ) ? absint( $options['count'] ) : 3; ?>" />
        <?php
    }

    /**
     * Display settings page content.
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AI Product Recommendations', 'ai-prp' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'ai_prp_settings' );
                do_settings_sections( 'ai_prp' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Shortcode callback to display product recommendations.
     *
     * @return string HTML output.
     */

    public function shortcode_callback() {
        $options = get_option( $this->option_name );
        $count   = isset( $options['count'] ) ? absint( $options['count'] ) : 3;
        $prompt  = isset( $options['prompt'] ) ? $options['prompt'] : '';

        $preferences = '';
        if ( ! empty( $_COOKIE['ai_prp_preferences'] ) ) {
            $preferences = sanitize_text_field( wp_unslash( $_COOKIE['ai_prp_preferences'] ) );
        }

        if ( is_user_logged_in() ) {
            $user_pref = get_user_meta( get_current_user_id(), 'ai_prp_preferences', true );
            if ( $user_pref ) {
                $preferences .= ' ' . sanitize_text_field( $user_pref );
            }
        }


        if ( empty( $prompt ) ) {
            $prompt = __( 'Recommend {count} products for a user interested in {preferences}. Return only product names separated by commas.', 'ai-prp' );
        }

        $prompt = str_replace( array( '{count}', '{preferences}' ), array( $count, $preferences ), $prompt );

        $recommendations = $this->fetch_recommendations( $prompt, isset( $options['api_key'] ) ? $options['api_key'] : '' );
        if ( is_wp_error( $recommendations ) ) {
            return '';
        }

        $items  = array_map( 'trim', explode( ',', $recommendations ) );
        $output = '<ul class="ai-prp-list">';
        foreach ( $items as $item ) {

            if ( '' !== $item ) {


            $product = get_page_by_title( $item, OBJECT, 'product' );
            if ( $product ) {
                $output .= '<li><a href="' . esc_url( get_permalink( $product ) ) . '">' . esc_html( get_the_title( $product ) ) . '</a></li>';
            } else {

            if ( '' !== $item ) {


                $output .= '<li>' . esc_html( $item ) . '</li>';
            }
        }
        $output .= '</ul>';

        return $output;
    }

    /**
     * Call OpenAI API to fetch recommendations.
     *
     * @param string $prompt  Prompt to send to the API.
     * @param string $api_key API key.
     * @return string|WP_Error Response string or error.
     */
    private function fetch_recommendations( $prompt, $api_key ) {
        if ( empty( $api_key ) ) {
            return new WP_Error( 'no_api_key', __( 'API key not set.', 'ai-prp' ) );
        }

        $args = array(
            'body'    => wp_json_encode(
                array(
                    'model'       => 'gpt-3.5-turbo',
                    'messages'    => array(
                        array(
                            'role'    => 'user',
                            'content' => $prompt,
                        ),
                    ),
                    'temperature' => 0.7,
                )
            ),
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
            ),
            'timeout' => 30,
        );

        $response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', $args );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return new WP_Error( 'api_error', __( 'Unexpected API response.', 'ai-prp' ) );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( empty( $body['choices'][0]['message']['content'] ) ) {
            return new WP_Error( 'api_error', __( 'Invalid API response.', 'ai-prp' ) );
        }

        return $body['choices'][0]['message']['content'];
    }
}
