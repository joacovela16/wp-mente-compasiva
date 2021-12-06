import {get, writable} from "svelte/store";
import {t} from "svelte-i18n"

export const showSidebar = writable(false);
export const queryBus = writable(undefined);
export const showProfileModal = writable(false);
export const showLoginModal = writable(false);
export const avatarUrl = writable(undefined);

export function i18n(key) {
    return get(t)(key);
}


export function setScrollTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For
}

/*
export function filterBuilder(appConf) {

    return [
        {
            id: "cft",
            children: [
                {
                    id: "release_date",
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
                    id: "publish_date",
                    children: [
                        {id: "gte", type: "date", multiple: false, queryTag: 'after'},
                        {id: "lte", type: "date", multiple: false, queryTag: 'before'},
                    ]
                }
            ]
        }
    ];
}
*/
