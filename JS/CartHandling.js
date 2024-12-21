class CartHandling {
    constructor() {
        this.cart = new CartCreation();
    }

    cartHandlingEvents() {
        this.addToCartEvent();
        this.removeFromCartEvent();
        this.cartCreationEvent();
    }

    cartCreationEvent() {
        $('#cart-view').on('click', () => {
            this.getCartItems();
        });
    }

    getCartItems() {
        let self = this;

        $.ajax({
            url: "http://localhost/theShop/API/index.php?resource=cart",
            method: "GET"
        })
            .done((response) => {
                if (self.CartIsEmpty(response)) {
                    self.cart.$cartTableOutput.hide();
                    self.cart.$emptyCartMessage.show();
                } else {
                    self.cart.$emptyCartMessage.hide();
                    self.cart.$cartTableOutput.show();
                    self.cart.fillCart(response)
                }

            })
            .fail(function (error) {
                self.showError(error);
            });

    }

    CartIsEmpty(response) {
        return response.cart.length === 0
    }

    addToCartEvent() {
        let self = this;
        $('#cart-output').on('click', 'button[name="AddButton"]', function () {
            let HTTPVerb = "POST";
            let url = $(this).attr('value');
            self.manipulateItems(url, HTTPVerb);
        });
    }

    removeFromCartEvent() {
        let self = this;
        $('#cart-output').on('click', 'button[name="RemoveButton"]', function () {
            let HTTPVerb = "DELETE";
            let url = $(this).attr('value');
            self.manipulateItems(url, HTTPVerb);
        });
    }

    manipulateItems(url, HTTPVerb) {
        let self = this;

        $.ajax({
            url: url,
            method: HTTPVerb
        })
            .done(() => {
                self.getCartItems();

            })
            .fail(function (error) {
                self.showError(error);
            });
    }

    showError(error) {
        console.error(error);
    }


}