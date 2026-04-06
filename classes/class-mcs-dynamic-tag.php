<?php

class MCS_Dynamic_Tag extends \Elementor\Core\DynamicTags\Tag {

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
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'key',
            [
                'label' => 'Key',
                'type' => \Elementor\Controls_Manager::TEXT,
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