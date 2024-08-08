<?php

function widget_initialize() {
    register_sidebar(array(
        'name' => __('Uppermost Header Bar', 'bitz'),
        'id' => 'toppest-bar',
        'description' => __('This header comes top most', 'bitz'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
}
add_action('widgets_init', 'widget_initialize');

?>