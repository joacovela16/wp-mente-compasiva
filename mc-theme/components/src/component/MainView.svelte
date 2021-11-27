<script>

    import VideoPlayer from "./VideoPlayer.svelte";
    import Animate from "./Animate.svelte";
    import {getContext} from "svelte";
    import DecorationSVG from "./DecorationSVG.svelte";
    import Container from "./Container.svelte";

    const {mainContent} = getContext("mc-context")
</script>
<div class="relative max-h-200 overflow-hidden">
    <VideoPlayer/>
    <div class="absolute top-0 left-0 bg-dark-500 opacity-20 h-full w-full"></div>

    <DecorationSVG/>
    <div class="absolute top-0 left-0 h-full w-full flex items-center justify-center">
        <div class="text-6xl text-white text-shadow-lg lg:-mt-30">MENTE COMPASIVA</div>
    </div>
</div>
<Container>
    {@html mainContent}

</Container>
