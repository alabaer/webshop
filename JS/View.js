class View {
    constructor() {
        this.$cart = $("#CartContainer")
        this.$shop = $("#ShopViewContainer")
    }

    initViewEvents() {
        this.startView();
        this.viewCart();
        this.viewShop();
    }

    startView() {
        this.$cart.hide();
        this.$shop.show();
    }

    viewCart() {
        $("#ShopView").on("click", () => {
            this.showShop();
        })
    }

    viewShop() {
        $("#CartView").on("click", () => {
            this.showCart()
        })
    }

    showCart() {
        this.$shop.hide();
        this.$cart.show();

    }

    showShop() {
        this.$cart.hide();
        this.$shop.show();
    }
}