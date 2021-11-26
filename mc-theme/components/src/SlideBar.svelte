<script>
    import {fly} from "svelte/transition";
    import {filterBuilder, showSlideBar} from "./lib";
    import SearchExplorer from "./search/SearchExplorer.svelte";
    import {setContext} from "svelte";

    setContext("slide-bar", {
        filter: {
            filterSpec: filterBuilder(),
            items: [],
            registry: {}
        }
    });

</script>
{#if $showSlideBar}
    <nav
            transition:fly={{x: -500, opacity: 1}}
            class="absolute top-0 left-0 w-100 h-full bg-white
                z-index-30 space-y-3 border-r-2 border-r-dark-400">
        <div class="flex flex-row bg-dark-400 text-white p-3">
            <div class="font-bold">ADVANCED SEARCH</div>
            <div class="flex-1"></div>
            <div class="font-bold cursor-pointer underline" on:click={()=>showSlideBar.set(false)}>Close</div>
        </div>
        <SearchExplorer/>
    </nav>
{/if}
