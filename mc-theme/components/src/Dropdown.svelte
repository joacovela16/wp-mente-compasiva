<script>

    export let label;
    let open = false;
    let el;

    function handleClick(e) {
        if (!el.contains(e.target)) {
            open = false;
        }
    }
</script>
<svelte:window on:click={e=>handleClick(e)}/>
<div class="relative inline-block text-left" bind:this={el}>
    <div
            class="flex flex-row justify-center w-full  px-4 py-2 bg-white font-medium
                   hover:bg-gray-50 focus:outline-none cursor-pointer focus:ring-2 focus:ring-blue-500"
            on:click={()=> open = !open }
    >
        <span>{label}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </div>
    {#if open}
        <div class="origin-top-right absolute z-index-10 mt-2 w-56 rounded shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="py-1">
                <slot></slot>
            </div>
        </div>
    {/if}
</div>
<style lang="scss">
</style>