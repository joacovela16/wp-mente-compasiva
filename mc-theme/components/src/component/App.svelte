<script>
    import Router from 'svelte-spa-router';
    import {parse} from "query-string"
    import Navbar from "./Navbar.svelte";
    import MainView from "./MainView.svelte";
    import {onMount, setContext} from "svelte";
    import SearchView from "./search/SearchView.svelte";
    import NotFound from "./404.svelte";
    import PostView from "./PostView.svelte";
    import Loader from "./Loader.svelte";
    import {default as Axios} from "axios";
    import Footer from "./Footer.svelte";
    import SlideBar from "./SlideBar.svelte";

    export let appConf = {};
    export let readyCallback = () => {
    };

    const defaults = {
        filterOptions: {
            countries: ["Uruguay", "Argentina", "Brasil"],
            multimedia: ["Video", "Audio"],
            reading: ["News", "Blog", "Book", "Article", "Thesis", "Website"],

        },
        mainContent: "<p>pepe</p>"
    };

    const routes = {
        '/': MainView,
        '/search': SearchView,
        '/post/:type/:id': PostView,
        "*": NotFound
    }

    setContext("mc-context", {...defaults, ...appConf});


    onMount(() => {
        if (readyCallback) {
            readyCallback();
        }

        const p = parse(window.location.search);
        const postType = Object.keys(p).find(x => !x.startsWith("preview"));
        if (postType) {
            const slug = p[postType];
            if (slug) {
                Axios.get('index.php', {params: {rest_route: `/wp/v2/${postType}`, slug}}).then(x => {
                    const data = x.data;
                    if (data.length > 0) {
                        const item = data[0];
                        location.href = `/#/post/${postType}/${item.id}`
                    }
                })
            }
        }

    })
</script>

<Loader/>
<Navbar/>
<Router {routes}/>
<SlideBar/>
<Footer/>


