const ResumeContainer = {
    name: 'resume-container',
    props: ["title"],
    //language=HTML
    template: `
        <div class="container mx-auto p-4">
            <div class="flex flex-row">
                <div class="flex-grow-0 text-3xl font-bold">
                    {{title}}
                </div>
                <div class="flex-1"></div>
                <div class="flex-grow-0 cursor-pointer hover:underline font-bold">
                    <slot name="right_label"></slot>
                </div>
            </div>
            <div class="space-x-2 flex flex-row h-50 ">
                <slot></slot>
            </div>
        </div>
    `
};
const PostResume = {
    name: 'post-resume',
    props: ["title"],
    //language=HTML
    template: `

        <div class="shadow-dark-500 ring-gray-800 shadow-lg p-4 rounded-lg overflow-hidden relative max-w-100">
            <div class="absolute left-0 top-0 w-full h-full bg-gradient-to-t to-transparent from-white via-transparent z-index-0"></div>
            <div class="flex flex-row z-index-20">
                <div class="text-lg font-bold">{{title || "Title empty"}}</div>
                <div class="flex-1"></div>
                <div class="cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         stroke-width="2"
                         stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5"></path>
                        <line x1="10" y1="14" x2="20" y2="4"></line>
                        <polyline points="15 4 20 4 20 9"></polyline>
                    </svg>
                </div>
            </div>
            <div class="overflow-hidden p-1">
                <slot>
                    It is a long established fact that a reader will be distracted by the readable content of a
                    page
                    when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less
                    normal
                    distribution of letters, as opposed to using 'Content here, content here', making it look
                    like
                    readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum
                    as
                    their default model text, and a search for 'lorem ipsum' will uncover many web sites still
                    in
                    their infancy. Various versions have evolved over the years, sometimes by accident,
                    sometimes on
                    purpose (injected humour and the like).
                </slot>
            </div>

        </div>`
};
