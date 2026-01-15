<div <?= get_block_wrapper_attributes(); ?>>
    <div id="nav-menu-full" >
        <?= wp_nav_menu(['menu' => 'Main Site Navigation']) ?>
    </div>
    <ul id="breadcrumbs">
        <?php if ($is_preview): ?>
            <li>Page</li>
            <li>Child Page</li>
        <?php endif; ?>
    </ul>
    <script>
        let breadcrumbs = document.querySelector(".wp-block-yr-nav-breadcrumbs ul#breadcrumbs");
        let navFull = document.querySelector(".wp-block-yr-nav-breadcrumbs #nav-menu-full");
        let currentPage = navFull.querySelector(".current_page_item")
            || navFull.querySelector(".current-menu-item");
        let crumbs = [];
        while (true) {
            if (!currentPage) break;
            const newCrumb = document.createElement("li");
            const link = currentPage.querySelector(":scope > a");
            newCrumb.classList = currentPage.classList;
            if (!link) break;
            newCrumb.appendChild(link);
            crumbs.push(newCrumb);
            const newCurrent = currentPage.closest(".current_page_parent");
            if (newCurrent == currentPage) break;
            else currentPage = newCurrent;
        }
        for (const crumb of crumbs.reverse()) breadcrumbs.appendChild(crumb);
    </script>
</div>