document.addEventListener("alpine:init", () => {
    const profileForm = document.getElementById('your-profile');
    profileForm && profileForm.setAttribute('enctype', 'multipart/form-data');

    let isUnlocked = true;

    function isDefined(obj) {
        return !isUndefined(obj);
    }

    function isUndefined(obj) {
        return obj === undefined || obj === null;
    }

    function nonEmpty(str) {
        return !isEmpty(str);
    }

    function isEmpty(str) {
        return isUndefined(str) || str.trim().length === 0;
    }

    Alpine.store("tab", (Name) => {
        return {
            selected: 0,
        };
    });
    Alpine.store("explorer_widget", (restUrl) => {

        return {
            selectedItem: 0,
            contents: '',
            page: 1,
            kind: 'all',
            hasNext: true,
            firstTime: true,
            select(index, kind) {

                if (kind === this.kind) return;
                else this.contents = '';

                this.selectedItem = index;
                this.page = 1;
                this.hasNext = true;
                this.kind = kind;
                this.doCall();
            },
            showMore() {
                this.page++;
                this.doCall()
            },
            doCall() {
                this.loaderOn = true;
                const scrollTop = window.scrollY;

                fetch(`${restUrl}?action=mc_search_dc&mode=${this.kind}&page=${this.page}`, {method: 'get'})
                    .then(response => response.json())
                    .then(json => {
                        if (json.content) {
                            this.contents += json.content;
                        } else {
                            this.hasNext = false;
                        }

                        // $("html, body").animate({ scrollTop: "1000px" });
                        if (this.firstTime) {
                            this.firstTime = false;
                        } else {
                            requestAnimationFrame(() => {
                                window.scrollTo(0, scrollTop);
                            });
                        }
                    })
                    .finally(() => this.loaderOn = false);

            }
        };
    });
    Alpine.store("explorer_widget_editor", (initValue, fieldName) => {
        console.log(initValue)
        return {
            selections: initValue,
            fieldName,
            addSelection() {
                this.selections.push({title: '', post: []});
            },
            removeItem(index) {
                this.selections.splice(index, 1);
            }
        };
    });
    Alpine.data("mc_app", () => {
        return {
            loaderOn: true,
            profileOn: false,
            profile: {},
            validation: {
                validationType: undefined,
                validationMessage: undefined,
            },
            invokeUrl(tagName, value, baseUrl) {
                window.location.href = `${baseUrl}&k=${tagName}&v=${value}&c=add`;
            },
            goBack() {
                history.back();
            }
        };
    });

});

