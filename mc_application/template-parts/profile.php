<?php
if (is_user_logged_in()) {
    $url = mc_get_user_avatar_url();
    $current_user = wp_get_current_user();
    $display_name = $current_user->display_name;
    $user_email = $current_user->user_email;
    $user_url = $current_user->user_url;
    $description = $current_user->description;

    echo modal([
        "header" =>
            div(["class" => "flex flex-row space-x-2 items-center"],
                img( ["src" => $url, "class" => "w-32px h-32px rounded-full items-center shadow-lg object-cover", "x-ref"=>"profileAvatar"]),
                div([], $current_user->display_name)
            ),
        "content" =>
            div(["class" => "space-y-5 flex flex-col bg-white p-3"],
                div(["class" => "flex flex-col md:flex-row md:space-x-5"],
                    field([
                        "label" => __('Name'),
                        "content" => el(
                            'input',
                            [
                                "type" => 'text',
                                "class" => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                "x-init" => "profile.name='$display_name'",
                                "x-model" => "profile.name",
                            ])
                    ]),

                    field([
                        "label" => __('Email'),
                        "content" =>
                            el(
                                'input',
                                [
                                    "type" => 'email',
                                    "class" => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                    "x-init" => "profile.email='$user_email'",
                                    "x-model" => "profile.email",
                                ])

                    ])
                ),
                div(['class' => "flex flex-col md:flex-row md:space-x-5"],
                    field([
                        "label" => __('Website'),
                        "content" => el(
                            'input',
                            [
                                "type" => 'text',
                                "class" => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                "x-init" => "profile.website='$user_url'",
                                "x-model" => "profile.website",
                            ])
                    ]),

                    field([
                        "label" => __("Select image"),
                        "content" =>
                            el("label", ["class" => 'p-2 rounded ring-2 ring-gray-100 cursor-pointer hover:ring-blue-500 hover:shadow-lg'],
                                input([
                                    'class' => 'hidden',
                                    'type' => 'file',
                                    'x-ref'=> 'profileFile',
                                    'accept' => 'image/png,image/jpeg',
                                    '@change' => 'profile.file=$refs.profileFile.files[0]'
                                ]),
                                __("Select image")
                            )
                    ])
                ),
                field([
                    "label" => __('About you'),
                    "content" =>
                        textarea([
                            'class' => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                            'x-init'=> "profile.description='$description'",
                            'x-model' => "profile.description"
                        ])
                ]),
                details(
                    summary(['class' => 'font-bold'], __("Security")),
                    div(['class' => 'space-y-3 mt-5'],
                        field([
                            "label" => __("Current password"),
                            "content" =>
                                input([
                                    'type' => 'password',
                                    'class' => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                    'x-model' => 'profile.current_pwd'
                                ])
                        ]),
                        div(['class' => 'flex flex-row space-x-5'],
                            field([
                                "label" => __("New password"),
                                "content" =>
                                    input([
                                        'type' => 'password',
                                        'class' => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                        'x-model' => 'profile.new_password'
                                    ])
                            ]),
                            field([
                                "label" => __("Confirm password"),
                                "content" =>
                                    input([
                                        'type' => 'password',
                                        'class' => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                        'x-model' => 'profile.confirm_password'
                                    ])
                            ])
                        )
                    )
                )
            ),
        'footer' => div(['class' => 'flex flex-row space-x-2 font-bold items-center'],
            div(['class' => 'cursor-pointer', '@click' => 'profileOn=false'], __('Cancel')),
            div(['class' => 'flex-1']),
            div(['class' => 'transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer', '@click' => 'submitProfile()'], __("Apply changes"))
        )
    ],
        "profileOn"
    );

}


