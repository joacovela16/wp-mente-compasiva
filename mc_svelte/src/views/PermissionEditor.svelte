<script>
    import {doGetter} from "../lib/shared";

    export let config = {
        postUrl: '',
        defaultPermission: {name: 'New item', logged_required: true, post_types: [], capabilities: []},
        post_types: ['post', 'page', 'attachment'],
        permissions: [
            {name: 'ShowProDir', logged_required: false, post_types: ['attachment'], capabilities: ['CAN_READ', 'CAN_WRITE']},
        ],
        defaults: {
            post: {
                permissions: [],
                capabilities: []
            },
            user: {
                permissions: [],
                capabilities: []
            }
        },
        i18n: {}
    };

    config.defaults || (config.defaults = {});
    config.defaults.user || (config.defaults.user = [...config.permissions]);
    config.defaults.post || (config.defaults.post = [...config.permissions]);
    config.countries || (config.countries = []);

    doDefault(config.defaults.post);
    doDefault(config.defaults.user);

    const postUrl = config.postUrl;
    const defaultPermission = config.defaultPermission;

    const postTypes = config.post_types;
    const permissions = config.permissions;
    const __ = doGetter(config.i18n);

    let formRef;

    function doDefault(perTmp) {

        for (let i = 0; i < config.permissions.length; i++) {
            perTmp[i] = perTmp[i] || config.permissions[i];
            perTmp[i].post_types = perTmp[i].post_types || [];
            perTmp[i].capabilities = perTmp[i].capabilities || [];
        }
    }

    function onKeypress(e, index) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (e.target.value === '') return;
            config.permissions[index].capabilities = config.permissions[index].capabilities || [];
            config.permissions[index].capabilities = [...config.permissions[index].capabilities, e.target.value];
            e.target.value = "";
        }
    }

    function removePermission(index) {
        config.permissions.splice(index, 1);
        config.permissions = config.permissions;
    }

    function moveUp(index) {
        const tmp = config.permissions[index - 1];
        config.permissions[index - 1] = config.permissions[index];
        config.permissions[index] = tmp;
    }

    function moveDown(index) {
        const tmp = config.permissions[index + 1];
        config.permissions[index + 1] = config.permissions[index];
        config.permissions[index] = tmp;
    }

    function isPostTypeChecked(index, pt, items) {
        // const tmp = items[index];
        const tmp = items.find(x=>x.id === index);
        return tmp && tmp.post_types.includes(pt)
    }

    function isCapabilityChecked(index, pt, items) {
        const tmp = items.find(x=>x.id === index);
        return tmp && tmp.capabilities.includes(pt);
    }

    function addNew() {
        config.permissions = [{...defaultPermission, id: Date.now()}, ...config.permissions];
        doDefault(config.defaults.user);
    }
</script>
<div class="space-y-2 text-gray-600">
    <div class="flex space-x-2 mt-2">
        <button class="bg-blue-500 text-white rounded p-1 font-bold" on:click={()=>formRef.submit()}>{ __('Save settings') }</button>
        <button class="bg-blue-500 rounded p-1 text-white shadow font-bold" on:click={()=>addNew()}>{ __('Add permission') }</button>
    </div>
    <form action={postUrl} method="post" class="space-y-2" bind:this={formRef}>
        <details open>
            <summary>{ __('Permissions') }</summary>
            {#each config.permissions as permission, index(permission.id)}
                <div class="rounded bg-white p-2">
                    {#if permission.id}
                        <input type="hidden" name="permissions[{index}][id]" value={permission.id}>
                    {/if}
                    <div class="flex space-x-2">
                        <div class="flex-1">
                            <p class="font-bold text-sm mb-2">{__("Configuration")}</p>
                            <div class="flex flex-col space-y-1 px-3">
                                <label class="flex flex-col">
                                    <span class="font-bold">{__("Name")}</span>
                                    <input type="text" name="permissions[{index}][name]" bind:value={permission.name} class="w-auto">
                                </label>
                                <label>
                                    <input type="checkbox" name="permissions[{index}][logged_required]'" checked={permission.logged_required==='on'}>
                                    <span>{__("Signed required")}</span>
                                </label>
                                <div>
                                    <span class="font-bold">{__("Post types")}</span>
                                    <div>
                                        {#each postTypes as pt}
                                            <label class=" items-center">
                                                <input type="checkbox" value={pt} name="permissions[{index}][post_types][]" bind:group={permission.post_types}>
                                                <span>{pt}</span>
                                            </label>
                                        {/each}
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-1 flex-1">
                                    <span class="font-bold">{ __("Capabilities") }</span>
                                    <input type="text" on:keypress={e=>onKeypress(e, index)}>
                                    <div class="flex flex-wrap">
                                        {#each (permission.capabilities || []) as cap, capIndex}
                                            <div class="w-auto">
                                                <input type="hidden" value={cap} name="permissions[{index}][capabilities][]">
                                                <div class="flex shadow items-center bg-blue-500 text-white fill-white rounded mr-1 px-1">
                                                    <span>{cap}</span>
                                                    <span
                                                            class="ml-2 font-bold"
                                                            on:click={()=>{permission.capabilities.splice(capIndex, 1);permission.capabilities=permission.capabilities;}}
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                             class="cursor-pointer"
                                                             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        {/each}
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="space-y-3">
                                <p class="font-bold text-sm mb-2">{__('Default user settings')}</p>
                                <div class="ring-1 ring-amber-500 bg-white p-2 rounded">
                                    {__('permission_desc')}
                                </div>
                                <div class="px-3">
                                    <div>
                                        <p class="font-bold">{__('Posts')}</p>
                                        <div>
                                            <input type="hidden" name="defaults[user][{index}][id]" bind:value={permission.id}>
                                            <input type="hidden" name="defaults[user][{index}][name]" bind:value={permission.name}>
                                            {#each permission.post_types as pt}
                                                <label class="flex items-center">
                                                    <input type="checkbox" value={pt} name="defaults[user][{index}][post_types][]"
                                                           checked={config.defaults.user[index].post_types.includes(pt)}
                                                    >
                                                    <span>{pt}</span>
                                                </label>
                                            {/each}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{__('Capabilities')}</div>
                                        <div class="flex flex-row space-x-2">
                                            {#each (permission.capabilities || []) as pt}
                                                <div class=" items-center">
                                                    <input type="checkbox" value={pt} name="defaults[user][{index}][capabilities][]"
                                                           checked={isCapabilityChecked(permission.id, pt, config.defaults.user)}
                                                    >
                                                    <span>{pt}</span>
                                                </div>
                                            {/each}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center ">
                        <div class="flex flex-col ">
                            {#if index > 0}
                                <div title="Move up" on:click={()=>moveUp(index)}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="cursor-pointer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M18 15l-6 -6l-6 6h12"></path>
                                    </svg>
                                </div>
                            {/if}
                            {#if index < config.permissions.length - 1}
                                <div title="Move down" on:click={()=>moveDown(index)}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="cursor-pointer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M18 15l-6 -6l-6 6h12" transform="rotate(180 12 12)"></path>
                                    </svg>
                                </div>
                            {/if}
                        </div>
                        <div class="flex-1"></div>
                        <div on:click|preventDefault={()=>  removePermission(index)}
                             class="p-2 bg-red-500 rounded text-white cursor-pointer">{__("Delete")}</div>
                    </div>
                </div>
            {/each}
        </details>

        <details open>
            <summary>{__('Countries')}</summary>
            <div class="rounded bg-white p-2 ">
                <input type="text" on:change={e=> {if (e.target.value.length>0) {config.countries.push(e.target.value); config.countries=config.countries;}}}
                       placeholder={__('Add country')}
                >
                <div class="flex flex-wrap">
                    {#each config.countries as country, countryIndex}
                        <input type="hidden" name="countries[]" value={country}>
                        <div class="flex shadow items-center bg-blue-500 text-white fill-white rounded mr-1 px-1 mt-1">
                            <span>{country}</span>
                            <span
                                    class="ml-2 font-bold"
                                    on:click={()=>{config.countries.splice(countryIndex, 1); config.countries = config.countries;}}
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                     stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </span>
                        </div>
                    {/each}
                </div>
            </div>
        </details>
    </form>
</div>
<style lang="scss">
</style>