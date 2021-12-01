<script>
    import Filter from "./Filter.svelte";
    import {avatarUrl, showLoginModal, showProfileModal, showSidebar} from "../core";
    import {t} from "svelte-i18n";
    import {getContext} from "svelte";
    import {isDefined} from "../utils";

    const {profile} = getContext("mc-context");
    const profileSsDefined = isDefined(profile);
    let openFilters = false;
</script>
<div class="sticky top-0 left-0 w-full z-index-20 shadow-lg shadow-dark-500 bg-white px-2">
    <div class="flex flex-row p-2 items-center">
        <div class="flex-grow-0 cursor-pointer flex flex-row space-x-2" on:click={()=>showSidebar.set(true)}>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </span>
            <span class="font-bold">{$t("menu")}</span>
        </div>
        <div class="flex-1"></div>
        <Filter/>
        <div class="flex-1"></div>
        <div class="flex-grow-0 cursor-pointer font-bold">
            {#if profileSsDefined}
                <div class="flex flex-row space-x-2 cursor-pointer" on:click={()=>showProfileModal.set(true)}>
                    <div>{profile.display_name}</div>
                    <img src={$avatarUrl} class="w-28px h-28px rounded-full shadow-lg object-cover" alt="User profile"/>
                </div>
            {:else }
                <span on:click={()=>showLoginModal.set(true)}>{$t("login")}/{$t("register")}</span>
            {/if}
        </div>
    </div>


</div>
