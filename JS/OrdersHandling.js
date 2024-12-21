class OrdersHandling {
    constructor() {
        this.sucessModal = $('#success-modal');
        this.failureModal = $('#failure-modal');
        this.orderHistoryToggler = $('#order-button-toggler');
        this.order = new OrderHistoryCreation();
        this.cart = new CartHandling();
    }

    orderEvents() {
        this.placeOrderEvent();
        this.getOrderEvent();
    }

    placeOrderEvent() {
        let self = this;
        $('#buy-button').on('click', function () {
            self.placeOrder();
        });
    }

    getOrderEvent() {
        let self = this;
        $('#order-history-view').on('click', function () {
            self.order.$outputfield.empty();
            self.getOrder();
        });
    }

    placeOrder() {

        let self = this;

        $.ajax({
            url: "http://localhost/theShop/API/index.php?resource=orders",
            method: "POST"
        })
            .done((response) => {
                if (response.state === "Okay") {
                    self.sucessModal.modal('show');
                    self.cart.getCartItems();
                } else {
                    self.failureModal.modal('show');
                }

            })
            .fail(function (error) {
            });

    }


    getOrder() {
        let self = this;

        $.ajax({
            url: "http://localhost/theShop/API/index.php?resource=orders",
            method: "GET"
        })
            .done((response) => {
                if (self.orderIsEmpty(response)) {
                    self.order.$emptyHistoryMessage.show();
                    self.order.$orderTableOutput.hide();
                } else {
                    self.order.$orderTableOutput.show();
                    self.order.$emptyHistoryMessage.hide();
                    self.order.createHistory(response);
                }
            })
            .fail(function (error) {
            });

    }

    orderIsEmpty(response) {
        return response.orders.length === 0;
    }

    showOrderHistoryButton() {
        this.orderHistoryToggler.removeClass('d-none');
    }

    hideOrderHistoryButton() {
        this.orderHistoryToggler.addClass('d-none');
    }
}