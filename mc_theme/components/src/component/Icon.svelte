<script>
    export let label;
    export let iconLeft = true;
</script>
<div class="flex flex-row space-x-3">
    {#if iconLeft}
        <div>
            <slot>

            </slot>
        </div>
    {/if}
    {#if label}
        <div>{label}</div>
    {/if}
    {#if !iconLeft}
        <div>
            <slot>

            </slot>
        </div>
    {/if}
</div>
<style lang="scss">
</style>