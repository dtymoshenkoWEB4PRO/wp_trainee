<?php
// Добавляем блоки в основную колонку на страницах постов и пост. страниц
add_action('add_meta_boxes', 'events_add_custom_box');
function events_add_custom_box()
{
    add_meta_box('post_metadata_events_post', 'Events Date and Status', 'events_meta_box_callback', 'events');
}

// HTML код блока
function events_meta_box_callback()
{

    $event_date = esc_attr(get_post_meta(get_the_ID(), '_event_date', true));

    echo "<input type=\"text\" name=\"_event_status\" value=\"" . esc_attr(get_post_meta(get_the_ID(), '_event_status', true))
        . "\" placeholder=\"Event _event_status\">";
    echo "<input type=\"date\" name=\"_event_date\" value=\"" . $event_date
        . "\" placeholder=\"Event Date\">";
}

//Сохраняем данные, когда пост сохраняется
add_action('save_post', 'events_save_postdata');
function events_save_postdata()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    $fields = [
        '_event_status',
        '_event_date',
    ];
    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post->ID, $field, sanitize_text_field($_POST[$field]));
        }
    }
}