class Views {
    constructor() {
        this.$cart = $("#cart-container");
        this.$shop = $("#shop-view-container");
        this.$login = $("#login-container");
        this.$orderHistory = $('#order-history-container');
        this.$logoutToggler = $('#logout-toggler');
        this.$loginToggler = $('#login-toggler');
        this.$buyButtonToggler = $('#buy-button-toggler');
        this.$notLogedInMessage = $('#not-loged-in-message');
        this.ordersHistory = new OrdersHandling();
    }

    initViewEvents() {
        this.startView();
        this.viewCart();
        this.viewShop();
        this.viewLogin();
        this.viewOrderHistory();
    }

    startView() {
        this.$shop.show();
        this.$cart.hide();
        this.$login.hide();
        this.$orderHistory.hide();

    }
    logedInView() {
        this.showLogoutButton();
        this.$loginToggler.hide();
        this.ordersHistory.showOrderHistoryButton();
        this.$notLogedInMessage.hide();
        this.showBuyButton();
    }

    logedOutView(){
        this.hideLogoutButton();
        this.$loginToggler.show();
        this.ordersHistory.hideOrderHistoryButton();
        this.hideBuyButton();
        this.$notLogedInMessage.show();
    }

    viewCart() {
        $("#shop-view").on("click", () => {
            this.showShop();
        })
    }

    viewLogin() {
        $("#login-view").on("click", () => {
            this.showLogin()
        })

    }

    viewShop() {
        $("#cart-view").on("click", () => {
            this.showCart()
        })
    }

    viewOrderHistory() {
        $("#order-history-view").on("click", () => {
            this.showOrderHistory()
        })
    }

    showCart() {
        this.$orderHistory.hide();
        this.$login.hide();
        this.$shop.hide();
        this.$cart.show();

    }

    showShop() {
        this.$orderHistory.hide();
        this.$login.hide();
        this.$cart.hide();
        this.$shop.show();

    }

    showLogin() {
        this.$orderHistory.hide();
        this.$cart.hide();
        this.$shop.hide();
        this.$login.show();
    }


    showOrderHistory() {
        this.$orderHistory.show();
        this.$cart.hide();
        this.$shop.hide();
        this.$login.hide();
    }
    showLogoutButton() {
        this.$logoutToggler.removeClass('d-none');
    }

    showBuyButton() {
        this.$buyButtonToggler.removeClass('d-none');
    }

    hideBuyButton() {
        this.$buyButtonToggler.addClass('d-none');
    }

    hideLogoutButton() {
        this.$logoutToggler.addClass('d-none');
    }
}