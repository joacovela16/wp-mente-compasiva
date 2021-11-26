<script>
    import {waitFor} from "../loader";
    import {onMount} from "svelte";

    const initial = randVideo();

    const videos = [
        () => import("/video/video-1.mp4"),
        () => import("/video/video-2.mp4"),
        () => import("/video/video-3.mp4"),
        () => import("/video/video-4.mp4"),
        () => import("/video/video-5.mp4"),
        () => import("/video/video-6.mp4"),
    ];
    let s = videos[initial];
    let videoRef;

    function randVideo() {
        return Math.floor(Math.random() * 6);
    }

    function importer(src) {
        return new URL(src, import.meta.url);
    }

    function endedHandler() {
        s = videos[randVideo()];
    }
</script>
{#await waitFor(s()) then x}
    <video bind:this={videoRef} src={x.default} autoplay muted loop></video>
{/await}
<style lang="scss">
</style>