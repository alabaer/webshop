class ProductsCreation {
    constructor() {
        this.$outputfield = $('#products-output');
    }

    initGUIevents() {
        this.productsViewEvent();
    }

    productsViewEvent() {
        let self = this;
        $('#category-container').on('click', 'button', function () {
            let url = $(this).attr('value');
            self.getProducts(url);
        });
    }

    getProducts(url) {
        let self = this;

        $.ajax({
            url: url,
            method: "GET"
        })
            .done((response) => {
                self.resetProductList();
                //Der ProductsCreation Array besteht aus einem leeren Array, deswegen die Bedingung
                if (self.productsExists(response)) {
                    self.fillProductSpace(response);
                }
                if (!self.productsExists(response)) {
                    self.productsEmpty()
                }

            })
            .fail(function (error) {
                self.showError(error);
            });
    }


    productsExists(response) {
        return response.products[0].length !== 0
    }

    productsEmpty() {
        let $errorMessage = $('<h1>Sorry no Products available at the moment</h1>')
        this.$outputfield.append($errorMessage)
    }


    fillProductSpace(response) {
        let $products = response.products;
        for (let product of $products) {
            this.drawProduct(product);

        }
    }

    drawProduct(product) {
        let name = product.name;
        let id = product.id;
        let $productSpace = $('<div class = "card text-center col-2 m-1"></div>')
        let $productBody = this.createProductBody(id, name)
        $productSpace.attr('id', name);
        $productSpace.append($productBody)
        this.$outputfield.append($productSpace);

    }

    createProductBody($id, $name) {

        let $productBody = $('<div class="card-body"></div>');
        let $productName = $('<div class="card-title"></div>');
        let $buyButton = this.createBuyButton($id, $name);
        $productName.text($name);
        $productBody.append($productName);
        $productBody.append($buyButton);
        return $productBody;

    }

    createBuyButton($id, $name) {
        let $buyButton = $('<button class="btn btn-success mt-2"></button>');
        $buyButton.attr({
            'id': $name,
            'value': 'http://localhost/theShop/API/index.php?resource=cart&articleId=' + $id
        });
        $buyButton.text('Buy');
        return $buyButton
    }

    resetProductList() {
        this.$outputfield.empty();
    }

    showError(error) {
        console.error(error);
    }
}