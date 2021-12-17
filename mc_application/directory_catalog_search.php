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

function _iterator($item): array
{
    if (isset($item['children'])) {
        return mc_get_allowed($item['children']);
    } else {
        return [$item['queryTag'] ?? $item['id']];
    }
}

function mc_get_allowed($filterSpec)
{
    $result = [];
    foreach ($filterSpec as $item) {
        $result = array_merge($result, _iterator($item));
    }
    return $result;
}

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
        $first = $findResult[0];
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
    $is_logged = is_user_logged_in();
    $maybePage = $query["page"];
    if (is_null($maybePage)) {
        $page = 1;
    } else {
        $page = intval($maybePage);
    }
    $query_result = ["post_type" => DIRECTORY_CATALOG, "posts_per_page" => 10, "paged" => $page];

    if (!is_null($query["s"])) {
        $query_result = array_merge($query_result, ["s" => $query["s"]]);
    }

    if (!is_null($query["writer"])) {
        $query_result = array_merge($query_result, ["author_name" => $query["writer"]]);
    }

    /*    if (!is_null($request->get_param("tag"))) {
            $func = function ($v) {
                return [
                    'taxonomy' => CLASSIFICATION_TAXONOMY,
                    "field" => "slug",
                    "terms" => $v
                ];
            };
            $args = array_map($func, explode(",", $request->get_param("tag")));
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

        if (!is_null($request->get_param("before"))) {
            $date = explode('/', $request->get_param("before"));
            $date_query = array_merge($date_query, [
                "before" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
            ]);
        }

        if (!is_null($request->get_param("after"))) {
            $date = explode('/', $request->get_param("after"));
            $date_query = array_merge($date_query, [
                "after" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
            ]);
        }

        if (!$is_logged){
            $query_result = array_merge($query_result, [
                "meta_query"=>[
                    ["key"=>"kind", "compare"=> "NOT EXISTS"]
                ]
            ]);
        }

        if (count($date_query) > 0) {
            $query_result = array_merge($query_result, ["date_query" => $date_query]);
        }

        $query = new WP_Query($query_result);
        $posts = $query->get_posts();
    */
}

?>
    <div x-init="loaderOn=false" class="container mx-auto py-5">
        <?php goBack() ?>
        <div class="flex ">
            <div class="w-1/3">
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
            </div>
        </div>
    </div>
<?php
get_footer();
