<script>
    import {fly} from "svelte/transition";
</script>
<div transition:fly="{{x: -50}}" class="relative">
    <slot></slot>
</div>
<style lang="scss">
</style>