var carousel = document.querySelector(".wp-block-yr-featured-items-carousel");
var items = carousel.querySelector(".items");
function next(e) {
    items.scrollBy(items.offsetWidth, 0);
}
function prev(e) {
    items.scrollBy(-items.offsetWidth, 0);
}
let carouselScrollInterval;

function startInterval() {
    stopInterval();
    carouselScrollInterval = window.setInterval(next, 5000);
}
function stopInterval() {
    window.clearInterval(carouselScrollInterval);
}

carousel.querySelector(".next").addEventListener("click", next);
carousel.querySelector(".prev").addEventListener("click", prev);
carousel.addEventListener("pointerenter", startInterval);
carousel.addEventListener("pointerleave", stopInterval);
startInterval();