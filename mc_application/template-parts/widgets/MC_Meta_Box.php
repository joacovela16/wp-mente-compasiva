<?php

abstract class MC_Meta_Box
{


    /**
     * Set up and add the meta box.
     */
    public static function add()
    {
        $screens = ['directory_catalog'];
        foreach ($screens as $screen) {
            add_meta_box(
                'mc_metabox_id',          // Unique ID
                'Resume this post', // Box title
                [self::class, 'html'],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }


    /**
     * Save the meta box selections.
     *
     * @param int $post_id The post ID.
     */
    public static function save(int $post_id)
    {
        if (array_key_exists(MC_METABOX_ABSTRACT, $_POST)) {
            update_post_meta($post_id, MC_METABOX_ABSTRACT, $_POST[MC_METABOX_ABSTRACT]);
        }

        if (array_key_exists(MC_METABOX_IMAGE, $_FILES)){
            $attachmentID = media_handle_upload(MC_METABOX_IMAGE, $post_id);

            $last_attachment = get_post_meta($post_id, MC_METABOX_IMAGE);
            if (!empty($last_attachment)){
                wp_delete_attachment($last_attachment);
            }

            $avatarURL = wp_get_attachment_url($attachmentID);
            update_post_meta($post_id, MC_METABOX_IMAGE, $attachmentID);
            update_post_meta($post_id, MC_METABOX_IMAGE."_id", $avatarURL);
        }
    }


    /**
     * Display the meta box HTML to the user.
     *
     * @param \WP_Post $post Post object.
     */
    public static function html($post)
    {
        $value = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);
        ?>

        <div class="space-y-3">
            <div class="flex flex-col">
                <div class="font-bold"><?= __("Abstract") ?></div>
                <textarea name="<?= MC_METABOX_ABSTRACT ?>" class="w-full" ><?= $value ?></textarea>
            </div>
            <div class="flex flex-col">
                <div class="font-bold"><?= __("Decorate with image") ?></div>
                <input type="file" accept="image/png, image/jpeg" name="<?= MC_METABOX_IMAGE ?>">
            </div>
        </div>
        <?php
    }
}

add_action('add_meta_boxes', ['MC_Meta_Box', 'add']);
add_action('save_post', ['MC_Meta_Box', 'save']);