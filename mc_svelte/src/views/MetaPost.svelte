<script>
    import {arrayAsMap, doDefault, doGetter} from "../lib/shared";

    const defaultConfig = {
        names: {
            abstract: "mc_metabox_abstract",
            image: 'mc_metabox_image',
            permission: 'mc_metabox_permission',
            countries: "mc_metabox_countries"
        },
        abstract: "",
        permissions: [],
        selections: {},
        i18n: {}
    };

    export let config = {};

    config.permissions || (config.permissions = []);
    config.base_countries || (config.base_countries = []);
    config.countries || (config.countries = []);

    const finalConfig = {...defaultConfig, ...(config || {})};
    doDefault(finalConfig.permissions, finalConfig.selections || [], x => x.name, src => ({name: src.name, post_types: [], capabilities: []}));
    const selections = arrayAsMap(finalConfig.selections, x => x.name);
    const __ = doGetter(finalConfig.i18n);
    let currentTab = finalConfig.permissions[0];

</script>

<div class="flex flex-row space-x-2">
    <div class="space-y-3 flex-1">
        <div class="flex flex-col">
            <label class="space-y-1">
                <p>{__("Abstract")} </p>
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
    {#if currentTab}
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
                    <div class="p-1" class:hidden={item.name!==currentTab.name}>
                        <div class="flex items-center">
                            <span>{__("Signed required")}</span>
                            {#if item.logged_required}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M7 12l5 5l10 -10"></path>
                                    <path d="M2 12l5 5m5 -5l5 -5"></path>
                                </svg>
                            {:else }
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            {/if}

                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{__("Capabilities")}</span>
                            {#each (item.capabilities || []) as subItem}
                                <label>
                                    <input
                                            type="checkbox"
                                            value={subItem}
                                            name="{finalConfig.names.permission}[{item.id}][capabilities][]"
                                            bind:group={selections[item.id].capabilities}
                                    >
                                    <span>{subItem}</span>
                                </label>
                            {/each}
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    {/if}
</div>

<div>
    <p>{__('Countries')}</p>
    <div class="flex flex-row flex-wrap space-x-2">
        {#each finalConfig.base_countries as c, index}
            <label >
                <input type="checkbox" value={c} name="{finalConfig.names.countries}[]" bind:group={finalConfig.countries}>
                {c}
            </label>
        {/each}
    </div>
</div>
<style lang="scss">
</style>