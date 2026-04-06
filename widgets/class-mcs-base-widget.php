<?php

use Elementor\Widget_Base;

abstract class MCS_Widget_Base extends Widget_Base {

    protected string $slug = '';

    public function set_slug( string $slug ): void {
        $this->slug = $slug;
    }

    public function get_script_depends(): array {
        return $this->slug ? [sprintf( 'mcs-%s-script', $this->slug )] : [];
    }

    public function get_style_depends(): array {
        return $this->slug ? [sprintf( 'mcs-%s-style', $this->slug )] : [];
    }
}