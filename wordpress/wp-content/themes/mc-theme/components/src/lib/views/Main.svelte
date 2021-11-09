<script>
    import {onMount} from "svelte";
    import Animate from "../widgets/Animate.svelte";
    import PostResume from "../widgets/PostResume.svelte";

    const videos = [
        "../../video/video-1.mp4",
        "../../video/video-2.mp4",
        "../../video/video-3.mp4",
        "../../video/video-4.mp4",
        "../../video/video-5.mp4",
    ];

    let lg = videos.length, counter = 4;
    let src = videos[counter];
    let videoEl;


    function importer(src) {
        return new URL(src, import.meta.url);
    }

    onMount(() => {
        if (videoEl) {
            videoEl.addEventListener("ended", () => {
                counter = (counter + 1) % lg;
                src = videos[counter];
            });
        }
    })
</script>
<Animate>
    <div class="overflow-hidden relative">
        <div class="relative">
            <video src="{importer(src)}" bind:this={videoEl} autoplay muted></video>
            <div class="absolute top-0 left-0 bg-dark-500 opacity-20 h-full w-full"></div>
            <svg class="absolute bottom-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1"
                      d="M0,160L80,170.7C160,181,320,203,480,213.3C640,224,800,224,960,197.3C1120,171,1280,117,1360,90.7L1440,64L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
            </svg>
            <div class="absolute top-0 left-0 h-full w-full flex items-center justify-center">
                <div class="text-6xl text-white text-shadow-lg lg:-mt-20">MENTE COMPASIVA</div>
            </div>
        </div>
    </div>
    <div class="relative -mt-20 p-2">
        <div class="container mx-auto">
            <div>
                <div class="text-3xl font-bold">Últimas noticias</div>
                <div class="space-x-2 flex flex-row">
                    <PostResume></PostResume>
                    <PostResume></PostResume>
                    <PostResume></PostResume>
                </div>
            </div>

        </div>
    </div>
</Animate>