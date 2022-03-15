<?php
$post = get_post();
$settings = get_option(MC_SETTING);
$permissions = $settings[MC_PERMISSIONS] ?? [];
$post_include = [];
/*$cond = is_user_logged_in() ? fn($x) => $x[MC_LOGGED_REQUIRED] === 'on' : fn($x) => empty($x[MC_LOGGED_REQUIRED]) || $x[MC_LOGGED_REQUIRED] !== 'on';

foreach ($permissions as $item) {
    if ($cond($item)) {
        $post_include = array_merge($post_include, $item[MC_POST_TYPES] ?? []);
    }
}*/
$allowed = true; //array_exists($post_include, fn($x) => $x === $post->post_type);

    setup_postdata($post);
    $tags = get_the_tags();
    $is_person = get_post_meta($post->ID, MC_KIND, true) === MC_PERSON;
    ?>
    <div class="mx-auto container md:mt-24 mt-48 h-screen" x-init="loaderOn=false">
        <?php
        if ($is_person):

            $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE, true);
            $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);
            $details = get_post_meta($post->ID, MC_USER_DETAILS, true);
            $location = $details['location'] ?? '';
            $country = $details['country'] ?? '';
            $email = $details['email'] ?? '';
            $website = $details['website'] ?? '';
            $phone = $details['phone'] ?? '';

            ?>
            <article>
                <div class="rounded-3xl overflow-hidden shadow-lg pb-10 bg-zinc-50 space-y-5">
                    <div class="relative">
                        <svg preserveAspectRatio="none" width="100%" height="392" viewBox="0 0 1920 966">
                                <g transform="translate(960,483) scale(1,1) translate(-960,-483)"><linearGradient id="lg-0.2760189174407055" x1="0" x2="1" y1="0" y2="0">
                                        <stop stop-color="#3b82f6" offset="0"></stop>
                                        <stop stop-color="#3b82f6" offset="1"></stop>
                                    </linearGradient><path d="" fill="url(#lg-0.2760189174407055)" opacity="0.4">
                                        <animate attributeName="d" dur="100s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="0s" values="M0 0L 0 757.285843974929Q 480 829.0540789219586  960 796.5625314758961T 1920 797.593217090224L 1920 0 Z;M0 0L 0 767.4691080922058Q 480 845.9032302826004  960 825.6200074236563T 1920 799.7667348879111L 1920 0 Z;M0 0L 0 805.239797118701Q 480 844.0462141396819  960 796.4749472048918T 1920 856.7062989544117L 1920 0 Z;M0 0L 0 757.285843974929Q 480 829.0540789219586  960 796.5625314758961T 1920 797.593217090224L 1920 0 Z"></animate>
                                    </path><path d="" fill="url(#lg-0.2760189174407055)" opacity="0.4">
                                        <animate attributeName="d" dur="100s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-33.333333333333336s" values="M0 0L 0 802.2955895305398Q 480 804.6621360307138  960 777.4958848811332T 1920 827.4003724484824L 1920 0 Z;M0 0L 0 824.0693395310052Q 480 871.0126001761827  960 851.1429529711223T 1920 865.6499451479178L 1920 0 Z;M0 0L 0 800.0001623290995Q 480 842.4648989162648  960 824.1682195705657T 1920 800.116601379561L 1920 0 Z;M0 0L 0 802.2955895305398Q 480 804.6621360307138  960 777.4958848811332T 1920 827.4003724484824L 1920 0 Z"></animate>
                                    </path><path d="" fill="url(#lg-0.2760189174407055)" opacity="0.4">
                                        <animate attributeName="d" dur="100s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-66.66666666666667s" values="M0 0L 0 830.2413258021917Q 480 755.4779864484038  960 724.9812523214165T 1920 779.1382823024304L 1920 0 Z;M0 0L 0 769.999377743319Q 480 813.4188543610501  960 790.0873669446495T 1920 751.648244740765L 1920 0 Z;M0 0L 0 770.4727082288574Q 480 788.2777921532546  960 761.9636693412018T 1920 754.8361132214287L 1920 0 Z;M0 0L 0 830.2413258021917Q 480 755.4779864484038  960 724.9812523214165T 1920 779.1382823024304L 1920 0 Z"></animate>
                                    </path></g>
                            </svg>
                        <?php if (is_numeric($image_url)): ?>
                            <img class="rounded-full w-64 ring-4 ring-8 ring-white shadow-lg object-cover absolute left-[calc(50%-8rem)] top-10" src="<?= wp_get_attachment_url($image_url) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="text-5xl text-center mt-4"><?= $post->post_title ?></div>
                    <div class="text-lg text-center mt-4"><?= $location . ' - ' . $country ?></div>
                    <div class="w-2/3 mx-auto ">
                        <div class="italic text-center"><?= $abstract ?></div>
                    </div>
                    <div class="mx-auto mb-5">
                        <div class="flex flex-col md:flex-row divide-x-2 flex-1 items-center">
                            <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                                </svg>
                                <span><?= $phone ?></span>
                            </div>
                            <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <polyline points="3 7 12 13 21 7"></polyline>
                                </svg>
                                <span>
                                    <a href="mailto:<?= $email ?>"><?= $email ?></a>
                                </span>
                            </div>
                            <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-dribbble" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                     stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M9 3.6c5 6 7 10.5 7.5 16.2"></path>
                                    <path d="M6.4 19c3.5 -3.5 6 -6.5 14.5 -6.4"></path>
                                    <path d="M3.1 10.75c5 0 9.814 -.38 15.314 -5"></path>
                                </svg>
                                <span>
                                    <a href="<?= $website ?>" target="_blank"><?= $website ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php else: ?>
            <article>
                <div class="space-y-10">
                    <header class="font-bold text-3xl border-b-blue-500 border-b-2 p-1 flex flex-row items-center">
                        <div><?php the_title() ?></div>
                        <div class="flex-1"></div>
                        <div class="text-base">
                            <div><?php the_date() ?></div>
                            <div> <?= __("by") . " " . get_the_author() ?></div>
                        </div>
                    </header>
                    <div>
                        <?php the_content(); ?>
                    </div>

                    <?php if (comments_open() || get_comments_number()) : ?>
                        <?php comments_template(); ?>
                    <?php endif; ?>
                </div>

            </article>
        <?php endif; ?>
    </div>

<?php
