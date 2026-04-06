<?php

use Elementor\Controls_Manager;

class Booking_List_Widget extends MCS_Widget_Base {

    protected string $slug = 'booking-list-widget';

    public function get_name(): string {
        return 'booking_list_widget';
    }

    public function get_title(): string {
        return esc_html__( 'Booking List', 'widget-elementor-mcs' );
    }

    public function get_icon(): string {
        return 'eicon-calendar';
    }

    public function get_categories(): array {
        return [ 'mcs-category' ];
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'widget-elementor-mcs' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'widget-elementor-mcs' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'My Title', 'widget-elementor-mcs' ),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'widget-elementor-mcs' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'widget-elementor-mcs' ),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'widget-elementor-mcs' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'forms',
            [
                'label' => esc_html__( 'Select Forms', 'widget-elementor-mcs' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_forms_options(),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function get_forms_options(): array {
        $forms = [];

        $query = get_posts([
            'post_type' => 'form',
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        foreach ( $query as $form ) {
            $forms[ $form->ID ] = get_the_title( $form );
        }

        return $forms;
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        $forms = $settings['forms'] ?? [];

        if ( empty( $forms ) ) {
            echo '<p>' . esc_html__( 'Aucun formulaire sélectionné.', 'widget-elementor-mcs' ) . '</p>';
            return;
        }

        $args = [
            'post_type' => 'booking',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'linked_form',
                    'value' => $forms,
                    'compare' => 'IN',
                ],
            ],
        ];

        $query = new WP_Query( $args );
        $data = [];

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $data[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'form_id' => get_post_meta( get_the_ID(), 'linked_form', true ),
                ];
            }
        }

        wp_reset_postdata();
        ?>
        <div class="custom-widget">
            <h2 class="custom-title"><?php echo esc_html( $settings['title'] ); ?></h2>
            <p class="custom-description"><?php echo esc_html( $settings['description'] ); ?></p>

            <?php if ( empty( $data ) ) : ?>
                <p><?php echo esc_html__( 'Aucun booking trouvé pour les formulaires sélectionnés.', 'widget-elementor-mcs' ); ?></p>
            <?php else : ?>
                <div class="custom-posts">
                    <?php foreach ( $data as $item ) : ?>
                        <div class="booking-item">
                            <h3><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
                            <p><?php echo esc_html__( 'Form ID:', 'widget-elementor-mcs' ) . ' ' . esc_html( $item['form_id'] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}