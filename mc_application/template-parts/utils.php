<?php

function goBack()
{
    ?>
    <div @click="goBack()" class="cursor-pointer font-bold underline"><?= __('back') ?></div>
    <?php
}

function field(array $config)
{
    ?>
    <div class="flex flex-col space-y-2 flex-1 justify-center">
        <? if (isset($config["label"])) : ?>
            <div class="font-bold z-index-10">
                <? render($config, "label"); ?>
            </div>
        <? endif; ?>
        <? render($config, "content"); ?>
    </div>
    <?php
}

function modal(array $config, string $handler)
{
    ?>
    <div class="flex justify-center items-center fixed top-0 left-0 w-full h-full z-index-20" x-show="<?= $handler ?>">
        <div class="absolute top-0 left-0 bg-black opacity-90 w-full h-full"></div>
        <div class="rounded-lg w-auto max-h-4/5 bg-white overflow-hidden shadow-md flex flex-col z-index-10">

            <div class="flex flex-row py-5 px-2 bg-cool-gray-100 relative">
                <? if (isset($config["header"])) : ?>
                    <div class="font-bold z-index-10">
                        <?php render($config, "header"); ?>
                    </div>
                <? endif; ?>
                <div class="flex-1"></div>
                <div class="cursor-pointer z-index-10" @click="<?= $handler ?>=false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>

            </div>
            <div class="flex-1 p-2 container mx-auto flex overflow-auto">
                <? if (isset($config["content"])) :
                    render($config, "content");
                endif; ?>
            </div>
            <? if (isset($config["footer"])) : ?>
                <div class="py-3 px-2 bg-cool-gray-100">
                    <?php render($config, "footer") ?>
                </div>
            <? endif; ?>
        </div>
    </div>
    <?php
}


function render(array $data, $field)
{
    if (isset($data[$field])) {
        $fieldValue = $data[$field];
        if (is_string($fieldValue)) {
            if (function_exists($fieldValue)) {
                ($fieldValue)();
            } else {
                echo $fieldValue;
            }
        } else if (is_callable($fieldValue)) {
            $fieldValue();
        }
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
function context($data, $inner) {
    return $inner((object)$data);
}
function div($attr, $inner = null)
{
    el("div", $attr, $inner);
}

function el(string $tag, $attr, $inner = null)
{
    $finalAttr = [];
    if (is_array($attr)) {
        foreach ($attr as $key => $value) {
            if (is_null($value)) {
                $finalAttr[] = "$key";
            } else {
                $finalAttr[] = "$key=\"$value\"";
            }
        }
    } elseif (is_string($attr)) {
        $inner = $attr;
    }

    $attrs = join(" ", $finalAttr);

    echo "<$tag $attrs>";

    if (!is_null($inner)) {
        if (is_callable($inner))
            $inner();
        elseif (is_string($inner))
            echo $inner;
    }

    echo "</$tag>";
}