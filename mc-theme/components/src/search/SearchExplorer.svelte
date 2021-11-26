<script>
    import {filterBuilder, queryBus} from "../lib";
    import Explorer from "./Explorer.svelte";
    import {getContext, setContext} from "svelte";
    import {push} from "svelte-spa-router";

    const slideBar = getContext("slide-bar");
    const filter = slideBar.filter;
    let filterSpec = filter.filterSpec;

    setContext("search-ctx", {
        cleanAll() {

            filter.items.forEach(x => {
                const reg = filter.registry[x.id];
                reg && reg.unselect && reg.unselect();
            });

            filter.registry = {};
            filter.items = [];
        },
        pushItem(item) {
            filter.items = [item, ...filter.items];
            refreshQuery();
        },
        getRegistry(id) {
            return filter.registry[id];
        },
        setRegistry(id, callback) {
            filter.registry[id] = callback;
        },
        buildItem(id, label, value, target) {
            return {id, label, value, target};
        }
    });

    function refreshQuery() {
        const tagStore = {};

        filter.items.forEach(datum => {
            const tag = datum.target.queryTag;
            if (tag) {
                tagStore[tag] = tagStore[tag] || [];
                tagStore[tag].push(datum.value);
            }
        });

        const keys = Object.keys(tagStore);
        const keyLg = keys.length;
        const result = [];
        for (let i = 0; i < keyLg; i++) {
            const k = keys[i];
            const str = tagStore[k];
            result.push(`${k}=${str}`);
        }

        const query = result.join("&");
        push("/search");
        queryBus.set(query);
    }

    function removeItem(item, index) {
        const reg = filter.registry[item.id];
        reg && reg.unselect();
        filter.items.splice(index, 1);
        filter.items = filter.items;
        refreshQuery();
    }
</script>

<div class="flex flex-row flex-wrap">
    {#each filter.items as item, idx}
        <div class="font-bold text-sm space-x-1 flex flex-row ring-1 ring-blue-500 rounded-full shadow shadow-dark-800 overflow-hidden cursor-pointer px-2 py-1 m-1"
             on:click={()=>removeItem(item, idx)}>
            {item.label}: {item.value}
        </div>
    {/each}
</div>

<Explorer data={filterSpec}/>