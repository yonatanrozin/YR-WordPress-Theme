<?php 
    global $wp_query;

    $queriedDate = $_GET['date'] ?? null;
    $queriedDay = $_GET['d'] ?? null;
    
    $calendarDate = date_create_from_format("Ym", $queriedDate);
    if (!$calendarDate) $calendarDate = new DateTime();
    $calendarDateEnd = new DateTime();

    $yearView = $calendarDate->format("Y");
    $monthView = $calendarDate->format("m");

    if (isset($queriedDay)) {
        $calendarDate->setDate($yearView, $monthView, $queriedDay);
        $calendarDateEnd->setDate($yearView, $monthView, $queriedDay);
    }
    else {
        $calendarDate->setDate($yearView, $monthView, 1);
        $calendarDateEnd->setDate($yearView, $monthView + 1, 1);
    }

    $list_query = $is_preview ? new WP_query(array(
        "post_type" => "event",
        "posts_per_page" => 3
    )) : $wp_query;

    $gap = get_field("gap");

    $list_items = [];
    foreach ($list_query->posts as $post) {
        $date = get_field("date", $post);
        $end_date = get_field("end_date", $post);
        $on_calendar = !empty($end_date) ? 
            ($date <= $calendarDateEnd->format("Ymd") && $end_date >= $calendarDate->format("Ymd"))
            : $date >= $calendarDate->format("Ymd") && $date <= $calendarDateEnd->format("Ymd");
        $upcoming = !empty($end_date) ? 
            ($end_date >= $calendarDate->format("Ymd"))
            : $date >= $calendarDate->format("Ymd");
        if ($is_preview || (isset($queriedDate) ? $on_calendar : ($upcoming || $on_calendar))) {
            array_push($list_items, $post);
        }
    }
?>

<?php if (!$is_preview): ?><div <?= get_block_wrapper_attributes() ?>><?php endif; ?>
    <?php if ($is_preview): ?>
        <InnerBlocks 
            template="<?php echo esc_attr(wp_json_encode([
                ['core/template-part', ["slug" => "event-list-item"]]
            ])); ?>"
            templateLock="all"
        />
    <?php endif; ?>
    <?php foreach ($list_items as $item): ?>
        <?php 
            global $post;
            $post = $item;
            setup_postdata($post);
            block_template_part("event-list-item");    
        ?>
    <?php endforeach; wp_reset_postdata(); ?>
<?php if (!$is_preview): ?></div><?php endif; ?>
<style>
    .wp-block-yr-event-list {
        gap: <?= $gap ?>rem;
    }
</style>