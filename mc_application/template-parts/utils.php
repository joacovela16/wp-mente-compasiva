<?php

function goBack()
{
    echo div(["class" => "cursor-pointer font-bold underline", "@click" => "goBack()"], __("Back"));
}

function field(array $config): string
{
    $label = isset($config["label"]) ? div(['class' => "font-bold z-index-10"], render($config, 'label')) : '';

    return div(['class' => "flex flex-col space-y-2 flex-1 justify-center"], $label, render($config, 'content'));
}

function modal(array $config, string $handler): string
{
    $header = isset($config["header"]) ? div(["class" => 'font-bold z-index-10'], render($config, 'header')) : '';
    $footer = isset($config["footer"]) ? div(["class" => 'py-3 px-2 bg-cool-gray-100'], render($config, 'footer')) : '';

    return div(["class" => "flex justify-center items-center fixed top-0 left-0 w-full h-full z-index-20", "x-show" => $handler],
        div(["class" => "absolute top-0 left-0 bg-black opacity-90 w-full h-full"]),
        div(["class" => "rounded-lg w-auto max-h-4/5 bg-white overflow-hidden shadow-md flex flex-col z-index-10"],
            div(["class" => "flex flex-row py-5 px-2 bg-cool-gray-100 relative"],
                $header,
                div(['class' => "flex-1"]),
                div(['class' => "cursor-pointer z-index-10", "@click" => $handler . "=false"],
                    '    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>'
                )
            ),
            div(["class" => "flex-1 p-2 container mx-auto flex overflow-auto"], render($config, "content")),
            $footer
        )
    );
}


function render(array $data, $field)
{
    if (isset($data[$field])) {
        $fieldValue = $data[$field];
        if (is_string($fieldValue)) {
            if (function_exists($fieldValue)) {
                return ($fieldValue)();
            } else {
                return $fieldValue;
            }
        } else if (is_callable($fieldValue)) {
            return $fieldValue();
        }
    } else {
        return "";
    }
}

function mc_get_user_avatar_url()
{
    $currentUser = wp_get_current_user();
    $user_avatar_url = get_user_meta($currentUser->ID, "user_avatar_url", true);

    if (empty($user_avatar_url)) {
        $user_avatar_url = get_avatar_url($currentUser->ID);
    }

    return $user_avatar_url;
}
