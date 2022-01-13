<script>
    import {arrayAsMap, doDefault, doGetter} from "../lib/shared";

    const defaultConfig = {
        names: {
            abstract: "mc_metabox_abstract",
            image: 'mc_metabox_image',
            permission: 'mc_metabox_permission'
        },
        abstract: "",
        permissions: [
            {name: 'ShowProDir', logged_required: false, post_types: ['attachment'], capabilities: ['CAN_READ', 'CAN_WRITE']},
            {name: 'ShowProDir2', logged_required: false, post_types: ['attachment'], capabilities: ['CAN_READ_1', 'CAN_WRITE_1']},
        ],
        selections: {},
        i18n: {}
    };

    export let config = {};
    const finalConfig = {...defaultConfig, ...(config || {})};
    console.log(finalConfig)

    doDefault(finalConfig.permissions, finalConfig.selections || [], x => x.name, src => ({name: src.name, post_types: [], capabilities: []}));

    const selections = arrayAsMap(finalConfig.selections, x=>x.name);
    const __ = doGetter(finalConfig.i18n);
    let currentTab = finalConfig.permissions[0];

</script>
{#if currentTab}
    <div class="flex flex-row space-x-2">
        <div class="space-y-3 flex-1">
            <div class="flex flex-col">
                <label>
                    <div>{__("Abstract")} </div>
                    <textarea name={finalConfig.names.abstract} class="w-full">{finalConfig.abstract}</textarea>
                </label>
            </div>
            <div class="flex flex-col">
                <label>
                    <div>{__("Decorative image")}</div>
                    <input type="file" accept="image/png, image/jpeg" name={finalConfig.names.image}>
                </label>
            </div>
        </div>
        <div class="flex-1">
            <p>{__("Permissions")}</p>
            <div class="p-2 bg-white space-y-2">
                <div class="flex flex-row space-x-2">
                    {#each finalConfig.permissions as item, index}
                        <div
                                on:click={()=>currentTab=finalConfig.permissions[index]}
                                class="shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 {currentTab.name === item.name && 'bg-blue-500 text-white'}"
                        >
                            {item.name}
                        </div>
                    {/each}
                </div>

                {#each finalConfig.permissions as item, index}
                    <div class="p-2" class:hidden={item.name!==currentTab.name}>
                        <label>
                            <input type="checkbox"
                                   name="{finalConfig.names.permission}[{item.name}][logged_required]"
                                   bind:checked={selections[item.name].logged_required}
                            >
                            {__("Signed required")}
                        </label>
                        <div>
                            {#each item.capabilities as subItem}
                                <label>
                                    <input
                                            type="checkbox"
                                            value={subItem}
                                            name="{finalConfig.names.permission}[{item.name}][capabilities][]"
                                            bind:group={selections[item.name].capabilities}
                                    >
                                    <span>{subItem}</span>
                                </label>
                            {/each}
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </div>
{/if}
<style lang="scss">
</style>