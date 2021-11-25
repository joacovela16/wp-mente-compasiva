<script>
    import {fade} from "svelte/transition";

    import LoaderState from "./loader";

    const S_STEP_SIZE = 2;
    const L_STEP_SIZE = 5;
    let step_size = 1;
    let counter = 0;
    let inProgress = false;
    let shutdown = false;
    let progress = 0;

    LoaderState.subscribe(x => {
        counter = x;
        if ((counter <= 0 && !inProgress)) {
            reset();
        }

        if (!inProgress) {
            inProgress = true;
            startAnimation();
        }
        if (shutdown) {
            halt()
            shutdown = false;
        }
    });

    function animate(i) {
        if (i < 100) {
            if (counter <= 0 && progress > 50) {
                step_size = L_STEP_SIZE;
            }
            progress = i + step_size;
            requestAnimationFrame(() => animate(i + step_size));
        } else {
            if (counter <= 0) {
                halt();
            } else {
                shutdown = true;
            }
        }
    }

    function halt() {
        inProgress = false;
        reset();
    }

    function startAnimation() {
        requestAnimationFrame(() => animate(0));
    }

    function reset() {
        progress = 0;
        counter = 0;
        step_size = S_STEP_SIZE;
    }
</script>

{#if progress > 0}
    <div out:fade
         class="absolute top-0 left-0 h-full w-full z-index-30 flex justify-center items-center bg-white"
         style="box-shadow: 0px 0px 50px rgba(0,0,0,0.2) inset;">
        <!-- <div class="pace pace-active">
             <div
                     class="pace-progress"
                     data-progress={progress}
                     data-progress-text="{progress}%"
                     style="-webkit-transform: translate3d({progress}%, 0px, 0px); -ms-transform: translate3d({progress}%, 0px, 0px); transform: translate3d({progress}%, 0px, 0px);">
                 <div class="pace-progress-inner"></div>
             </div>
         </div>-->
        <div>
            <div class="infinity">
                <div>
                    <span></span>
                </div>
                <div>
                    <span></span>
                </div>
                <div>
                    <span></span>
                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="display: none;">
                <defs>
                    <filter id="goo">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur"/>
                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7"
                                       result="goo"/>
                        <feBlend in="SourceGraphic" in2="goo"/>
                    </filter>
                </defs>
            </svg>
        </div>
    </div>
{/if}

<style lang="scss">


</style>