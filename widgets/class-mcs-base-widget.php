<?php 
use Elementor\Widget_Base;

abstract class MCS_Widget_Base extends Widget_Base {

    protected $slug;

    public function set_slug($slug) {
        $this->slug = $slug;
    }

    public function get_script_depends() {
        return $this->slug ? [$this->slug . '-script'] : [];
    }

    public function get_style_depends() {
        return $this->slug ? [$this->slug . '-style'] : [];
    }
}