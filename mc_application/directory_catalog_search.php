<?php
get_header();
get_template_part("template-parts/loader");
$filters = buildFilter();
$category = $_GET["category"] ?? '';
$uri = $_SERVER['REQUEST_URI'];
?>
    <div x-init="loaderOn=false" class="flex container mx-auto py-5">

        <div class="w-1/3">
            <?php if (empty($category)): ?>
                <div class="text-lg font-bold"><?= __('Category') ?></div>
                <div>
                    <?php foreach ($filters as $item): ?>
                        <div>
                            <a href="<?= $uri."&category=".$item['id'] ?>"><?= __($item['id']) ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex-1">pepe</div>
    </div>
<?php
get_footer();
