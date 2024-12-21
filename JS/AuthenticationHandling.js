class AuthenticationHandling {
    constructor() {
        this.$sucessModal = $('#success-modal');
        this.$failureModal = $('#failure-modal');
        this.view = new Views();
    }

    authenticationEvent() {
        this.loginEvent();
        this.logoutEvent();
    }

    loginEvent() {
        let self = this;

        $('#login-form').submit(function (event) {
            event.preventDefault();

            let username = $('#username').val();
            let password = $('#password').val();

            let formData = {
                username: username,
                password: password
            };
            self.loginUser(formData);
        });
    }

    logoutEvent() {
        let self = this;
        $('#logout-button').on('click', function () {
            self.logoutUser();
        });
    }

    loginUser(formData) {
        let self = this;

        $.ajax({
            url: "http://localhost/theShop/API/index.php?action=login",
            method: "POST",
            data: formData
        })
            .done(function (response) {
                if (response.state === "OK") {
                    self.$sucessModal.modal('show');
                    self.view.startView();
                    self.view.logedInView();


                } else {
                    self.$failureModal.modal('show');
                }
            })
            .fail(function (error) {
                alert("Failed to log in. Something went wrong");
                console.log(error);
            });
    }


    logoutUser() {
        let self = this;
        $.ajax({
            url: "http://localhost/theShop/API/index.php?action=logout",
            method: "POST",
        })
            .done(function (response) {
                if (response.state === "OK") {
                    self.$sucessModal.modal('show');
                    self.view.startView();
                    self.view.logedOutView();
                } else {
                    self.$failureModal.modal('show');
                }
            })
            .fail(function (error) {
                self.$failureModal.modal('show');
                console.log(error);
            });
    }
}

