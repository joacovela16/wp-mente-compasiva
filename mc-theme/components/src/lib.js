import 'virtual:windi.css';
import './assets/theme.scss';
import "./MainView.svelte";
import App from "./App.svelte";
import {writable} from "svelte/store";

export const showSlideBar = new writable(true);
export const queryBus = new writable(undefined);
const task = [];
let appInstanced = false;

export function filterBuilder(values) {
    return [
        {
            id: "cft",
            children: [
                {
                    id: "releaseDate",
                    children: [
                        {id: "gte", type: "date", queryTag: 'after'},
                        {id: "lte", type: "date", queryTag: 'before'},
                    ]
                },
                {id: "language", multiple: true, enum: [{key: 'es', value: 'es', queryTag: 'tag'}]},
                {id: "country", multiple: true, enum: [{key: "uru", value: "uruguay", queryTag: 'tag'}]}
            ]
        },
        {
            id: "resource",
            children: [
                {
                    id: "author",
                    type: "string",
                    multiple: true,
                    queryTag: 'author'
                },
                {
                    id: "multimedia",
                    multiple: true,
                    enum: [
                        {key: "video", value: "Video", queryTag: 'tag'},
                        {key: "audio", value: "Audio", queryTag: 'tag'},
                    ]
                },
                {
                    id: "journal",
                    multiple: true,
                    enum: [
                        {key: "news", value: "News", queryTag: 'tag'},
                        {key: "blog", value: "Blog", queryTag: 'tag'},
                        {key: "book", value: "Book", queryTag: 'tag'},
                        {key: "article", value: "Article", queryTag: 'tag'},
                        {key: "thesis", value: "Thesis", queryTag: 'tag'},
                        {key: "website", value: "Website", queryTag: 'tag'},
                    ]
                },
                {
                    id: "publish-date",
                    children: [
                        {id: "gte", type: "date", multiple: false, queryTag: 'after'},
                        {id: "lte", type: "date", multiple: false, queryTag: 'before'},
                    ]
                }
            ]
        }
    ];
}

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
        new App({
            target: elementById, props: {
                appConf, readyCallback() {
                    appInstanced = true;
                    runTask();
                }
            }
        });
    } else {
        console.warn(`Can't find element ${id}`)
    }
}

if (import.meta.env.MODE === "development") {
    new App({target: document.body})
}