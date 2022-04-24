<?php

$event_date = esc_attr(get_post_meta(get_the_ID(), '_event_date', true));
$event_status = esc_attr(get_post_meta(get_the_ID(), '_event_status', true));

?>

<div>
    <div>
        <span for="_event_status">Status</span>
        <select id="_event_status"
                name="_event_status">
            <option value="open" <?php echo ($event_status === 'open') ? ' selected' : ''; ?>>Open event</option>
            <option value="by_invitation" <?php echo ($event_status === 'by_invitation') ? ' selected' : ''; ?>> Event
                by invitation
            </option>
        </select>
    </div>
    <div>
        <span>Event date</span>
        <input type="date" name="_event_date" value="<?php echo $event_date ?>" placeholder="Event Date">
    </div>
</div>
