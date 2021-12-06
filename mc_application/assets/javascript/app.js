document.addEventListener("alpine:init", () => {
    Alpine.data("mc_app", () => {
        return {
            loaderOn: true,
            profileOn: true,
            goBack() {
                history.back();
            }
        };
    });
});