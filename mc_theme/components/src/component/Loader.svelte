<script>
    import {fade} from "svelte/transition";

    import LoaderState from "../loader";

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
         class="fixed top-0 left-0 h-full w-full z-index-30 flex justify-center items-center bg-white"
         style="box-shadow: 0 0 50px rgba(0,0,0,0.2) inset;">
        <div class="triple-spinner"></div>
    </div>
{/if}

<style lang="scss">

  .triple-spinner {
    display: block;
    position: relative;
    width: 125px;
    height: 125px;
    border-radius: 50%;
    border: 4px solid transparent;
    border-top: 4px solid #2563eb;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
  }

  .triple-spinner::before,
  .triple-spinner::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    border: 4px solid transparent;
  }
  .triple-spinner::before {
    top: 5px;
    left: 5px;
    right: 5px;
    bottom: 5px;
    border-top-color: #059669;
    -webkit-animation: spin 3s linear infinite;
    animation: spin 3.5s linear infinite;
  }
  .triple-spinner::after {
    top: 15px;
    left: 15px;
    right: 15px;
    bottom: 15px;
    border-top-color: #dc2626;
    -webkit-animation: spin 1.5s linear infinite;
    animation: spin 1.75s linear infinite;
  }



  @-webkit-keyframes spin {
    from {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    to {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }


  @keyframes spin {
    from {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    to {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }

</style>