<script>
    import {t} from "svelte-i18n";

    import {fade, fly} from "svelte/transition";
    import {showSidebar} from "../core";
    import SearchExplorer from "./search/SearchExplorer.svelte";
    import {getContext, setContext} from "svelte";

    const {filter} = getContext("mc-context");

    setContext("slide-bar", {
        filter: {
            filterSpec: filter,
            items: [],
            registry: {}
        }
    });

</script>
{#if $showSidebar}
    <div transition:fade class="absolute top-0 left-0 w-full h-full bg-black opacity-50 z-index-30"
         on:click={()=>showSidebar.set(false)}></div>
    <div class="fixed top-0 left-0 w-full md:w-1/2 lg:w-1/3 h-full z-index-30" transition:fly={{x: -500, opacity: 1}}>
        <div class="h-full w-full bg-white space-y-3 shadow-lg shadow-dark-900">
            <div class="flex flex-row p-3">
                <div class="font-bold">{$t("advanced_search")}</div>
                <div class="flex-1"></div>
                <div class="font-bold cursor-pointer underline" on:click={()=>showSidebar.set(false)}>Close</div>
            </div>
            <SearchExplorer/>
        </div>

    </div>
{/if}
