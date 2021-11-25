<script>
    export let label;
</script>
<div class="flex flex-row space-x-3">
    <div>
        <slot>

        </slot>
    </div>
    {#if label}
        <div>{label}</div>
    {/if}
</div>
<style lang="scss">
</style>