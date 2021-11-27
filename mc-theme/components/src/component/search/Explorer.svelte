<script>

    import NodeExpander from "./NodeExpander.svelte";
    import Animate from "../Animate.svelte";
    import {getContext, onMount} from "svelte";
    import {t} from "svelte-i18n";


    const {cleanAll} = getContext("search-ctx");
    const slideBar = getContext("slide-bar");
    export let data = [];
    let selection;

    function goBack() {
        selection = undefined;
        cleanAll();
    }

    function selectNode(index) {
        selection = data[index];
        slideBar.selectedNode = index;
    }

    onMount(()=>{
        if (slideBar.selectedNode !== undefined) {
            selectNode(slideBar.selectedNode);
        }
    });
</script>
<div class="flex flex-col">
    {#if selection === undefined}
        {#each data as item, idx(item.id)}
            <div class="flex flex-row hover:bg-gray-100 p-2 transition-all rounded cursor-pointer"
                 on:click={()=>selectNode(idx)}>
                <div class="font-bold text-lg">{$t(item.id)}</div>
                <div class="flex-1"></div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right"
                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <polyline points="9 6 15 12 9 18"></polyline>
                    </svg>
                </div>
            </div>
        {/each}
    {:else}
        <Animate>
            <NodeExpander node={selection} isRoot="true" expand="true" {goBack}/>
        </Animate>
    {/if}
</div>
<style lang="scss">
</style>