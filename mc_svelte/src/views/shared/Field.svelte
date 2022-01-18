<script>
    export let label = "";
</script>
<div class="flex flex-col space-y-2 flex-1 justify-center">
    {#if label}
        <div class="font-bold z-index-10">{label}</div>
    {/if}
    <div class="flex flex-col space-y-2 flex-1 justify-center">
        <slot></slot>
    </div>
</div>
<style lang="scss">
</style>