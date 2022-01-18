<script>
    import {fly} from "svelte/transition";
    import {doGetter, isEmpty, nonEmpty} from "../lib/shared";
    import Field from "./shared/Field.svelte";

    export let config = {
        isLogged: true,
        url: 'https://google.com.uy',
        user_avatar_url: "",
        display_name: "Joaquin",
        user_email: "joaquinvelazquezcamacho@gmail.com",
        user_url: 'https://google.com.uy',
        description: "pepe",
        i18n: {}
    };

    const __ = doGetter(config.i18n || {});
    let show = false;
    let profile = {
        name: config.display_name,
        email: config.user_email,
        website: config.user_url,
        avatarUrl: config.user_avatar_url,
        description: config.description,
        file: undefined,
        current_pwd: undefined,
        new_password: undefined,
        confirm_password: undefined,
    };

    let selectedImage;
    let isUnlocked = true;
    let validation = null,
        validationType = "invalid_field",
        validationMessage = undefined;

    function submitProfile() {
        if (isUnlocked) {
            isUnlocked = false;

            if (isEmpty(profile.name)) {
                validationMessage = "name";
                return;
            }

            if (isEmpty(profile.email)) {
                validationMessage = "email";
                isUnlocked = true;
                return;
            }

            const formData = new FormData();

            if (nonEmpty(profile.current_pwd) || nonEmpty(profile.new_password) || nonEmpty(profile.confirm_password)) {
                if (isEmpty(profile.current_pwd)) {
                    validationMessage = `current_pwd`;
                    return;
                }

                if (isEmpty(profile.new_password) || isEmpty(profile.confirm_password) || profile.new_password !== profile.confirm_password) {
                    validationMessage = `confirm_password`;
                    return;
                }

                formData.append("current_pwd", profile.current_pwd);
                formData.append("new_password", profile.new_password);
                formData.append("confirm_password", profile.confirm_password);
            }

            if (profile.file) formData.append("file", profile.file);

            profile.name && formData.append("display_name", profile.name);
            profile.email && formData.append("user_email", profile.email);
            profile.website && formData.append("user_url", profile.website);
            profile.description && formData.append("description", profile.description);

            fetch("/wp-json/mcplugin/v1/user", {
                method: 'POST',
                headers: {
                    'accept': 'application/json',
                    "enctype": "multipart/form-data",
                    'X-WP-Nonce': MCApp.nonce
                },
                body: formData
            })
                .then(response => response.json())
                .then(response => {

                    validation = {};
                    validationType = "success";
                    validationMessage = "data_saved";

                    const ud = response.userData;
                    profile.name = ud.display_name || profile.name;
                    profile.email = ud.user_email || profile.email;
                    profile.website = ud.user_url || profile.website;
                    profile.description = ud.description || profile.description;

                    if (ud.user_avatar_url) {
                        profile.avatarUrl = ud.user_avatar_url
                    }

                    isUnlocked = true;
                    validation.validationType = "success";
                    validation.validationMessage = "Changes saved";

                })
                .catch(e => {
                    console.log(e);
                    isUnlocked = true;
                    validation.validationType = "error";
                    validation.validationMessage = "Can´t save changes";
                })
                .finally(() => {
                    setTimeout(() => validation = null, 3000);
                });
        }
    }
</script>
{#if config.isLogged}
    <div class="flex flex-row space-x-2 cursor-pointer items-center" on:click={()=>show=true}>
        <div>{profile.name}</div>
        <img src={profile.avatarUrl} alt="Avatar" class="w-28px h-28px rounded-full shadow-lg object-cover">
    </div>
{:else}
    <span>{__("Login")}/{__("register")}</span>
{/if}
{#if show}
    <div in:fly={{y:-10}} class="flex justify-center items-center fixed top-0 left-0 w-full h-full z-index-20">
        <div class="absolute top-0 left-0 bg-black opacity-90 w-full h-full"></div>
        <div class="rounded-lg w-auto max-h-4/5 bg-white overflow-hidden shadow-md flex flex-col z-index-10">
            <div class="flex flex-row py-5 px-2 bg-cool-gray-100 relative">
                <div class="flex flex-row space-x-2 items-center">
                    <img src={profile.avatarUrl} alt="img" class="w-32px h-32px rounded-full items-center shadow-lg object-cover">
                    <div>{config.display_name}</div>
                </div>
                <div class="flex-1"></div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"
                     on:click={()=>show=false}
                >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </div>
            <div class="flex-1 p-2 container mx-auto flex overflow-auto">
                <div class="space-y-5 flex flex-col bg-white p-3">
                    <div class="flex flex-col md:flex-row md:space-x-5">
                        <Field label={__("Name")}>
                            <input
                                    type="text"
                                    class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                    bind:value={profile.name}>
                        </Field>
                        <Field label={__("Email")}>
                            <input
                                    type="email"
                                    class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                    bind:value={profile.email}>
                        </Field>
                    </div>

                    <div class="flex flex-col md:flex-row md:space-x-5">
                        <Field label={__("Website")}>
                            <input
                                    type="text"
                                    class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                    bind:value={profile.website}>
                        </Field>
                        <Field label={__("Select image")}>
                            <label class="p-2 rounded ring-2 ring-gray-100 cursor-pointer hover:ring-blue-500 hover:shadow-lg">
                                <input
                                        type="file"
                                        class="hidden"
                                        accept="image/png,image/jpeg"
                                        on:change={e=> {profile.file= e.target.files[0]; console.log(profile)}}
                                >
                                {(profile.file && profile.file.name) || __("Select image")}
                            </label>

                        </Field>
                    </div>

                    <Field label={__("About me")}>
                        <textarea
                                class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                bind:value={profile.description}/>
                    </Field>
                    <details>
                        <summary class="font-bold">{__("Security")}</summary>
                        <div class="space-y-3 mt-5">
                            <Field label={__("Current password")}>
                                <input
                                        type="password"
                                        class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                        bind:value={profile.current_pwd}>
                            </Field>
                            <div class="flex flex-row space-x-5">
                                <Field label={__("New password")}>
                                    <input
                                            type="password"
                                            class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                            bind:value={profile.new_password}>
                                </Field>

                                <Field label={__("Confirm password")}>
                                    <input
                                            type="password"
                                            class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"
                                            bind:value={profile.confirm_password}>
                                </Field>
                            </div>
                        </div>
                    </details>
                </div>
            </div>
            <div class="py-3 px-2 bg-cool-gray-100">
                {#if validation}
                    {#if validation.validationType === "success"}
                        <div class="border-2 border-green-300 rounded-full p-2 my-2 bg-white">{validation.validationMessage}</div>
                    {:else}
                        <div class="border-2 border-red-300 rounded-full p-2 my-2 bg-white">{validation.validationMessage}</div>
                    {/if}
                {/if}
                <div class="flex flex-row space-x-2 font-bold items-center">
                    <div class="cursor-pointer" on:click={()=>show=false}>{__('Cancel')}</div>
                    <div class="flex-1"></div>
                    <div class="transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer" on:click={()=>submitProfile()}>{__('Save')}</div>
                </div>
            </div>
        </div>
    </div>
{/if}
<style lang="scss">
</style>