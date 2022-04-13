<?php

class MCPermissionLib
{
    public function init()
    {
        add_action('pre_get_posts', [$this, "pre_get_posts"], 10, 1);
        add_filter('query_vars', [$this, 'query_vars']);
        add_filter('map_meta_cap', [$this, 'map_meta_cap'], 10, 4);
    }

    function map_meta_cap($caps, $cap, $user_id, $args)
    {
        if ($cap === 'delete_post' && get_post_type() === CFT_DIRECTORY) {
            $caps[] = 'do_not_allow';
        }
        return $caps;
    }

    public function query_vars($qvars)
    {
        $qvars[] = "q";
        $qvars[] = "t";
        return $qvars;
    }

    public function pre_get_posts(WP_Query $query)
    {
        if (!empty($query->query) && ($query->query['post_type'] ?? '') === CFT_DIRECTORY) {

            $query_term = $_GET['q'] ?? null;
            $meta_query = ['relation' => 'AND'];

            // country filter
            $countries = $_GET[MC_COUNTRY] ?? [];
            $countries = is_array($countries) ? $countries : [$countries];
            $countries = array_filter($countries, fn($x) => !empty($x));
            $country_query = array_map(fn($x) => ['key' => MC_COUNTRY, 'value' => $x, 'compare' => 'like'], $countries);

            if (count($country_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$country_query];
            }

            $city = $_GET[MC_CITY] ?? [];
            $city = is_array($city) ? $city : [$city];
            $city = array_filter($city, fn($x) => !empty($x));
            $city_query = array_map(fn($x) => ['key' => MC_CITY, 'value' => $x, 'compare' => 'like'], $city);

            if (count($city_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$city_query];
            }

            $gender = $_GET[MC_GENDER] ?? [];
            $gender = is_array($gender) ? $gender : [$gender];
            $gender = array_filter($gender, fn($x) => !empty($x));
            $gender_query = array_map(fn($x) => ['key' => MC_METABOX_GENDER, 'value' => $x, 'compare' => '='], $gender);

            if (count($gender_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$gender_query];
            }

            $work_mode = $_GET[MC_MODE] ?? [];
            $work_mode = is_array($work_mode) ? $work_mode : [$work_mode];
            $work_mode = array_filter($work_mode, fn($x) => !empty($x));
            $work_mode_query = array_map(fn($x) => ['key' => MC_MODE, 'value' => $x, 'compare' => '='], $work_mode);

            if (count($work_mode_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$work_mode_query];
            }

            $lang = $_GET[MC_LANGUAGE] ?? [];
            $lang = is_array($lang) ? $lang : [$lang];
            $lang = array_filter($lang, fn($x) => !empty($x));
            $lang_query = array_map(fn($x) => ['key' => MC_LANGUAGE, 'value' => $x, 'compare' => '='], $lang);

            if (count($lang_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$lang_query];
            }

            $profession = $_GET[MC_PROFESSION] ?? [];
            $profession = is_array($profession) ? $profession : [$profession];
            $profession = array_filter($profession, fn($x) => !empty($x));
            $profession_query = array_map(fn($x) => ['key' => MC_PROFESSION, 'value' => $x, 'compare' => '='], $profession);

            if (count($profession_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$profession_query];
            }

            $meta_query[] = ['key' => MC_ENABLED, 'value' => 'on', 'compare' => '='];

            $works_with = $_GET[MC_WORKS_WITH] ?? [];
            $works_with = is_array($works_with) ? $works_with : [$works_with];
            $works_with = array_filter($works_with, fn($x) => !empty($x));

            $works_with_query = array_map(fn($x) => ['key' => MC_WORKS_WITH, 'value' => $x, 'compare' => '='], $works_with);

            if (count($works_with_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$works_with_query];
            }

            $has_date = false;
            $container = [];

            if (!empty($_GET['before'])) {
                $has_date = true;
                $container['before'] = $_GET['before'];
            }

            if (!empty($_GET['after'])) {
                $has_date = true;
                $container['after'] = $_GET['after'];
            }

            if ($has_date) {
                $container['inclusive'] = true;
                $query->set('date_query', [$container]);
            }

            $orderby = $_GET['orderby'] ?? "";
            if (!empty($orderby)) {
                if ($orderby === 'title') {
                    $query->set('order', 'ASC');
                } elseif ($orderby === 'date') {
                    $query->set('order', 'DESC');
                }
            }

            if (!empty($query_term)) {

                $query_tmp = [
                    ['key' => MC_COUNTRY, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_CITY, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_METABOX_GENDER, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_MODE, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_PROFESSION, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_WORKS_WITH, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_NAME, 'value' => $query_term, 'compare' => 'like'],
                    ['key' => MC_ABSTRACT, 'value' => $query_term, 'compare' => 'like'],
                ];

                $meta_query[] = [
                    'relation' => 'OR',
                    ...$query_tmp
                ];
            }

            if (count($meta_query) > 1) {
                $query->set('meta_query', $meta_query);
            }

            $query->set("posts_per_page", 12);
            $query->set("post_status", "publish");

        }
    }

    public static function update_post_permissions($post_id, array $permissions)
    {

        delete_post_meta($post_id, MC_METABOX_PERMISSION_RULE);

        foreach ($permissions as $v) {
            foreach ($v[MC_CAPABILITIES] as $datum) {
                $value = $v[MC_ID] . "::" . $datum;
                add_post_meta($post_id, MC_METABOX_PERMISSION_RULE, $value);
            }
        }
        update_post_meta($post_id, MC_METABOX_PERMISSION, $permissions);
    }

}
