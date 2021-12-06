const MainLayout = {
    name: 'main-layout',
    data() {
        return {
            items: 4,
            videoSource: "http://localhost:8000/wp-content/themes/mc-theme/assets/video/video-5.mp4"
        };
    },
    //language=HTML
    template: `
        <div class="overflow-hidden relative">
            <div class="relative">
                <video-player></video-player>
                <div class="absolute top-0 left-0 bg-dark-500 opacity-20 h-full w-full"></div>
                <svg class="absolute -bottom-1px" viewBox="0 0 1440 320">
                    <path fill="#ffffff" fill-opacity="1"
                          d="M0,160L80,170.7C160,181,320,203,480,213.3C640,224,800,224,960,197.3C1120,171,1280,117,1360,90.7L1440,64L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
                </svg>
                <div class="absolute top-0 left-0 h-full w-full flex items-center justify-center">
                    <div class="text-6xl text-white text-shadow-lg lg:-mt-20">MENTE COMPASIVA</div>
                </div>
            </div>
        </div>
        <div class="relative -mt-20 bg-white">
            <slot></slot>
        </div>`
};
