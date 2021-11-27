<script>
    import {t} from "svelte-i18n";
    import {queryBus} from "../core";
    import {push} from "svelte-spa-router";

    let freeSearch;


    function itemUpdater(data, index) {
        data[index] = !data[index];
        updateQuery()
    }

    function updateQuery() {
        const freeSearchNorm = normalizeString(freeSearch);
        push("/search");
        queryBus.set(`s=${freeSearchNorm}`)
    }

    function normalizeString(str) {
        return str === undefined || str === null ? str : str.trim().length === 0 ? undefined : str;
    }

    function getItems(selections, dataSource) {
        const lg = selections.length;
        const result = [];

        for (let i = 0; i < lg; i++) {
            const element = selections[i];
            if (element) {
                result.push(dataSource[i]);
            }
        }

        return result;
    }

    function keydownHandler(e) {
        if (e.key==="Enter"){
            updateQuery();
        }
    }
</script>
<div class="flex flex-row md:(flex-col) flex-grow-0 w-full md:w-auto">
    <div class="flex flex-row items-center border-b-gray-300 border-b-width-2 bg-white px-2 bg-white w-full">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             class="flex-grow-0"
             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
             stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <circle cx="10" cy="10" r="7"></circle>
            <line x1="21" y1="21" x2="15" y2="15"></line>
        </svg>
        <input
                type="text"
                class="h-13 sm:w-full md:w-96 focus:outline-none"
                placeholder="{$t('search')}..."
                bind:value={freeSearch}
                on:keydown={e=> keydownHandler(e)}
                on:change={()=>updateQuery()}>
    </div>
</div>