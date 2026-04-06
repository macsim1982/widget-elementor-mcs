<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Booking_list_Widget extends MCS_Widget_Base {

    public function get_name() {
        return 'booking_list';
    }

    public function get_title() {
        return 'Booking List';
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return [ 'mcs-category' ];
    }

    protected function register_controls() {

        // SECTION CONTENT
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Content',
            ]
        );

        // TITLE
        $this->add_control(
            'title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'My Title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        // DESCRIPTION
        $this->add_control(
            'description',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => 'Description Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        // FORMS MULTISELECT
        $this->add_control(
            'forms',
            [
                'label' => 'Select Forms',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_forms_options(),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get forms list (example - adapt depending on plugin used)
     */
    private function get_forms_options() {
        $forms = [];

        // Exemple avec posts type "form"
        $query = get_posts([
            'post_type' => 'form',
            'numberposts' => -1
        ]);

        foreach ($query as $form) {
            $forms[$form->ID] = $form->post_title;
        }

        return $forms;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // var_dump($settings); // Debug settings values

        $forms = $settings['forms'];

        if (empty($forms)) {
            return;
        }

        // QUERY POSTS LINKED TO FORMS
        $args = [
            'post_type' => 'booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'linked_form', // ACF / meta field
                    'value' => $forms,
                    'compare' => 'IN'
                ]
            ]
        ];

        $query = new WP_Query($args);

        // BUILD ARRAY OF VALUES FOR FRONT
        $data = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $data[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'form_id' => get_post_meta(get_the_ID(), 'linked_form', true),
                ];
            }
        }

        wp_reset_postdata();
        ?>

        <div class="custom-widget">

            <h2 class="custom-title"><?php echo esc_html($settings['title']); ?></h2>
            <p class="custom-description"><?php echo esc_html($settings['description']); ?></p>

            <div class="custom-posts">
                <?php

                // fallback rendering
                foreach ($data as $item) {
                ?>
                    <div class="booking-item">
                        <h3><a href="<?php echo esc_url($item['link']); ?>"><?php echo esc_html($item['title']); ?></a></h3>
                        <p>Form ID: <?php echo esc_html($item['form_id']); ?></p>
                        with data: <?php var_dump($item); ?>
                    </div>
                <?php
                }
                ?>
            </div>

        </div>

        <script>
            // DATA usable in front JS
            const customWidgetData = <?php echo json_encode($data); ?>;
            console.log('Forms linked posts:', customWidgetData);
        </script>

        <?php
    }
}