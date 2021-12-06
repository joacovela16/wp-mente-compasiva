const VideoPlayer = {
    name: 'video-player',
    data() {
        const initial = Math.floor(Math.random() * 6) + 1;
        return {
            videoSource: `http://localhost:8000/wp-content/themes/mc-theme/assets/video/video-${initial}.mp4`
        }
    },
    //language=HTML
    template: `<video :src="videoSource" ref="videoEl" autoplay muted loop></video>`
}