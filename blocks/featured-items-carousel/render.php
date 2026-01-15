<?php 
    $scroll_interval = (get_field("scroll_interval") * 1000) ?? 10000;

    $items = get_field('featured_items');
?>
<div <?= get_block_wrapper_attributes() ?>>
    <?php if (count($items) > 1): ?>
        <button class="carousel-nav prev">
            <?= esc_html("‹") ?>
        </button>
        <button class="carousel-nav next">
            <?= esc_html("›") ?>
        </button>
    <?php endif; ?>
    <div class="items">
        <?php if ($is_preview): ?>
            <div class="featured-item">
                <InnerBlocks 
                    template="<?php echo esc_attr(wp_json_encode([
                        ['core/template-part', [
                            'slug' => 'carousel-item',
                        ]]
                    ])); ?>"
                    templateLock="true"
                />
            </div>
        <?php endif; ?>

        <?php foreach ($items as $item): ?>
            <div class="featured-item" >
                <?php 
                    global $post;
                    $post = $item;
                    setup_postdata($post);
                    block_template_part("carousel-item");    
                ?>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>
    <?php if (count($items) > 1): ?>
        <script>
            let carousel = document.currentScript.parentElement;
            let items = carousel.querySelector(".items");
            function next(e) {
                items.scrollBy(items.offsetWidth, 0);
            }
            function prev(e) {
                items.scrollBy(-items.offsetWidth, 0);
            }
            let carouselScrollInterval;

            function startInterval() {
                stopInterval();
                carouselScrollInterval = window.setInterval(next, <?= $scroll_interval ?>);
            }
            function stopInterval() {
                window.clearInterval(carouselScrollInterval);
            }
            carousel.querySelector(".next").addEventListener("click", next);
            carousel.querySelector(".prev").addEventListener("click", prev);
            carousel.addEventListener("pointerenter", stopInterval);
            carousel.addEventListener("pointerleave", startInterval);
            startInterval();
        </script>
    <?php endif; ?>
</div>
