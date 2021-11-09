const VideoPlayer = {
    name: 'video-player',
    data() {
        const initial = Math.floor(Math.random() * 5) + 1;
        return {
            videoSource: `http://localhost:8000/wp-content/themes/mc-theme/assets/video/video-${initial}.mp4`
        }
    },
    template: `<video :src="videoSource" ref="videoEl" autoplay muted ></video>`
}