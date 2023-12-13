<?php

function mel_slider_shortcode() {
    ob_start();

    // Query custom repeater field data
    $repeater_data = get_post_meta(get_the_ID(), '_mel_slider_image_urls', true);

    if ($repeater_data) {
        $total_sliders = count($repeater_data); // Get the total number of sliders
        wp_localize_script('script', 'php_vars', array('total_sliders' => $total_sliders));
        echo '<div class="owl-carousel-container">'; // Container for the whole slider

        // Owl Carousel
        echo '<div class="owl-carousel owl-carousel-mel owl-theme" data-slider-id="1">';
        foreach ($repeater_data as $item) {
            $image_url = $item;
            // You can add more fields if needed

            echo '<div class="item">';
            if (!empty($image_url)) {
                // If only the image field is filled, display the image
                echo '<img src="' . esc_url($image_url) . '" alt="Image">';
            }
            echo '</div>';
        }
        echo '</div>';	

        // Container for counter and custom thumbs
        echo '<div class="counter-thumbs-container">';
        // Counter
        echo '<div class="slider-counter">1 - ' . $total_sliders . '</div>';
        // Thumbnail Navigation with Dynamic Counter
        echo '<div class="custom-owl-thumbs" data-slider-id="1">';
        foreach ($repeater_data as $key => $item) {
            $image_url = $item;
            $counter = $key + 1;
            $current_slider_text = $counter . ' - ' . $total_sliders;
            echo '<button class="custom-owl-thumb-item" data-counter="' . $current_slider_text . '"><img src="' . esc_url($image_url) . '" alt="Image"></button>';
        }
        echo '</div>';

        echo '</div>'; // End of counter-thumbs-container
        
        echo '</div>'; // End of owl-carousel-container

        echo '<script>
        jQuery(document).ready(function($) {
            var owl = $(".owl-carousel").owlCarousel({
                loop: false,
                margin: 20,
                nav: false,
                dots: false,
                items: 1, // Set items to 1 for single item display
                thumbs: true,
                thumbImage: false,
                thumbsPrerendered: true,
                thumbContainerClass: "custom-owl-thumbs",
                thumbItemClass: "custom-owl-thumb-item",
            });
        
            // On change slide, update the counter dynamically
            owl.on("changed.owl.carousel", function(event) {
                var currentSlideIndex = event.item.index;
                var currentSliderText = (currentSlideIndex + 1) + " - " + ' . $total_sliders . ';
                $(".custom-owl-thumb-item").attr("data-counter", currentSliderText);
                $(".slider-counter").text(currentSliderText);
            });
        });
        </script>';
    } else {
        echo '<p>No carousel items found.</p>';
    }

    return ob_get_clean();
}
add_shortcode('mel_slider', 'mel_slider_shortcode');
