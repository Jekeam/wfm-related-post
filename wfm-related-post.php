<?php
/*
Plugin Name: Плагин похожие записи
Description: Плагин выводить несколько записей из одной группы
Author: Savinykh-AS
*/

add_filter('the_content', 'wfm_related_posts' );
add_action('wp_enqueue_scripts', 'wp_register_style_scripts' );

function wp_register_style_scripts(){
    wp_register_script( 'wfm-jquery-tools-js', plugins_url('js/jquery.tools.min.js', __FILE__ ), array('jquery') );
    wp_register_script( 'wfm-scripts-js', plugins_url('js/wfm-scripts.js', __FILE__ ), array('jquery') );
    wp_register_style( 'wfm-style-css', plugins_url('css/wfm-style.css', __FILE__ ) );

    wp_enqueue_script( 'wfm-jquery-tools-js' );
    wp_enqueue_script( 'wfm-scripts-js' );
    wp_enqueue_style( 'wfm-style-css' );
}

function wfm_related_posts($content){

    if( !is_single() ) return $content;

    $id = get_the_ID();    
    $categorys = get_the_category( $id );

    foreach ($categorys as $category) {
        $cats_id[] = $category->cat_ID;
    }

    $related_posts = new WP_Query(
        array(
            'posts_per_page' => 5,
            'category__in'  => $cats_id,
            'orderby'       => 'rand',
            'post__not_in' => array($id)
            )
        );

    if( $related_posts->have_posts() ){
        $content .= '<div class="related-post"><h2>Похожие статьи</h2>';
        while ( $related_posts->have_posts() ) {
            $related_posts->the_post();
            if( has_post_thumbnail() ){
                $img  = get_the_post_thumbnail( $id , array(90, 90) , array('alt' => get_the_title(),'title' => get_the_title()) );
            }else{
                $img = '<img src="' . plugins_url( 'img/No_image_available.png', __FILE__ ) .'" alt="'.get_the_title().'" title="'.get_the_title().'" heigth="90px" width="90px">';
            }
            $content .= '<a href="' . get_the_permalink() . '">' . $img . '</a>';
        }
        $content .= '</div>';
    }

    wp_reset_query();
    return $content;

}