<?php
use Elementor\Controls_Manager;

class Mon_Widget_Elementor extends MCS_Widget_Base {
    protected $slug = 'mon-widget';
    
    public function get_name() {
        return 'mon_widget';
    }

    public function get_title() {
        return 'Mon Widget Custom';
    }

    public function get_icon() {
        return 'eicon-code';
    }

    /**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return [ 'mcs-category' ];
	}

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Contenu',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => 'Titre',
                'type' => Controls_Manager::TEXT,
                'default' => 'Hello Elementor',
            ]
        );

        $this->add_control(
            'color_title',
            [
                'label' => 'Couleur du titre',
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .mon-widget h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_background',
            [
                'label' => 'Couleur de fond',
                'type' => Controls_Manager::COLOR,
                'default' => '#EEEEEE',
                'selectors' => [
                    '{{WRAPPER}} .mon-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        ?>
        <div class="mon-widget">
            <h2><?php echo esc_html($settings['title']); ?></h2>
            <div class="mon-widget-canvas"></div>
        </div>
        <?php
    }
}
