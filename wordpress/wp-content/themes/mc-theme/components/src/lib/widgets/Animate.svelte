<script>
    import {fade} from "svelte/transition";
</script>
<div in:fade out:fade class="text-gray-700">
    <slot></slot>
</div>
<style lang="scss">
</style>