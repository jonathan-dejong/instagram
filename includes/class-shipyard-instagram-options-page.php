<?php

final class Shipyard_Instagram_Options_Page {

    /**
     * Public instance of the class.
     */
    public static $instance;

    /**
     * @var string Name of the option key in the database.
     */
    public $option_key = 'shipyard-instagram';

    /**
     * @var string Name of the settings section.
     */
    public $setting = 'shipyard-instgram-settings-group';


    /**
     * Creates or returns an instance of this class.
     *
     * @return A single instance of this class.
     */
    public static function get() {
        if ( self::$instance === null ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Class constructor.
     *
     * Register the settings and menu option.
     */
    private function __construct() {
        add_action( 'admin_menu', array( $this, 'register_admin_menu_item' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_notices', array( $this, 'maybe_add_admin_notice' ) );
    }


    /**
     * Get a setting by key.
     *
     * @param string $key Array key.
     *
     * @return mixed Value of the setting.
     */
    public function get_setting( $key ) {
        $settings = (array) get_option( $this->option_key );
        if ( isset( $settings[ $key ] ) ) {
            return $settings[ $key ];
        }

        return '';
    }


    public function maybe_add_admin_notice() {
        if ( empty( $this->get_setting( 'hashtag' ) ) ) : ?>
            <div class="update-nag"><p><?php
                printf(
                    __( 'The Instagram pluing %sneeds to be configured%s!', 'shipyard-instagram' ),
                    '<a href="' . admin_url( 'options-general.php?page=shipyard-instagram' ) . '">',
                    '</a>'
                ); ?>
        </p></div>
        <?php endif;
    }


    /***************************************

     * Settings API callbacks below follow *

     ***************************************/


    public function register_admin_menu_item() {
        add_options_page( __( 'Instagram', 'shipyard-instagram' ), __( 'Instagram', 'shipyard-instagram' ), 'manage_options', 'shipyard-instagram', array( $this, 'render_options_page' ) );
    }

    public function register_settings() {
        register_setting( $this->setting, $this->option_key );
        add_settings_section( 'section-one', __( 'Configure Settings', 'shipyard-instagram' ), array( $this, 'section_one_callback' ), 'shipyard-instagram' );
        add_settings_field( 'field-one', __( 'Title', 'shipyard-instagram' ), array( $this, 'field_one_callback' ), 'shipyard-instagram', 'section-one' );
        add_settings_field( 'field-two', __( 'Hashtag', 'shipyard-instagram' ), array( $this, 'field_two_callback' ), 'shipyard-instagram', 'section-one' );
    }

    public function section_one_callback() {
    }

    public function field_one_callback() { ?>
        <input type="text" class="regular-text" name="<?php echo $this->option_key; ?>[title]" value="<?php echo $this->get_setting( 'title' ); ?>">
    <?php }

    public function field_two_callback() { ?>
        <input type="text" class="regular-text" name="<?php echo $this->option_key; ?>[hashtag]" value="<?php echo $this->get_setting( 'hashtag' ); ?>">
    <?php }


    public function render_options_page() { ?>
        <div class="wrap">
            <h2><?php _e( 'Shipyard Instagram', 'shipyard-instagram' ); ?></h2>
            <form action="options.php" method="POST">
                <?php settings_fields( $this->setting ); ?>
                <?php do_settings_sections( 'shipyard-instagram' ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php }

}

