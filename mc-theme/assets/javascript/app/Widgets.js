function mountApp() {
    const components = [MainLayout, PostResume, DropdownItem, Dropdow, VideoPlayer, Footer, TodoWidget, ResumeContainer];

  /*  const app = Vue
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

    // const elementsByClassName = document.getElementsByClassName("mc-app");
    // Array.from(elementsByClassName).forEach(el => app.mount(el));
    const main = document.getElementById("mc-app") || document.getElementById("widget-area")
    app.mount(main);*/
}

window.addEventListener("load", () => {
    mountApp()
});

