<script>
    import {fly} from "svelte/transition";
</script>
<div transition:fly="{{x: -50}}">
    <slot></slot>
</div>
<style lang="scss">
</style>