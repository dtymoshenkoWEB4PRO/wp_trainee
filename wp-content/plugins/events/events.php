<?php

/*
 * Plugin Name: Events
 * Description: Events plugin
 * Author:      Daria Tymoshenko
 * Version:     1.0
 * Requires PHP: 7.4
 */

add_action('init', 'event_post_type');
function event_post_type()
{
    register_post_type('event', array(
        'labels' => array(
            'name' => __('Events', 'vicodemedia'),
            'singular_name' => __('Event', 'vicodemedia'),
            'add_new' => __('Add New', 'vicodemedia'),
            'add_new_item' => __('Add New Event', 'vicodemedia'),
            'edit_item' => __('Edit Event', 'vicodemedia'),
            'new_item' => __('New Event', 'vicodemedia'),
            'view_item' => __('View Event', 'vicodemedia'),
            'view_items' => __('View Events', 'vicodemedia'),
            'search_items' => __('Search Events', 'vicodemedia'),
            'not_found' => __('No events found.', 'vicodemedia'),
            'not_found_in_trash' => __('No events found in trash.', 'vicodemedia'),
            'all_items' => __('All Events', 'vicodemedia'),
            'archives' => __('Event Archives', 'vicodemedia'),
            'insert_into_item' => __('Insert into Event', 'vicodemedia'),
            'uploaded_to_this_item' => __('Uploaded to this Event', 'vicodemedia'),
            'filter_items_list' => __('Filter Events list', 'vicodemedia'),
            'items_list_navigation' => __('Events list navigation', 'vicodemedia'),
            'items_list' => __('Events list', 'vicodemedia'),
            'item_published' => __('Event published.', 'vicodemedia'),
            'item_published_privately' => __('Event published privately.', 'vicodemedia'),
            'item_reverted_to_draft' => __('Event reverted to draft.', 'vicodemedia'),
            'item_scheduled' => __('Event scheduled.', 'vicodemedia'),
            'item_updated' => __('Event updated.', 'vicodemedia')
        ),
        'has_archive' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'revisions'),
        'can_export' => true,
        'taxonomies' => array('event_type')
    ));
}

// add event date field to events post type
function add_post_meta_boxes()
{
    add_meta_box(
        "post_metadata_events_post", // div id containing rendered fields
        "Event Date", // section heading displayed as text
        "post_meta_box_events_post", // callback function to render fields
        "event", // name of post type on which to render fields
        "side", // location on the screen
        "low" // placement priority
    );
}

add_action("admin_init", "add_post_meta_boxes");

// save field value
function save_post_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // if ( get_post_status( $post->ID ) === 'auto-draft' ) {
    //     return;
    // }
    update_post_meta($post->ID, "_event_date", sanitize_text_field($_POST["_event_date"]));
}

add_action('save_post', 'save_post_meta_boxes');

// callback function to render fields
function post_meta_box_events_post()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $advertisingCategory = $custom["_event_date"][0];
    echo "<input type=\"date\" name=\"_event_date\" value=\"" . $advertisingCategory . "\" placeholder=\"Event Date\">";
}

// generate shortcode
add_shortcode('events-list', 'vm_events');
function vm_events($arguments)
{

    global $post;
    $args = array(
        'post_type' => 'event',
        'post_status' => $arguments['post_status'],
        'posts_per_page' => $arguments['posts_per_page'],
        'orderby' => 'meta_value',
        'meta_key' => '_event_date',
        'order' => 'ASC'
    );

    $query = new WP_Query($args);

    $content = '<ul>';
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();

            // display event
            $content .= '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a> - ' . date_format(date_create(get_post_meta($post->ID, '_event_date', true)), 'jS F') . '</li>';
        endwhile;
    else:
        _e('Sorry, nothing to display.', 'vicodemedia');
    endif;
    $content .= '</ul>';

    return $content;
}

// хук для регистрации
add_action( 'init', 'create_taxonomy' );
function create_taxonomy(){

    // список параметров: wp-kama.ru/function/get_taxonomy_labels
    register_taxonomy( 'event_type', [ 'event' ], [
        'label'                 => '', // определяется параметром $labels->name
        'labels'                => [
            'name'              => 'Event type',
            'singular_name'     => 'Event type',
            'search_items'      => 'Search Event',
            'all_items'         => 'All events',
            'view_item '        => 'View events',
            'parent_item'       => 'Parent events',
            'parent_item_colon' => 'Parent events:',
            'edit_item'         => 'Edit events',
            'update_item'       => 'Update events',
            'add_new_item'      => 'Add New events',
            'new_item_name'     => 'New events Name',
            'menu_name'         => 'events',
            'back_to_items'     => '← Back to events',
        ],
        'description'           => '', // описание таксономии
        'public'                => true,
        'publicly_queryable'    => true, // равен аргументу public
        'show_in_nav_menus'     => true, // равен аргументу public
        'show_ui'               => true, // равен аргументу public
        'show_in_menu'          => true, // равен аргументу show_ui
        'show_tagcloud'         => true, // равен аргументу show_ui
        'show_in_quick_edit'    => true, // равен аргументу show_ui
        'hierarchical'          => false,

        'rewrite'               => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities'          => array(),
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => null, // добавить в REST API
        'rest_base'             => null, // $taxonomy
        // '_builtin'              => false,
        //'update_count_callback' => '_update_post_term_count',
    ] );
}