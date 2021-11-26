<script>
    import {push, querystring} from "svelte-spa-router";

    import {default as Axios} from "axios";
    import {waitFor} from "./loader";
    import moment from "moment";
    import GoBack from "./GoBack.svelte";
    import Container from "./Container.svelte";
    import {queryBus} from "./lib";

    let queryResult;

    function doQuery(query) {
        queryResult = Axios
            .get(`index.php?${query}`, {params: {rest_route: '/mcplugin/v1/search'}})
            .then(x => {
                return x.data;
            });
    }

    queryBus.subscribe(query => {
        doQuery(query);
    })
</script>


<Container>
    <GoBack/>
    {#if queryResult}
        {#await waitFor(queryResult) }
            Loading...
        {:then items}
            <div class="space-y-2">
                {#each items as item}
                    <div
                            class="rounded shadow-lg shadow-dark-500 flex flex-col p-2 transition-all hover:(ring-1 ring-blue-300) cursor-pointer"
                            on:click={()=>push(`/post/${item.postType}/${item.id}`)}
                    >
                        <div class="flex flex-row items-center">
                            <div class="text-2xl font-bold">{item.title}</div>
                            <div class="flex-1"></div>
                            <div class="text-gray-700">{moment(item.publishDate).format("DD/MM/YYYY")} </div>
                        </div>
                        <div>{item.author}</div>
                    </div>
                {:else}
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="9" y1="10" x2="9.01" y2="10"></line>
                            <line x1="15" y1="10" x2="15.01" y2="10"></line>
                            <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
                        </svg>
                        <div class="font-bold">Empty result</div>
                    </div>
                {/each}
            </div>
        {:catch e}
            Something wrong
        {/await}
    {/if}
</Container>