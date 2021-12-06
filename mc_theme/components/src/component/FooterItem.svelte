<script>
    export let label;
</script>
<div class="flex flex-col flex-grow-0 max-w-100 space-y-3">
    <div class="text-lg font-bold">{label}</div>
    <div>
        <slot></slot>
    </div>
</div>
<style lang="scss">
</style>