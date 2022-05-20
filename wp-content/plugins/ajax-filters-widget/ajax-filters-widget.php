<?php
/**
 * Plugin Name: Ajax filter post plugin
 * Version: 1.0.0
 * Author: Daria Tymoshenko
 * Requires PHP: 7.3
 */

include dirname( __FILE__ ) . '/widget/ajax_filter_widget.php';

add_action( 'wp_ajax_ajax_filters', 'ajax_filters' );
add_action( 'wp_ajax_nopriv_ajax_filters', 'ajax_filters' );
function ajax_filters() {

	$args  = array(
		'posts_per_page' => $_POST['post_limit'],
		's'              => esc_attr( $_POST['title'] ),
		'date_query'     => array(
			array(
				'after' => $_POST['date'],
			),
		),
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<div><a href="' . esc_url( get_permalink( $query->post ) ) . '">' . $query->post->post_title . '</a></div>';
		}
		wp_reset_postdata();
	} else {
		echo 'Post does not exist'();
	}
	wp_die();
}

add_action( 'wp_enqueue_scripts', 'init_ajax_filters' );
function init_ajax_filters() {
	wp_enqueue_script( 'init_ajax', plugins_url( '/js/ajax_filters.js', __FILE__ ), array( 'jquery' ), null, true, );
	wp_localize_script( 'init_ajax', 'ajaxFilter', array( 'admin_url' => admin_url( 'admin-ajax.php' ) ) );
}




