document.addEventListener("alpine:init", () => {
    Alpine.data("mc_app", () => {
        return {
            loaderOn: true,
            profileOn: false,
            goBack() {
                history.back();
            }
        };
    });
});