<?php
/*
Plugin Name: Плагин похожие записи
Description: Плагин выводить несколько записей из одной группы
Author: Savinykh-AS
*/

add_filter('the_content', 'wfm_related_posts' );

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
            $content .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a><br>';
        }
        $content .= '</div>';
    }

    wp_reset_query();
    return $content;
}