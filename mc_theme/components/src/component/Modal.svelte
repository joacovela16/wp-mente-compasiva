<script>
    import {fade} from "svelte/transition";
    import {t} from "svelte-i18n";

    export let title = "";
    export let open = true;


    function closeModel() {
        open = false;
    }

</script>
{#if open}
    <div class="flex justify-center items-center fixed top-0 left-0 w-full h-full z-index-30"
         transition:fade>
        <div class="rounded-lg w-auto max-h-4/5 bg-white overflow-hidden shadow-md flex flex-col">

            <div class="flex flex-row py-5 px-2 bg-cool-gray-100 relative">
                <div class="font-bold z-index-10">
                    <slot name="header"></slot>
                </div>
                <div class="flex-1"></div>
                <div class="cursor-pointer z-index-10" on:click={()=>closeModel()} title={$t("close")}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>

            </div>
            <div class="flex-1 p-2 container mx-auto flex overflow-auto">
                <slot name="content"></slot>
            </div>
            {#if $$slots.footer}
                <div class="py-3 px-2 bg-cool-gray-100">
                    <slot name="footer"></slot>
                </div>
            {/if}

        </div>

    </div>
{/if}
<style lang="scss">
  .custom-shape-divider-top-1638203925 {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
  }

  .custom-shape-divider-top-1638203925 svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 62px;
  }

  .custom-shape-divider-top-1638203925 .shape-fill {
    fill: #2563eb;
  }
</style>