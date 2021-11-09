<script>
    let open = false;
    let ref;

    function handler(e) {
        if (!ref.contains(e.target)) {
            open = false;
        }
    }
</script>

<svelte:window on:click={e=>handler(e)}/>

<div class="relative inline-block text-left" bind:this={ref}>
    <button
            type="button"
            class="
                inline-flex
                justify-center
                w-full rounded
                shadow-sm
                px-4 py-2
                bg-white
                font-medium
                text-gray-700
                hover:bg-gray-50
                focus:outline-none
                focus:(ring-2 ring-blue-500)"
            on:click={()=>open = !open}>
        COUNTRY
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </button>

    {#if open}
        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="py-1" role="none">
                <slot></slot>
            </div>
        </div>
    {/if}
</div>

<style lang="scss">
</style>