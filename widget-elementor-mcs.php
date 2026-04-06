<?php
/**
 * Plugin Name: Elementor MCS Widgets
 * Plugin URI:  https://example.com/
 * Description: Custom Elementor widgets and dynamic tags for MCS.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com/
 * Text Domain: widget-elementor-mcs
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class MCS_Elementor_Widgets {

    public const VERSION = '1.0.0';
    public const TEXT_DOMAIN = 'widget-elementor-mcs';

    private bool $assets_registered = false;

    private array $widgets = [
        'mon-widget' => [
            'class' => 'Mon_Widget_Elementor',
            'file'  => 'class-mon-widget.php',
        ],
        'list-widget' => [
            'class' => 'List_Widget_Elementor',
            'file'  => 'class-list-widget.php',
        ],
        'booking-list-widget' => [
            'class' => 'Booking_List_Widget',
            'file'  => 'class-booking-list-widget.php',
        ],
    ];

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'bootstrap' ] );
    }

    public function bootstrap(): void {
        if ( did_action( 'elementor/loaded' ) ) {
            $this->init();
            return;
        }

        add_action( 'elementor/loaded', [ $this, 'init' ] );
        add_action( 'admin_notices', [ $this, 'display_elementor_missing_notice' ] );
    }

    public function init(): void {
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
        add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_frontend_assets' ] );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_frontend_assets' ] );
    }

    public function display_elementor_missing_notice(): void {
        if ( did_action( 'elementor/loaded' ) ) {
            return;
        }

        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            esc_html__( 'Le plugin Elementor MCS Widgets nécessite Elementor pour fonctionner.', self::TEXT_DOMAIN )
        );
    }

    public function register_category( $elements_manager ): void {
        $elements_manager->add_category(
            'mcs-category',
            [
                'title' => esc_html__( 'Mes Widgets MCS', self::TEXT_DOMAIN ),
                'icon'  => 'fa fa-plug',
            ]
        );
    }

    public function enqueue_editor_styles(): void {
        $custom_css = '
            #elementor-panel-categories { display: flex; flex-direction: column; }
            #elementor-panel-category-mcs-category { order: -9999 !important; }
        ';

        if ( wp_style_is( 'elementor-editor', 'enqueued' ) ) {
            wp_add_inline_style( 'elementor-editor', $custom_css );
            return;
        }

        echo '<style>' . wp_strip_all_tags( $custom_css ) . '</style>';
    }

    public function register_dynamic_tags( $dynamic_tags ): void {
        $tag_file = __DIR__ . '/classes/class-mcs-dynamic-tag.php';

        if ( file_exists( $tag_file ) ) {
            require_once $tag_file;
        }

        if ( class_exists( 'MCS_Dynamic_Tag' ) ) {
            $dynamic_tags->register( new \MCS_Dynamic_Tag() );
        }
    }

    public function register_frontend_assets(): void {
        if ( $this->assets_registered ) {
            return;
        }

        $this->assets_registered = true;

        foreach ( $this->widgets as $slug => $widget ) {
            $js_file  = "assets/js/{$slug}.js";
            $css_file = "assets/css/{$slug}.css";

            $js_path  = plugin_dir_path( __FILE__ ) . $js_file;
            $css_path = plugin_dir_path( __FILE__ ) . $css_file;

            if ( file_exists( $js_path ) ) {
                wp_register_script(
                    $this->get_asset_handle( $slug, 'script' ),
                    plugins_url( $js_file, __FILE__ ),
                    [],
                    filemtime( $js_path ),
                    true
                );
            }

            if ( file_exists( $css_path ) ) {
                wp_register_style(
                    $this->get_asset_handle( $slug, 'style' ),
                    plugins_url( $css_file, __FILE__ ),
                    [],
                    filemtime( $css_path )
                );
            }
        }
    }

    public function register_widgets( $widgets_manager ): void {
        require_once __DIR__ . '/widgets/class-mcs-base-widget.php';

        foreach ( $this->widgets as $slug => $widget ) {
            $file = __DIR__ . '/widgets/' . $widget['file'];

            if ( ! file_exists( $file ) ) {
                continue;
            }

            require_once $file;
            $class = $widget['class'];

            if ( ! class_exists( $class ) ) {
                continue;
            }

            $instance = new $class();

            if ( method_exists( $instance, 'set_slug' ) ) {
                $instance->set_slug( $slug );
            }

            $widgets_manager->register( $instance );
        }
    }

    private function get_asset_handle( string $slug, string $type ): string {
        return sprintf( 'mcs-%s-%s', $slug, $type );
    }
}

new MCS_Elementor_Widgets();