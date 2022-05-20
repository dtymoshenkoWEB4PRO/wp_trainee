<?php

class AjaxSearch extends WP_Widget {
	function __construct() {
		parent::__construct(
			'ajax_search_widget',
			'Ajax Search Widget',
			array( 'description' => 'Widget for ajax search post' )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		?>
        <form action="" method="POST" id="ajax_filter">
            <div>
                <label><?php _e( 'Since date:' ) ?></label>
                <input id="date" type="date" value="<?php echo get_search_query(); ?>" name="date"/>
            </div>
            <div>
                <label><?php _e( 'Post name:' ) ?></label>
                <input  type="search" id="title" value="<?php echo get_search_query(); ?>" name="title" required />
            </div>
            <input type="hidden" name="post_limit" id="post_limit" value="<?php echo $instance['post_limit'] ?>">
            <input type="hidden" name="action" value="post">
        </form>
        <div id="response"></div>
		<?php
		echo $args['after_widget'];
	}

	function form( $instance ) {
		extract( $instance );
		?>
        <div>
            <label><?php _e( 'Posts limit:' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'post_limit' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'post_limit' ) ); ?>" type="text"
                   value="<?php echo esc_attr( intval( $instance['post_limit'] ) ); ?>"/>
        </div>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['post_limit'] = ( ! empty( $new_instance['post_limit'] ) ) ? sanitize_text_field( $new_instance['post_limit'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', 'register_ajax_search' );
function register_ajax_search() {
	register_widget( 'AjaxSearch' );
}

