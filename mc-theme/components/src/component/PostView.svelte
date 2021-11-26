<script>
    import Animate from "./Animate.svelte";
    import {getContext} from "svelte";
    import {default as Axios} from "axios";
    import {waitFor} from "../loader";
    import GoBack from "./GoBack.svelte";
    import Container from "./Container.svelte";

    const {adminUrl} = getContext("mc-context");
    export let params = {};
    const id = params.id;
    const type = params.type;

    const result = waitFor(Axios.get('index.php', {params: {rest_route: `/wp/v2/${type}/${id}`}}).then(x => x.data));
</script>
<Animate>
    <Container>
        <GoBack/>
        {#await result then data}
            <div class="flex flex-row border-b-gray-300 border-b-width-2 p-1 items-center justify-center">
                <div class="font-bold text-3xl ">{data.title.rendered}</div>
                <div class="flex-1"></div>
                <!--                <GoBack></GoBack>-->
            </div>
            <div>
                {@html data.content.rendered}
            </div>

        {:catch e}
            <p> UPS!!! </p>
        {/await}
    </Container>
</Animate>
<style lang="scss">
</style>