<script>
    import {getContext} from "svelte";
    import {t} from "svelte-i18n";
    import {default as Axios} from "axios";

    import Modal from "./Modal.svelte";
    import {avatarUrl as avatarRef, showProfileModal} from "../core";
    import Field from "./Field.svelte";
    import {isEmpty, nonEmpty} from "../utils";


    const {profile, nonce, logoutUrl} = getContext("mc-context");
    const userData = {
        name: profile.display_name,
        email: profile.user_email,
        website: profile.user_url,
        description: profile.description,
        current_pwd: undefined,
        new_password: undefined,
        confirm_password: undefined,
    };
    avatarRef.set(profile.user_avatar_url);
    console.log(profile.user_avatar_url)

    let isUnlocked = true;
    let validationType, validationMessage;
    let profilePictureRef;

    function handleApplyChanges() {
        if (isUnlocked) {

            Axios
            // validate
            validationType = "invalid_field";
            validationMessage = undefined;

            if (isEmpty(userData.name)) {
                validationMessage = "name";
                return;
            }

            if (isEmpty(userData.email)) {
                validationMessage = "email";
                isUnlocked = true;
                return;
            }

            const formData = new FormData();

            if (nonEmpty(userData.current_pwd) || nonEmpty(userData.new_password) || nonEmpty(userData.confirm_password)) {
                if (isEmpty(userData.current_pwd)) {
                    validationMessage = `current_pwd`;
                    return;
                }

                if (isEmpty(userData.new_password) || isEmpty(userData.confirm_password) || userData.new_password !== userData.confirm_password) {
                    validationMessage = `confirm_password`;
                    return;
                }

                formData.append("current_pwd", userData.current_pwd);
                formData.append("new_password", userData.new_password);
                formData.append("confirm_password", userData.confirm_password);
            }

            const file = profilePictureRef.files[0];

            if (file) formData.append("file", file);

            userData.name && formData.append("display_name", userData.name);
            userData.email && formData.append("user_email", userData.email);
            userData.website && formData.append("user_url", userData.website);
            userData.description && formData.append("description", userData.description);

            isUnlocked = false;

            Axios
                .post("index.php", formData, {
                    params: {
                        rest_route: '/mcplugin/v1/user'
                    },
                    headers: {
                        "enctype": "multipart/form-data",
                        'X-WP-Nonce': nonce
                    }
                })
                .then(x => {

                    isUnlocked = true;

                    const data = x.data;
                    if (data.isSuccess) {
                        validationType = "success";
                        validationMessage = "data_saved";

                        const ud = data.userData;
                        userData.name = ud.display_name || userData.name;
                        userData.email = ud.user_email || userData.email;
                        userData.website = ud.user_url || userData.website;
                        userData.description = ud.description || userData.description;
                        userData.description = ud.description || userData.description;
                        ud.user_avatar_url && avatarRef.set(ud.user_avatar_url);

                    } else {
                        return Promise.reject("error updating user")
                    }
                })
                .catch(e => {
                    isUnlocked = true;
                    validationType = "unexpected_error";
                    validationMessage = "error_updating_user";
                })

        }

    }
</script>
<Modal bind:open={$showProfileModal}>
    <div slot="header" class="flex flex-row space-x-2">
        <img src={$avatarRef} alt="avatar" class="w-32px h-32px rounded-full items-center shadow-lg object-cover">
        <div>{profile.display_name}</div>
    </div>
    <div slot="content" class="space-y-5 flex flex-col">
        <div class="flex flex-row space-x-5">
            <Field label={$t("name")}>
                <input type="text"
                       class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                       bind:value={userData.name}>
            </Field>
            <Field label={$t("email")}>
                <input type="email"
                       class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                       bind:value={userData.email}>
            </Field>
        </div>
        <div class="flex flex-row space-x-5">
            <Field label={$t("website")}>
                <input type="url"
                       class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                       bind:value={userData.website}>
            </Field>
            <Field label={$t("change_avatar")}>
                <label class="p-2 rounded ring-2 ring-gray-100 cursor-pointer hover:(ring-blue-500 shadow-lg)">
                    <input bind:this={profilePictureRef} type="file"
                           class="hidden"
                           accept="image/png, image/jpeg">
                    {$t('select_image')}
                </label>
            </Field>
        </div>
        <Field label={$t("about_you")}>
            <textarea class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                      bind:value={userData.description}></textarea>
        </Field>
        <details>
            <summary class="font-bold">{$t("change_password")}</summary>
            <div class="space-y-3 mt-5">
                <Field label={$t("current_pwd")}>
                    <input type="password"
                           class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                           bind:value={userData.current_pwd}>
                </Field>
                <div class="flex flex-row space-x-5">
                    <Field label={$t("new_pwd")}>
                        <input type="password"
                               class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                               bind:value={userData.new_password}>
                    </Field>
                    <Field label={$t("confirm_pwd")}>
                        <input type="password"
                               class="appearance-none p-2 rounded ring-2 {userData.new_password === userData.confirm_password ? 'ring-green-500' : 'ring-red-500'} focus:(ring-blue-500 shadow-lg)"
                               bind:value={userData.confirm_password}>
                    </Field>
                </div>
            </div>
        </details>
        {#if validationMessage}
            {#if validationType === "success"}
                <div class="space-x-2 text-blue-500 flex flex-row font-bold">
                    <span>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                             height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 12l5 5l10 -10"></path>
                            <path d="M2 12l5 5m5 -5l5 -5"></path>
                        </svg>
                    </span>
                    <span>{$t(validationMessage)}</span>
                </div>
            {:else}
                <div class="space-x-2 text-red-500 flex flex-row font-bold">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                             fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 9v2m0 4v.01"></path>
                            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path>
                        </svg>
                    </span>
                    <span> {$t(validationType)}: {$t(validationMessage)}</span>
                </div>
            {/if}
        {/if}
        <div class="flex-1"></div>
        <div class="text-right">
            <a href={logoutUrl} class="font-bold space-x-1 items-center flex text-red-600">
                <span class="inline-block">{$t('logout')}</span>
                <span class="inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                         height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                        <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
    <div slot="footer">
        {#if isUnlocked}
            <div class="flex flex-row space-x-2 font-bold items-center">
                <div class="cursor-pointer" on:click={()=>showProfileModal.set(false)}>{$t('cancel')}</div>
                <div class="flex-1"></div>
                <div class="transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer"
                     on:click={()=>handleApplyChanges()}>
                    {$t('apply_changes')}
                </div>
            </div>
        {:else}
            <span class="text-blue-500">{$t('loading')}...</span>
        {/if}
    </div>
</Modal>
<style lang="scss">
</style>