class View {
    constructor() {
        this.$cart = $("#cart-container")
        this.$shop = $("#shop-view-container")
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
        $("#shop-view").on("click", () => {
            this.showShop();
        })
    }

    viewShop() {
        $("#cart-view").on("click", () => {
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