class ProductsHandling {
    constructor() {
        this.$modal = $('#product-modal');
        this.$modalBody = $('#product-modal-body');
    }

    productsHandlingEvents() {
        this.addProductEvent();
    }

    addProductEvent = () => {
        let self = this;
        $('#products-container').on('click', 'button', function () {
            let $url = $(this).attr('value');
            let $name = $(this).attr('id');
            self.addProductToCart($url, $name)
        });
    };

    addProductToCart(url, name) {
        let self = this;

        $.ajax({
            url: url,
            method: "POST"
        })
            .done((response) => {
                self.showResponse(response, name);
            })
            .fail(function (error) {
                self.showError(error);
            });
    }

    showResponse(response, name) {
        let buyPhrase = ' added to cart'
        this.$modal.modal('show');
        this.$modalBody.text(name + buyPhrase);
        console.log(response.state);
    }
}