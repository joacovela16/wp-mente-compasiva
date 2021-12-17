document.addEventListener("alpine:init", () => {
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
            submitProfile() {
                const me = this,
                    profile = me.profile,
                    validation = me.validation;

                // validate
                let validationType = "invalid_field",
                    validationMessage = undefined;

                if (isUnlocked) {
                    isUnlocked = false;

                    if (isEmpty(profile.name)) {
                        validationMessage = "name";
                        return;
                    }

                    if (isEmpty(profile.email)) {
                        validationMessage = "email";
                        isUnlocked = true;
                        return;
                    }

                    const formData = new FormData();

                    if (nonEmpty(profile.current_pwd) || nonEmpty(profile.new_password) || nonEmpty(profile.confirm_password)) {
                        if (isEmpty(profile.current_pwd)) {
                            validationMessage = `current_pwd`;
                            return;
                        }

                        if (isEmpty(profile.new_password) || isEmpty(profile.confirm_password) || profile.new_password !== profile.confirm_password) {
                            validationMessage = `confirm_password`;
                            return;
                        }

                        formData.append("current_pwd", profile.current_pwd);
                        formData.append("new_password", profile.new_password);
                        formData.append("confirm_password", profile.confirm_password);
                    }

                    if (profile.file) formData.append("file", profile.file);

                    profile.name && formData.append("display_name", profile.name);
                    profile.email && formData.append("user_email", profile.email);
                    profile.website && formData.append("user_url", profile.website);
                    profile.description && formData.append("description", profile.description);

                    fetch("/wp-json/mcplugin/v1/user", {
                        method: 'POST',
                        headers: {
                            'accept': 'application/json',
                            "enctype": "multipart/form-data",
                            'X-WP-Nonce': MCApp.nonce
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(response => {

                            validationType = "success";
                            validationMessage = "data_saved";

                            const ud = response.userData;
                            profile.name = ud.display_name || profile.name;
                            profile.email = ud.user_email || profile.email;
                            profile.website = ud.user_url || profile.website;
                            profile.description = ud.description || profile.description;

                            if (ud.user_avatar_url) {
                                me.$refs.profileAvatar.src = ud.user_avatar_url
                                me.$refs.navbarAvatar.src = ud.user_avatar_url
                            }

                            isUnlocked = true;
                            validation.validationType = validationType;
                            validation.validationMessage = validationMessage;
                        })
                        .catch(e => {
                            console.log(e);
                            isUnlocked = true;
                            validation.validationType = "unexpected_error";
                            validation.validationMessage = "error_updating_user";
                        })

                }

            },
            goBack() {
                history.back();
            }
        };
    });
});