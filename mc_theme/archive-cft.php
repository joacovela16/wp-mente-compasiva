<?php

global $wp_query;
const SORTBY = "orderby";
const PAFTER = "after";
const PBEFORE = "before";

$search_fields = [MC_GENDER, MC_COUNTRY, MC_CITY, SORTBY, PBEFORE, PAFTER, 'q', 'post_type', MC_MODE, MC_PROFESSION, MC_WORKS_WITH];

$orderby = $_GET[SORTBY] ?? '';

$selected_countries = $_GET[MC_COUNTRY] ?? [];
$selected_countries = is_array($selected_countries) ? $selected_countries : [$selected_countries];
$gender = $_GET[MC_GENDER] ?? [];
$work_with = $_GET[MC_WORKS_WITH] ?? [];
$work_mode = $_GET[MC_MODE] ?? '';
$professions = get_option(MC_PROFESSION_OPTIONS, []);
$profession = $_GET[MC_PROFESSION] ?? [];
get_header();
?>

    <div class="container py-4 mx-auto px-2 md:px-8" x-init="loaderOn=false">
        <div class="p-2 rounded bg-blue-50">
            <p class="font-bold">Directorio de Profesionales de Salud Mental Con Formación en Terapia Centrada en la Compasión (CFT)</p>
            <br>
            <p class="text-sm">
                En este directorio puedes encontrar un listado de profesionales de salud mental (psicólogos y psiquiatras) que utilizan el enfoque de la Terapia Centrada en la Compasión (CFT). Quienes
                aparecen en esta lista han completado los <a class="text-blue-500 font-bold" href="https://cultivarlamente.com/formacion-cft/">tres módulos de formación</a> (introductorio, de
                profundización y
                avanzado)
                diseñados
                e implementados por Gonzalo Brito en diversos
                países de
                habla hispana. Puedes ver una descripción de esa formación <a class="text-blue-500 font-bold"
                                                                              href="https://cultivarlamente.com/wp-content/uploads/2019/04/Descripci%C3%B3n-Programa-3-m%C3%B3dulos-CFT.pdf">en este
                    documento</a>.
            </p>
            <br>
            <p class="text-sm font-bold">
                Nota importante: Este directorio se presenta con la intención de facilitar la visibilidad y el contacto con profesionales interesados y formados en este enfoque. Sin embargo,
                debido
                a que no es
                posible hacer un seguimiento del trabajo de cada uno, el que una persona aparezca en este directorio no necesariamente implica una recomendación de sus servicios de parte de
                Gonzalo Brito o Cultivar la Mente.
            </p>
        </div>
        <div class="flex flex-col md:flex-row space-x-3 mt-3">
            <form class="w-2/7 flex-grow-0" method="get">
                <?php foreach ($_GET as $k => $v): ?>
                    <?php if (!empty(array_filter($search_fields, fn($x) => $x === $k))): continue; endif; ?>
                    <input type="hidden" name="<?= $k ?>" value="<?= get_query_var($k) ?>">
                <?php endforeach; ?>

                <div class="form-control w-full ">
                    <label class="label">
                        <span class="label-text"><?= __('Search') ?></span>
                    </label>
                    <input type="text" class="input input-bordered w-full" name="q" title="<?= __('cft-search-description') ?>" placeholder="<?= __('cft-search-description') ?>" value="<?= $_GET['q']
                    ?? '' ?>">
                </div>
                <div class="flex flex-row gap-2">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Country') ?></span>
                        </label>
                        <input type="text" class="input input-bordered  w-full" name="<?= MC_COUNTRY ?>" value="<?= $_GET[MC_COUNTRY] ?? '' ?>">
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('City') ?></span>
                        </label>
                        <input type="text" class="input input-bordered  w-full" name="<?= MC_CITY ?>" value="<?= $_GET[MC_CITY] ?? '' ?>">
                    </div>
                </div>
                <div class="form-control w-full ">
                    <label class="label">
                        <span class="label-text"><?= __('Profession') ?></span>
                    </label>
                    <div class="space-y-1">
                        <?php foreach (get_option(MC_PROFESSION_OPTIONS, []) as $item): ?>
                            <label class="flex flex-row space-x-3 items-center cursor-pointer">
                                <input type="checkbox" class="checkbox" value="<?= $item ?>" name="<?= MC_PROFESSION ?>[]" <?= in_array($item, $profession) ? 'checked' : '' ?>>
                                <span><?= $item ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-control w-full ">
                    <label class="label">
                        <span class="label-text"><?= __('Work mode') ?></span>
                    </label>
                    <select class="select select-bordered w-full" name="<?= MC_MODE ?>">
                        <option value="" <?= $work_mode === '' ? 'selected' : '' ?> > <?= __('select') ?></option>
                        <option <?= $work_mode === 'onsite' ? 'selected' : '' ?> value="onsite"> <?= __('onsite') ?></option>
                        <option <?= $work_mode === 'online' ? 'selected' : '' ?> value="online"> <?= __('online') ?></option>
                        <option <?= $work_mode === 'online-onsite' ? 'selected' : '' ?> value="online-onsite"> <?= __('online-onsite') ?></option>
                    </select>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text"><?= __('Works with') ?></span>
                    </label>
                    <div class="space-y-1">
                        <?php foreach (get_option(MC_WORKS_WITH, []) as $item): ?>
                            <label class="flex flex-row space-x-3 items-center cursor-pointer">
                                <input type="checkbox" class="checkbox" value="<?= $item ?>" name="<?= MC_WORKS_WITH ?>[]" <?= in_array($item, $work_with) ? 'checked' : '' ?>>
                                <span><?= $item ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text"><?= __('Gender') ?></span>
                    </label>
                    <div class="space-y-1">
                        <label class="flex flex-row space-x-3 items-center cursor-pointer">
                            <input type="checkbox" class="checkbox">
                            <span><?= __('Anyone') ?></span>
                        </label>
                        <label class="flex flex-row space-x-3 items-center cursor-pointer">
                            <input type="checkbox" class="checkbox" value="female" name="<?= MC_GENDER ?>[]" <?= in_array("female", $gender) ? 'checked' : '' ?>>
                            <span><?= __('Female') ?></span>
                        </label>
                        <label class="flex flex-row space-x-3 items-center cursor-pointer">
                            <input type="checkbox" class="checkbox" value="male" name="<?= MC_GENDER ?>[]" <?= in_array("male", $gender) ? 'checked' : '' ?>>
                            <span><?= __('Male') ?></span>
                        </label>
                    </div>
                </div>
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text"><?= __('Sort by') ?></span>
                    </label>
                    <select class="select select-bordered w-full h-18 overflow-hidden" name="<?= SORTBY ?>">
                        <option <?= $orderby === '' ? 'selected' : '' ?> value=""><?= __('None') ?></option>
                        <option <?= $orderby === 'title' ? 'selected' : '' ?> value="title"><?= __('Name') ?></option>
                        <option <?= $orderby === 'date' ? 'selected' : '' ?> value="date"><?= __('Date') ?></option>
                    </select>
                </div>

                <button class="mt-3 btn btn-primary btn-sm"><?= __('Search') ?></button>
            </form>
            <div class="flex-1 p-1 space-y-1">
                <?php
                $found_posts = $wp_query->found_posts;
                $previous_link = get_previous_posts_link("&laquo; " . __('previous'));
                $next_link = get_next_posts_link(__('next') . " &raquo;");
                $result_text = $found_posts . ' ' . __('result') . ($found_posts > 1 ? 's' : '') . ' ' . __('match_with_result');
                ?>

                <div class="flex flex-col sm:flex-row items-center border-b border-b-blue-500 p-1 space-x-2">
                    <div class="flex-1"><?= $result_text ?></div>
                    <div class="flex flex-row items-center space-x-3">
                        <?php if (!is_null($previous_link)): ?>
                            <div class="text-blue-500"><?= $previous_link ?></div>
                        <?php endif; ?>
                        <?php if (!is_null($next_link)): ?>
                            <div class="text-blue-500"><?= $next_link ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="space-y-2">
                    <?php
                    if (have_posts()) {
                        while (have_posts()): the_post();
                            $post = get_post();
                            render_cft($post);
                        endwhile;
                    }
                    ?>
                </div>
                <?php if (!(is_null($next_link) && is_null($previous_link))): ?>
                    <div class="flex flex-row items-center border-t border-t-blue-500 p-1 space-x-2">
                        <div class="flex-1"></div>
                        <?php if (!is_null($previous_link)): ?>
                            <div class="text-blue-500"><?= $previous_link ?></div>
                        <?php endif; ?>
                        <?php if (!is_null($next_link)): ?>
                            <div class="text-blue-500"><?= $next_link ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
get_footer();