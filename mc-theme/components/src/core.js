import {writable} from "svelte/store";

export const showSlideBar = new writable(false);
export const queryBus = new writable(undefined);
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
