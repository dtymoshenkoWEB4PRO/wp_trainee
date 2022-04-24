<?php
/*
 * Plugin Name: Events
 * Description: Events plugin
 * Author:      Daria Tymoshenko
 * Version:     1.0
 * Requires PHP: 7.4
 */

class FeatureEventsWidget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'foo_widget',
            'Events',
            array('description' => 'Feature events widget')
        );
    }

    public function widget($args, $instance) {
        global $post;

        $arguments = [
            'post_type' => 'events',
            'meta_query' => [['key' => '_event_status', 'value' => $instance['_event_status']]],
            'posts_per_page' => $instance['posts_per_page']
        ];

        $query = new WP_Query($arguments);
        if ($query->have_posts()) :
            while ($query->have_posts()): $query->the_post();

                echo '<ul><li>' . get_the_title() . ' - ' . date_format(date_create(get_post_meta($post->ID, '_event_date', true)), 'jS F') . '</li></ul>';
            endwhile;
        endif;
    }

    public function form($instance) {

        $event_status = $instance['_event_status'];
        $event_limit = $instance['posts_per_page'];
        ?>
        <div>
            <label>Event status</label>
            <select name="<?php echo $this->get_field_name('_event_status'); ?>">
                <option id="open" value="open" <?php echo ($event_status === 'open') ? ' selected' : ''; ?>>Open event
                </option>
                <option id="by_invitation"
                        value="by_invitation" <?php echo ($event_status === 'by_invitation') ? ' selected' : ''; ?>>
                    Event by invitation </option>
            </select>
        </div>
        <div>
            <label>Event limit</label>
            <input name="<?php echo esc_attr($this->get_field_name('posts_per_page')); ?>"
                   type="number"
                   value="<?php echo esc_attr(intval($event_limit)); ?>">
        </div>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['_event_status'] = (!empty($new_instance['_event_status'])) ? sanitize_text_field($new_instance['_event_status']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? sanitize_text_field($new_instance['posts_per_page']) : '';
        return $instance;
    }

}

add_action('widgets_init', 'register_foo_widget');
function register_foo_widget()
{
    register_widget('FeatureEventsWidget');
}

