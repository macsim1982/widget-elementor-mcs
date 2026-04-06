<?php
use \Elementor\Core\DynamicTags\Tag;
use \Elementor\Modules\DynamicTags\Module;
use \Elementor\Controls_Manager;

class MCS_Dynamic_Tag extends Tag {

    public function get_name() {
        return 'mcs-field';
    }

    public function get_title() {
        return 'MCS Field';
    }

    public function get_group() {
        return 'site';
    }

    public function get_categories() {
        return [ Module::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'key',
            [
                'label' => 'Key',
                'type' => Controls_Manager::TEXT,
            ]
        );
    }

    public function render() {
        global $mcs_current_item;

        $key = $this->get_settings('key');

        if (!$mcs_current_item || empty($key)) {
            return;
        }

        if (isset($mcs_current_item[$key])) {
            echo wp_kses_post($mcs_current_item[$key]);
        }
    }
}