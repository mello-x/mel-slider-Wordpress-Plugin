<?php
/*
Plugin Name: Mel Slider
Plugin URI: https://winchitta.space/
Description: A slider with options to have thumbnails.
Version: 1.0
Author: Assawin Chittanandha
Author URI: https://winchitta.space/
*/

// Include necessary files
register_activation_hook(__FILE__, 'mel_slider_activate');
register_deactivation_hook(__FILE__, 'mel_slider_deactivate');
require_once plugin_dir_path(__FILE__) . 'inc/database.php';
require_once plugin_dir_path(__FILE__) . 'inc/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'admin/options-page.php';
require_once plugin_dir_path(__FILE__) . 'shortcode/slider.php';

function enqueue_owl_scripts() {
    wp_enqueue_style('style', plugins_url('mel-slider/assets/style.css'));

    // Enqueue Owl Carousel styles
    wp_enqueue_style('owl-carousel-style', plugins_url('mel-slider/owlcarousel/owl.carousel.min.css'));
    wp_enqueue_style('owl-theme-default-style', plugins_url('mel-slider/owlcarousel/owl.theme.default.min.css'));

    // Enqueue Owl Carousel script
    wp_enqueue_script('owl-carousel-script', plugins_url('mel-slider/owlcarousel/owl.carousel.min.js'), array('jquery'), '2.3.4', true);

    // Enqueue Owl Carousel Thumb script
    wp_enqueue_script('owl-carousel-thumb-script', plugins_url('mel-slider/owlcarousel/owl.carousel2.thumbs.min.js'), array('jquery'), '0.1.6', true);
}
add_action('wp_enqueue_scripts', 'enqueue_owl_scripts');

// add option page
function mel_slider_menu() {
    add_options_page('Mel Slider Options', 'Mel Slider', 'manage_options', 'mel-slider-options', 'mel_slider_options_page');
}
add_action('admin_menu', 'mel_slider_menu');
