<script>
    import {t} from "svelte-i18n";

    import Modal from "./Modal.svelte";
    import {showLoginModal} from "../core";
    import {getContext} from "svelte";
    import Field from "./Field.svelte";

    const {loginUrl, homeUrl} = getContext("mc-context")
    console.log(loginUrl);
</script>
<Modal bind:open={$showLoginModal}>
    <span slot="header">{$t('welcome')} </span>
    <div slot="content">
        <form action={loginUrl} method="post" class="space-y-2">
            <Field label={$t("user_or_email")}>
                <input type="text" name="log" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg" value="" size="20"/>
            </Field>
            <Field label={$t("password")}>
                <input type="password" name="pwd" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg" value="" size="20"/>
            </Field>
            <div>
                <label>
                    <input name="rememberme" type="checkbox" value="forever"/>
                    {$t("remember_me")}
                </label>
            </div>
            <div class="flex-1"></div>
            <input type="submit" name="wp-submit" class="appearance-none rounded-lg bg-blue-500 p-3 text-white cursor-pointer" value="Log In"/>
            <input type="hidden" name="redirect_to" value={homeUrl}/>
        </form>
    </div>
</Modal>
<style lang="scss">
</style>