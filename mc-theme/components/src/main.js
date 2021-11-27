import 'virtual:windi.css';
import './assets/theme.scss';
import {init, isLoading, register} from "svelte-i18n";

import App from "./component/App.svelte";

const task = [];
let appInstanced = false;

function runTask() {
    if (appInstanced) {
        while (task.length > 0) {
            const item = task.shift();
            item();
        }
    }
}

export function renderPostResume(id, title, content) {
    const elementById = document.getElementById(id);
    if (elementById) {
        task.push(() => new PostResumeContainer({target: elementById, props: {title, content}}));
        runTask();
    }
}

export function renderApp(id, appConf) {
    const elementById = document.getElementById(id);
    if (elementById) {

        register("es", () => import("./i18n/es.json"));
        register("en", () => import("./i18n/en.json"));
        init({fallbackLocale: "en", initialLocale: "en"});
        let appRendered = false;

        isLoading.subscribe(loaded => {
            if (!loaded && !appRendered) {
                appRendered = true;

                new App({
                    target: elementById, props: {
                        appConf, readyCallback() {
                            appInstanced = true;
                            runTask();
                        }
                    }
                });
            }
        })
        ;
    } else {
        console.warn(`Can't find element ${id}`)
    }
}

if (import.meta.env.MODE === "development") {
    new App({target: document.body})
}