window.addEventListener("load", () => {

    const components = [MainLayout, PostResume, DropdownItem, Dropdow, VideoPlayer, Footer];

    const app = Vue
        .createApp({
            data() {
                return {openFilter: false};
            },
            methods: {
                toggleFilters() {
                    this.openFilter = !this.openFilter;
                }
            }
        });

    components.forEach(x => app.component(x.name, x));
    app.mount(document.body);
});