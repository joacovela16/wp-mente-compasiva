<script>
    import {arrayAsMap, doDefault, doGetter} from "../lib/shared";

    const defaultConfig = {
        names: {
            permission: 'mc_metabox_permission'
        },
        selections: {},
        i18n: {}
    };
    export let config = {
        permissions: [
            // {name: 'ShowProDir', logged_required: false, post_types: ['attachment'], capabilities: ['CAN_READ', 'CAN_WRITE']},
            // {name: 'ShowProDir2', logged_required: false, post_types: ['attachment'], capabilities: ['CAN_READ_1', 'CAN_WRITE_1']},
        ],
        selections: {},
        i18n: {}
    };

    const finalConfig = {...defaultConfig, ...(config || {})};

    if (!finalConfig.selections) {
        finalConfig.selections = {}
    }

    doDefault(finalConfig.permissions, finalConfig.selections || {}, x => x.name, src => ({name: src.name, post_types: [], capabilities: []}));

    Object.keys(finalConfig.selections || {}).forEach(x => {
        const item = finalConfig.selections[x];
        item.post_types = item.post_types || [];
        item.capabilities = item.capabilities || [];
    });
    const permissions = finalConfig.permissions;
    const selections = finalConfig.selections;
    const __ = doGetter(finalConfig.i18n || {});

    let currentTab = permissions[0];
</script>
<div class="bg-white p-2 rounded">
    <div class="flex flex-row space-x-2">
        {#each permissions as item, index(item.name)}
            <div
                    class="shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 {currentTab.name === item.name && 'bg-blue-500 text-white'}"
                    on:click={()=>currentTab=permissions[index]}
            >
                {item.name}
            </div>
        {/each}

    </div>
    {#each permissions as item, index(item.name)}
        <div class="p-2" class:hidden={item.name !== currentTab.name}>
            <input type="hidden" value={item.name} name="{finalConfig.names.permission}[{item.name}][name]">
            <div>{ __('Contents') }</div>
            <div class="space-x-2">
                {#each item.post_types as pt}
                    <label>
                        <input type="checkbox"
                               value={pt}
                               name="{finalConfig.names.permission}[{item.name}][post_types][]"
                               bind:group={selections[item.name].post_types}
                        >
                        <span>{pt}</span>
                    </label>
                {/each}
            </div>
            <div>{ __('Capabilities') }</div>
            <div class="space-x-2">
                {#each item.capabilities as pt}
                    <label>
                        <input type="checkbox"
                               value={pt}
                               name="{finalConfig.names.permission}[{item.name}][capabilities][]"
                               bind:group={selections[item.name].capabilities}
                        >
                        <span>{pt}</span>
                    </label>
                {/each}
            </div>
        </div>
    {/each}
</div>
<style lang="scss">
</style>