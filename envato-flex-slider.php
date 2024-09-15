<?php
/*
Plugin Name: Envato FlexSlider
Plugin URI:
Description: A simple plugin that integrates FlexSlider (http://flex.madebymufffin.com/) with WordPress using custom post types!
Author: Joe Casabona
Version: 0.5
Author URI: http://www.casabona.org
*/

/* Some Set-up */
define('EFS_PATH', get_template_directory_uri() . '/inc/vendors/' . basename(dirname(__FILE__)) . '/');
define('EFS_NAME', "Envato FlexSlider");
define("EFS_VERSION", "0.5");

/* Files to Include */
require_once('slider-img-type.php');

function efs_get_slider() {
  $args = array(
    'post_type' => 'slider-image',
    'posts_per_page' => -1 // Get all posts
  );
  $query = new WP_Query($args);

  global $post_id;

  if ($query->have_posts()) {
    $count = $query->post_count;
    $slider = '<div class="orbit" role="region" aria-label="Banner Slider" data-orbit data-options="animInFromLeft:fade-in; animInFromRight:fade-in; animOutToLeft:fade-out; animOutToRight:fade-out;">
                <ul class="orbit-container">';

    $x = 1;
    while ($query->have_posts()) {
      $query->the_post();
      $img = get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'orbit-image'));
      $slide_link = slider_link_get_meta_box_data(get_the_ID());
      $caption = get_the_title();

      $class = ($x == 1) ? 'is-active' : '';
      $slider .= '<li class="orbit-slide ' . $class . '"><div class="orbit-slide-number"><span>' . $x . '</span> of <span>' . $count . '</span></div><a href="' . $slide_link . '">' . $img . '</a><figcaption class="orbit-caption">' . $caption . '</figcaption></li>';
      $x++;
    }

    if ($count > 1) {
      $slider .= '<button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
                  <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>';
    }
    
    $slider .= '</ul>';
    
    if ($count > 1) {
      $slider .= '<nav class="orbit-bullets">';
      for ($i = 0; $i < $count; $i++) {
        $class = ($i == 0) ? 'is-active' : '';
        $slider .= '<button class="' . $class . '" data-slide="' . $i . '"><span class="show-for-sr">Current Slide</span></button>';
      }
      $slider .= '</nav>';
    }

    $slider .= '</div>';

    wp_reset_postdata(); // Reset the query data

    return $slider;
  } else {
    return;
  }
}

/** Add the shortcode for the slider - for use in editor **/
function efs_insert_slider($atts, $content = null) {
  $slider = efs_get_slider();
  return $slider;
}
add_shortcode('ef_slider', 'efs_insert_slider');

/** Add template tag - for use in themes **/
function efs_slider() {
  echo efs_get_slider();
}

?>
