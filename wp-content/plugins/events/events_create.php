<?php
/*
 * Plugin Name: Events
 * Description: Events plugin
 * Author:      Daria Tymoshenko
 * Version:     1.0
 * Requires PHP: 7.4
 */

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

// добавление event date
add_action("admin_init", "add_post_meta_boxes");
function add_post_meta_boxes()
{
    add_meta_box(
        "post_metadata_events_post",
        "Event Date",
        "post_meta_box_events_post",
        "events",
        "side",
        "low"
    );
}

add_action('save_post', 'save_post_meta_boxes');
function save_post_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_event_date", sanitize_text_field($_POST["_event_date"]));
}

function post_meta_box_events_post()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $advertisingCategory = $custom["_event_date"][0];
    echo "<input type=\"date\" name=\"_event_date\" value=\"" . $advertisingCategory . "\" placeholder=\"Event Date\">";
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
    $args = array(
        'post_type' => 'events',
        'post_status' => $arguments['post_status'],
        'posts_per_page' => $arguments['posts_per_page']
    );
    $query = new WP_Query($args);
    $content = '<ul>';
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();
            $content .= '<li>' . get_the_title() . ' ' . get_the_date() . '</li>';;
        endwhile;
    else:
        __('Sorry, nothing to display.');
    endif;
    $content .= '</ul>';
    return $content;
}