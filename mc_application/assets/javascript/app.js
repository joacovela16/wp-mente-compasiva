document.addEventListener("alpine:init", () => {
    Alpine.data("mc_app", () => {
        return {
            loaderOn: true,
            profileOn: true,
            profile: {
            },
            submitProfile(){
                console.log(this.profile.name)

            },
            goBack() {
                history.back();
            }
        };
    });
});