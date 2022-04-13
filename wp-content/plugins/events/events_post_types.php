<?php

add_action('init', 'register_events_post_types');
function register_events_post_types()
{
    register_post_type('events', [
        'label' => null,
        'labels' => [
            'name' => 'Events',
            'singular_name' => 'Event',
            'add_new' => 'Add event',
            'add_new_item' => 'Add new event',
            'edit_item' => 'Edit event',
            'new_item' => 'New event',
            'view_item' => 'See event',
            'search_items' => 'Search event',
            'not_found' => 'Doesn not found',
            'not_found_in_trash' => 'Doesn not found in trash',
            'parent_item_colon' => '',
            'menu_name' => 'Events',
        ],
        'has_archive' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 8,
        'supports' => array('title', 'editor', 'thumbnail')
    ]);
}

// создание таксономии
add_action('init', 'create_event_type_taxonomy');
function create_event_type_taxonomy()
{
    register_taxonomy('event_type', ['events'], [
        'label' => '',
        'labels' => [
            'name' => 'Events types',
            'singular_name' => 'Event type',
            'search_items' => 'Search Event type',
            'all_items' => 'All events types',
            'view_item ' => 'View event type',
            'parent_item' => 'Parent event type',
            'parent_item_colon' => 'Parent events:',
            'edit_item' => 'Edit event type',
            'update_item' => 'Update event type',
            'add_new_item' => 'Add New event type',
            'new_item_name' => 'New events type name',
            'menu_name' => 'Events types',
            'back_to_items' => 'Back to events types',
        ],
        'description' => 'Events type',
        'public' => true,
        'publicly_queryable' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'hierarchical' => false,
        'rewrite' => true,
        'capabilities' => array(),
        'meta_box_cb' => null,
        'show_admin_column' => false,
        'show_in_rest' => null,
        'rest_base' => null,
    ]);

}

// генерация shortcode
add_shortcode('events_list', 'list_events');
function list_events($arguments)
{
    global $post;
    $args = array(
        'post_type' => 'events',
        'meta_query' => [['key' => '_event_status', 'value' => $arguments['status']]],
        'meta_key' => '_event_date',
        'posts_per_page' => $arguments['posts_per_page']
    );

    $query = new WP_Query($args);
    $content = '<ul>';
    if($query->have_posts()):
        while($query->have_posts()): $query->the_post();
            $exp_date = get_post_meta(get_the_ID(), '_event_date', true);
            date_default_timezone_set('America/New_York');
            $today = new DateTime();
            if($exp_date < $today->format('Y-m-d h:i:sa')){

                $current_post = get_post( get_the_ID(), 'ARRAY_A' );
                $current_post['post_status'] = 'trash';
                wp_update_post($current_post);
            }
            $content .= '<li><a href="'.get_the_permalink().'">'. get_the_title() .'</a> - '.date_format(date_create(get_post_meta($post->ID, '_event_date', true)), 'jS F').'</li>';
        endwhile;
    else:
        _e('Sorry, nothing to display.', 'vicodemedia');
    endif;
    $content .= '</ul>';
    return $content;
}