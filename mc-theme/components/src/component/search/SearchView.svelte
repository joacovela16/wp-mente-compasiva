<script>
    import GoBack from "../GoBack.svelte";
    import Container from "../Container.svelte";
    import {queryBus, setScrollTop} from "../../core";
    import SearchLoader from "./SearchLoader.svelte";
    import {onMount} from "svelte";
    import {getLastPage, getLastQuery, setLastPage, setLastQuery} from "./search-lib";

    let page = getLastPage(), localQuery;
    let items = loadArray(page);

    function loadArray(init) {
        const result = [];
        for (let i = 1; i <= init; i++) {
            result.push(i)
        }
        return result;
    }

    function increase() {
        page++;
        setLastPage(page)
        items = loadArray(page);
    }

    onMount(() => {
        setScrollTop();

        queryBus.subscribe(query => {

            if (query !== undefined && query !== getLastQuery()) {
                page = 1;
                localQuery = query;
                setLastPage(1);
                setLastQuery(query);
            } else {
                page = getLastPage();
                localQuery = getLastQuery();
            }

            items = loadArray(page);
        });
    })
</script>


<Container>
    <GoBack/>

    <div class="font-bold text-lg border-b-blue-500 border-b-width-2">Results</div>
    {#if localQuery !== undefined}
        {#each items as item}
            <SearchLoader page={item} query={localQuery} on:click={()=> increase()} noClicked={items.length === item}/>
        {/each}
    {/if}
</Container>