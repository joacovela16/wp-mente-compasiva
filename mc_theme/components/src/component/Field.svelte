<script>
    export let label;
</script>
<div class="flex flex-col space-y-2 flex-1 justify-center">
    <p class="font-bold">{label}</p>
    <slot></slot>
</div>
<style lang="scss">
</style>