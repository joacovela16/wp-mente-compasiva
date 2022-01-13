import 'virtual:windi.css';
import UserPermissions from "./views/UserPermissions.svelte";
import DevApp from "./lib/DevApp.svelte";
import PermissionEditor from "./views/PermissionEditor.svelte";
import MetaPost from "./views/MetaPost.svelte";

function applySelection(selector, handler) {
    Array.from(document.getElementsByClassName(selector)).forEach(elem => handler(elem));
}

if (import.meta.env.DEV) {
    new DevApp({target: document.getElementById('app')});
}

export function renderUserPermissions(selector, config) {
    applySelection(selector, elem => new UserPermissions({target: elem, props: {config}}));
}

export function renderPermissionEditor(selector, config) {
    applySelection(selector, elem => new PermissionEditor({target: elem, props: {config}}));
}

export function renderMetaPost(selector, config) {
    applySelection(selector, elem => new MetaPost({target: elem, props: {config}}));
}