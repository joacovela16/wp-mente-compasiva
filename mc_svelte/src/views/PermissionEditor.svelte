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

    function removePermission(index, event) {
        event.preventDefault();
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
        const tmp = items[index];
        return tmp && tmp.post_types.includes(pt)
    }

    function isCapabilityChecked(index, pt, items) {
        const tmp = items[index];
        return tmp && tmp.capabilities.includes(pt)
    }
</script>
<div class="space-y-2 text-gray-600">


    <div class="flex flex-row space-x-2 mt-2">
        <button on:click={()=>formRef.submit()} class="bg-blue-500 text-white rounded p-1">{ __('Save settings') }</button>
        <button class="border-1 border-blue-500 rounded p-1 bg-white" on:click={()=>config.permissions = [defaultPermission, ...config.permissions]}>{ __('Add new') }</button>
    </div>
    <form action={postUrl} method="post" class="space-y-2" bind:this={formRef}>
        <details open>
            <summary>{ __('Permissions') }</summary>
            {#each config.permissions as permission, index}
                <div class="rounded bg-white p-2">
                    <input type="hidden" name="permissions[{index}][id]" value={permission.id}>
                    <div class="flex space-x-2">
                        <div class="flex-1">
                            <p class="font-bold text-sm mb-2">{__("Configuration")}</p>
                            <div class="flex flex-col space-y-1 px-3">
                                <label class="flex flex-col">
                                    <span class="font-bold">{__("Name")}</span>
                                    <input type="text" name="permissions[{index}][name]" value={permission.name} class="w-auto">
                                </label>
                                <label>
                                    <input type="checkbox" name="permissions[{index}][logged_required]'" checked={permission.logged_required==='on'}>
                                    <span>{__("Signed required")}</span>
                                </label>
                                <div>
                                    <span class="font-bold"> {__("Post type")}</span>
                                    <div>
                                        {#each postTypes as pt}
                                            <label class=" items-center">
                                                <input type="checkbox" value={pt} name="permissions[{index}][post_types][]" checked={permission.post_types.includes(pt)}>
                                                <span>{pt}</span>
                                            </label>
                                        {/each}
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-1 flex-1">
                                    <span>{ __("Capabilities") }</span>
                                    <input type="text" on:keypress={e=>onKeypress(e, index)}>
                                    <div class="flex flex-wrap">
                                        {#each (permission.capabilities || []) as cap, capIndex}
                                            <div class="w-auto">
                                                <input type="hidden" value={cap} name="permissions[{index}][capabilities][]">
                                                <div class="flex shadow items-center bg-blue-500 text-white fill-white rounded mr-1 px-1">
                                                    <span>{cap}</span>
                                                    <span
                                                            class="ml-2 font-bold"
                                                            on:click={()=>{permission.capabilities.splice(capIndex, 1);permission.capabilities  =permission.capabilities;}}
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
                                <p class="font-bold text-sm mb-2">{__('Defaults settings')}</p>
                                <div class="px-3">
                                    <div>
                                        <p class="font-bold">{__('Default posts')}</p>
                                        <div>
                                            <input type="hidden" name="defaults[user][{index}][name]" value={permission.name}>
                                            {#each permission.post_types as pt}
                                                <label class="flex items-center">
                                                    <input type="checkbox" value={pt} name="defaults[user][{index}][post_types][]"
                                                           checked={isPostTypeChecked(index, pt, config.defaults.user)}
                                                    >
                                                    <span>{pt}</span>
                                                </label>
                                            {/each}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{__('Default capabilities')}</div>
                                        <div class="flex flex-row space-x-2">
                                            {#each (permission.capabilities || []) as pt}
                                                <div class=" items-center">
                                                    <input type="checkbox" value={pt} name="defaults[user][{index}][capabilities][]"
                                                           checked={isCapabilityChecked(index, pt, config.defaults.user)}
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
                        <div on:click={e=>removePermission(index, e)} class="p-2 bg-red-500 rounded text-white ">{__("Delete")}</div>
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