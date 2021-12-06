<?php
add_action("edit_user_profile_update", "mc_edit_user_profile_update");
add_action("edit_user_profile", "mc_edit_user_profile");
function mc_edit_user_profile()
{
    $user_id = $_GET["user_id"];
    $post_id = get_user_meta(intval($user_id), "user_post_id", true);
    $value = get_post_meta($post_id, "is_cft", true);
    $is_cft = $value === "1" ? "checked" : "";

    ?>

    <table class="form-table">
        <tr>
            <th><?= __("Coursed CFT") ?></th>
            <td>
                <label>
                    <input name="mc_coursed_cft" type="checkbox" <?= $is_cft ?>>
                </label>
            </td>
        </tr>
    </table>
    <?php
}

function mc_edit_user_profile_update()
{
    $user_id = $_POST["user_id"];
    if (!is_null($user_id)) {
        $is_cft = isset($_POST["mc_coursed_cft"]);
        $post_id = get_user_meta(intval($user_id), "user_post_id", true);
        update_post_meta($post_id, "is_cft", $is_cft);
    }
}