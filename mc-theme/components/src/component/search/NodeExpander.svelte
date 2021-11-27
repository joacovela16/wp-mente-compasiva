<script>
    import {getContext, onMount} from "svelte";
    import {t} from "svelte-i18n";

    import moment from "moment";

    export let node = {};
    export let expand = true;
    export const isRoot = true;
    export let goBack;
    const {setRegistry, pushItem, buildItem} = getContext("search-ctx");

    let valueRef, dateRef;

    function isEnum(obj) {
        return isDefined(obj) && isDefined(obj.enum);
    }

    function isDefined(obj) {
        return obj !== undefined;
    }

    function isUndefined(obj) {
        return obj === undefined;
    }

    function isMultiple(obj) {
        return isDefined(obj) && obj.multiple === true;
    }

    function hasChildren(obj) {
        return isDefined(obj) && isDefined(obj.children);
    }

    function hasType(obj) {
        return isDefined(obj) && isDefined(obj.type);
    }

    function itemCheckedHandler(item, index) {
        pushItem(
            buildItem(getEnumID(item.key, index), node.id, item.value, item)
        );
    }

    function getEnumID(key, idx) {
        return `${node.id}-enum-${key}-${idx}`;
    }

    function getTextID() {
        return `${node.id}-text`;
    }

    function getDateID() {
        return `${node.id}-date`;
    }


    function textChangeHandler() {
        pushItem(
            buildItem(getTextID(), node.id, valueRef, node)
        );
        node.selected = true;
        valueRef = undefined;
    }

    function dateChangeHandler() {
        pushItem(
            buildItem(getDateID(), node.id, moment(dateRef, "YYYY-MM-DD").format("DD/MM/YYYY"), node)
        );
        node.selected = true;
    }

    onMount(() => {
        (node.enum || []).forEach((x, idx) => {
            setRegistry(getEnumID(x.key, idx), {
                unselect() {
                    node.enum[idx].selected = false;
                }
            })
        });

        if (hasType(node)) {
            const id = node.type === 'string' ? getTextID() : getDateID();
            setRegistry(id, {
                unselect() {
                    node.selected = false;
                }
            })
        }
    });

</script>
<div class="px-2 space-y-2">

    {#if hasType(node)}
        {#if isMultiple(node) || isUndefined(node.selected) || node.selected === false}
            <div class="flex flex-row items-center">
                <div class="font-bold">{$t(node.id)}</div>
                <div class="flex-1"></div>
                <div>

                    {#if node.type === "string"}
                        <input type="text"
                               class="appearance-none w-40 py-1 px-2 ring-2 ring-gray-200 focus:ring-blue-500 rounded"
                               bind:value={valueRef}
                               on:change={()=>textChangeHandler()}>
                    {:else if node.type === "date"}
                        <input type="date"
                               class="appearance-none w-40 py-1 px-2 ring-2 ring-gray-200 focus:ring-blue-500 rounded"
                               bind:value={dateRef}
                               on:change={()=>dateChangeHandler()}
                        >
                    {/if}
                </div>
            </div>
        {/if}
    {:else}
        <div class="flex flex-row">
            <div class="cursor-pointer font-bold" on:click={()=>expand=!expand}>
                {$t(node.id)}
            </div>
            <div class="flex-1"></div>
            {#if goBack}
                <div class="font-bold cursor-pointer underline"
                     on:click={()=>goBack()}>
                    {$t('back')}
                </div>
            {/if}
        </div>
    {/if}

    {#if hasChildren(node)}
        {#if expand}
            <div class="ml-5 space-y-2">
                {#each node.children as item, idx(item.id)}
                    <svelte:self node={item}/>
                {/each}
            </div>
        {/if}
    {:else if isEnum(node)}
        <div class="ml-5">
            {#each node.enum as item, idx(item.key)}
                {#if isUndefined(item.selected) || item.selected === false }
                    <div>
                        <label class="cursor-pointer">
                            <input type="checkbox" bind:checked={item.selected}
                                   on:click={()=>itemCheckedHandler( item , idx)}/>
                            <span>{$t(item.key)}</span>
                        </label>
                    </div>
                {/if}
            {/each}
        </div>
    {/if}
</div>
<style lang="scss">
</style>