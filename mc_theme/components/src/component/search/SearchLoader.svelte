<script>
    import {push} from "svelte-spa-router";
    import {default as Axios} from "axios";
    import {t} from "svelte-i18n";

    import moment from "moment";
    import {createEventDispatcher} from "svelte";
    import {waitFor} from "../../loader";

    const eventDispatcher = createEventDispatcher();
    export let page = 1;
    export let query;
    export let noClicked = false;

    function doQuery(query) {
        return Axios
            .get(`index.php?${query}`, {params: {rest_route: '/mcplugin/v1/search', page}})
            .then(x => {
                return x.data;
            });
    }

    function handleClick() {
        noClicked = false;
        eventDispatcher("click");
    }
</script>
{#await (page === 1 ? waitFor(doQuery(query)) : doQuery(query))}
    <div class="font-bold text-center">{$t("loading")}...</div>
{:then items}
    {#each items as item}
        <div
                class="rounded ring-1 ring-gray-200 shadow shadow-dark-500 flex flex-col p-2 transition-all hover:(ring-1 ring-blue-500) cursor-pointer"
                on:click={()=>push(`/post/${item.postType}/${item.id}`)}
        >
            <div class="flex flex-row items-center">
                <div class="text-lg font-bold">{item.title}</div>
                <div class="flex-1"></div>
                <div class="text-gray-700">
                    <div class="flex flex-row space-x-5">
                        <b>{item.author}</b>
                        <b>{$t("published")}</b>: {moment(item.publishDate).format("DD/MM/YYYY")}
                    </div>
                </div>
            </div>
        </div>
    {:else}

        <!--<div class="flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="9" y1="10" x2="9.01" y2="10"></line>
                <line x1="15" y1="10" x2="15.01" y2="10"></line>
                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
            </svg>
            <div class="font-bold">{$t("empty_result")}</div>
        </div>-->
    {/each}

    {#if items[0] === undefined}
        <div class="font-bold text-center">{$t("empty_result")}</div>
    {:else}
        {#if noClicked}
            <div class="cursor-pointer text-center font-bold" on:click={()=>handleClick()}>
                Show more
            </div>
        {/if}
    {/if}
{:catch e}
    pepe
{/await}
<style lang="scss">
</style>