<?php
session_start();
get_header();
get_template_part("template-parts/loader");

const SEARCH_SELECTION = "mc:search:directory_catalog:selections";


$uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'];
$filters = buildFilter();
$query = [];

parse_str($query_string, $query);
$category = $query['category'] ?? '';


if (!isset($_SESSION[SEARCH_SELECTION]) || empty($category)) {
    // (id, label, tag, value)
    $_SESSION[SEARCH_SELECTION] = [];
}

if (isset($query['c']) && isset($query['k']) && isset($query['v'])) {

    $k = $query['k'];
    $v = $query['v'];
    $l = $query['l'] ?? $k;
    $multiple = isset($query['m']) && $query['m'];

    if ($query['c'] === 'add') {

        $exists = array_exists($_SESSION[SEARCH_SELECTION], fn($x) => $x['key'] === $k && $x['value'] == $v);

        if (!$exists) {
            $_SESSION[SEARCH_SELECTION][] = ["key" => $k, "value" => $v, "label" => $l];
        }

    } elseif ($query['c'] === 'del') {
        $_SESSION[SEARCH_SELECTION] = array_filter($_SESSION[SEARCH_SELECTION], fn($x) => $x['key'] !== $k || $x['value'] !== $v);
    }

    unset($query['k']);
    unset($query['v']);
    unset($query['m']);
    unset($query['l']);
    unset($query['c']);
}

$removeLink = mc_build_remove_links($query);

function mc_build_remove_links($query): array
{

    $result = [];
    $selectionState = $_SESSION[SEARCH_SELECTION];

    foreach ($selectionState as $item) {
        $result[] = [
            'label' => __($item['label']),
            'value' => __($item['value']),
            'link' => mc_build_search_query(mc_remove_query($query, $item))
        ];
    }
    return $result;
}

function mc_render_filter(array $filter, string $category, $query)
{
    $findResult = array_filter($filter, fn($x) => $x['id'] == $category);
    if (!empty($findResult)) {
        $first = array_values($findResult)[0];
        mc_renderer($first, $query);
    }
}

function mc_add_query($query, $queryTag, $value, $multiple, $label = null)
{
    $query['k'] = $queryTag;
    $query['v'] = $value;
    $query['m'] = intval($multiple);
    $query['l'] = $label;
    $query['c'] = 'add';

    return $query;
}

function mc_remove_query($query, $item)
{
    $query['k'] = $item['key'];
    $query['v'] = $item['value'];
    $query['c'] = 'del';
    return $query;
}

function mc_build_search_query($query): string
{
    return "?" . http_build_query($query);
}

function mc_renderer($item, $query)
{
    $localQuery = $query;
    $id = $item['id'];
    $label = $item['label'] ?? $id;
    $children = $item['children'] ?? [];
    $queryTag = $item['queryTag'] ?? $item['id'];

    $enum = array_filter($item['enum'] ?? [], function ($x) {
        $queryTag = $x['queryTag'] ?? $x['key'];
        return !array_exists($_SESSION[SEARCH_SELECTION], fn($y) => $y['key'] === $queryTag && $y['value'] === $x['value']);
    });

    $multiple = $item['multiple'] ?? false;
    $has_type = isset($item['type']);
    $type = $item['type'] ?? '';
    $is_selected = $has_type && array_exists($_SESSION[SEARCH_SELECTION], fn($x) => $x['key'] === $queryTag);

    if ($is_selected) return;

    if (empty($children) && empty($enum) && !$has_type) return;

    ?>
    <div>
        <?php if ($has_type): ?>
            <?php if (!isset($query[$queryTag])): ?>
                <?php if ($type === 'date'): ?>
                    <div class="flex flex-row items-center space-x-2 space-y-2">
                        <div class="font-bold flex-grow-0 min-w-10 max-w-20"><?= __($label) ?></div>
                        <input
                                type="date"
                                @change="invokeUrl('<?= $queryTag ?>', $el.value, '<?= mc_build_search_query($query) ?>')"
                                class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                name="<?= $queryTag ?>"
                                value="<?= $query[$queryTag] ?? '' ?>"
                        >
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="font-bold"><?= __($label) ?></div>
            <div class="px-3">
                <?php foreach ($children as $child): ?>
                    <?php mc_renderer($child, $query); ?>
                <?php endforeach; ?>
                <?php foreach ($enum as $child): ?>
                    <?php
                    $finalTag = $child['queryTag'] ?? $queryTag;
                    $value = $child['value'];
                    ?>

                    <div>
                        <a href="<?= mc_build_search_query(mc_add_query($localQuery, $finalTag, $value, $multiple, $label)) ?>">
                            <?= $child['key'] ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

function mc_render_content($query)
{
    $selections = $_SESSION[SEARCH_SELECTION];
    $is_logged = is_user_logged_in();
    $maybePage = $query["page"] ?? 1;
    $post_type = $query['post_type'];
    if (is_null($maybePage)) {
        $page = 1;
    } else {
        $page = intval($maybePage);
    }
    $query_result = ["post_type" => $post_type, "posts_per_page" => 10, "paged" => $page];

    if (!is_null($query["s"])) {
        $query_result = array_merge($query_result, ["s" => $query["s"]]);
    }

    $author = array_find($selections, fn($x) => $x['key'] === 'author');
    if (!is_null($author)) {
        $query_result = array_merge($query_result, ["author_name" => $author["value"]]);
    }

    $tags = array_map(fn($x) => $x['value'], array_filter($selections, fn($x) => $x['key'] === 'tag'));
    if (!empty($tags)) {
        $func = function ($v) {
            return [
                'taxonomy' => CLASSIFICATION_TAXONOMY,
                "field" => "slug",
                "terms" => $v
            ];
        };
        $args = array_map($func, $tags);
        $query_result = array_merge($query_result, [
            "tax_query" => array_merge(
                [
                    'relation' => 'OR',
                ],
                $args
            )
        ]);
    }

    $date_query = [];

    $before = array_find($selections, fn($x) => $x['key'] === "before");
    if (!is_null($before)) {
        $date = explode('/', $before);
        $date_query = array_merge($date_query, [
            "before" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
        ]);
    }

    $after = array_find($selections, fn($x) => $x['key'] === "after");
    if (!is_null($after)) {
        $date = explode('/', $after);
        $date_query = array_merge($date_query, [
            "after" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
        ]);
    }

    if (!$is_logged) {
        $query_result = array_merge($query_result, [
            "meta_query" => [
                ["key" => "kind", "compare" => "NOT EXISTS"]
            ]
        ]);
    }

    if (count($date_query) > 0) {
        $query_result = array_merge($query_result, ["date_query" => $date_query]);
    }

    $query = new WP_Query($query_result);
    $posts = $query->get_posts();

    foreach ($posts as $post) {
        $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);
        ?>

        <div>
            <div><?= $post->post_title ?></div>
            <div>
                <?= $post->post_content ?>
                <div class="p-4 lg:w-1/2">
                    <div class="h-full flex sm:flex-row flex-col items-center sm:justify-start justify-center text-center sm:text-left">
                        <img alt="team" class="flex-shrink-0 rounded-lg w-48 h-48 object-cover object-center sm:mb-0 mb-4" src="https://dummyimage.com/200x200">
                        <div class="flex-grow sm:pl-8">
                            <h2 class="title-font font-medium text-lg text-gray-900"><?= $post->post_title ?></h2>
                            <h3 class="text-gray-500 mb-3">UI Developer</h3>
                            <p class="mb-4"><?= $abstract ?></p>
                            <span class="inline-flex">
                                <a class="text-gray-500">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                    </svg>
                                </a>
                                <a class="ml-2 text-gray-500">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                                    </svg>
                                </a>
                                <a class="ml-2 text-gray-500">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                    </svg>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    $a = 1;
}

?>
    <div x-init="loaderOn=false" class="container mx-auto py-5">
        <?php goBack() ?>
        <div class="flex ">
            <div class="flex-grow-0 min-w-50">
                <?php if (empty($category)): ?>
                    <div class="text-lg font-bold"><?= __('Category') ?></div>

                    <div>
                        <?php foreach ($filters as $item): ?>
                            <div>
                                <a href="<?= $uri . "&category=" . $item['id'] ?>"><?= __($item['id']) ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>

                    <!--                    <form action="--><? //= get_search_link($query_string) ?><!--" method="get" x-ref="form">-->
                    <?php mc_render_filter($filters, $category, $query) ?>
                    <!--                    </form>-->
                <?php endif; ?>
            </div>
            <div class="flex-1">
                <div>
                    <?php foreach ($removeLink as $item): ?>
                        <div class="inline-block rounded-full px-1 shadow bg-blue-500 text-white">
                            <a href="<?= $item['link'] ?>" class="flex flex-row items-center ">
                                <span><?= __($item['label']) . ": " . __($item['value']) ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div>
                    <?php mc_render_content($query); ?>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
