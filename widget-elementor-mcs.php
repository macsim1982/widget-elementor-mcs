<?php
/**
 * Plugin Name: Elementor MCS Widgets
 */

if (!defined('ABSPATH')) exit;


class MCS_Elementor_Widgets {

    private $widgets = [
        'mon-widget' => [
            'class' => 'Mon_Widget_Elementor',
            'file' => 'class-mon-widget.php',
        ],
        'list-widget' => [
            'class' => 'List_Widget_Elementor',
            'file' => 'class-list-widget.php',
        ],
        'booking-list-widget' => [
            'class' => 'Booking_list_Widget',
            'file' => 'class-booking-list-widget.php',
        ],
    ];

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {

        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>Plugin nécessite Elementor</p></div>';
            });
            return;
        }

        // Elementor
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_category'], 1, 1);
        add_action('elementor/editor/after_enqueue_styles', function() {
            echo '<style>
                #elementor-panel-categories {
                    display: flex;
                    flex-direction: column;
                }
                
                #elementor-panel-category-mcs-category {
                    order: -9999 !important;
                }
            </style>';
        });

        // add_action('elementor/dynamic_tags/register', function($dynamic_tags) {
        //     require_once __DIR__ . '/classes/class-mcs-dynamic-tag.php';
        //     $dynamic_tags->register(new \MCS_Dynamic_Tag());
        // });

        // Assets
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_assets']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_assets']);
    }

    public function register_category($elements_manager) {
        $elements_manager->add_category('mcs-category', [
            'title' => 'Mes Widgets MCS',
            'icon' => 'fa fa-plug',
        ]);
    }

    public function register_assets() {

        foreach ($this->widgets as $slug => $widget) {

            $js_file  = "assets/{$slug}-script.js";
            $css_file = "assets/{$slug}-style.css";

            $js_path  = plugin_dir_path(__FILE__) . $js_file;
            $css_path = plugin_dir_path(__FILE__) . $css_file;

            if (file_exists($js_path)) {
                wp_register_script(
                    $slug . '-script',
                    plugins_url($js_file, __FILE__),
                    [],
                    filemtime($js_path),
                    true
                );
            }

            if (file_exists($css_path)) {
                wp_register_style(
                    $slug . '-style',
                    plugins_url($css_file, __FILE__),
                    [],
                    filemtime($css_path)
                );
            }
        }
    }

    public function register_widgets($widgets_manager) {
        require_once __DIR__ . '/widgets/class-mcs-base-widget.php';

        foreach ($this->widgets as $slug => $widget) {

            $file = __DIR__ . '/widgets/' . $widget['file'];

            if (file_exists($file)) {
                require_once $file;
            }

            $js_file  = "assets/js/{$slug}.js";
            $css_file = "assets/css/{$slug}.css";

            $js_path  = plugin_dir_path(__FILE__) . $js_file;
            $css_path = plugin_dir_path(__FILE__) . $css_file;
            $class = $widget['class'];

            if (class_exists($class)) {
                $instance = new $class();

                $widgets_manager->register($instance);
            }

            if (file_exists($js_path)) {
                wp_register_script(
                    $slug . '-script',
                    plugins_url($js_file, __FILE__),
                    [],
                    filemtime($js_path),
                    true
                );
            }

            if (file_exists($css_path)) {
                wp_register_style(
                    $slug . '-style',
                    plugins_url($css_file, __FILE__),
                    [],
                    filemtime($css_path)
                );
            }
        }
    }
}

// Init
new MCS_Elementor_Widgets();