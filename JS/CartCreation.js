class CartCreation {
    constructor() {
        this.$outputfield = $('#cart-output');
        this.$pricefield = $('#price-output')
    }

    fillCart(response) {
        this.$outputfield.empty();
        for (let item of response) {
            this.drawItem(item);
            this.priceTotalOutput(this.getPriceTotal(response))
        }
    }

    priceTotalOutput(totalPrice) {
        let pricePhrase = "The Total Price is: "
        let dollar = " $"
        this.$pricefield.text(pricePhrase + totalPrice.toFixed(2) + dollar);
    }

    getPriceTotal(response) {
        let totalPrice = 0;
        for (let item of response) {
            totalPrice += item.totalPrice;
        }
        return totalPrice;
    }

    drawItem(item) {
        this.createItemBody(item)
    }

    createItemBody(item) {
        let id = item.id;
        let $row = $('<tr></tr>');
        let $name = $('<td class="text-center" title="articleName"></td>');
        let $amount = $('<td class="text-center" title="amount"></td>');
        let $price = $('<td class="text-center" title="price"></td>');
        let $productSum = $('<td class="text-center" title="productSum"></td>');
        let $buttons = $('<td class="text-center"></td>');
        let $addButton = this.createAddButton(id);
        let $removeButton = this.createRemoveButton(id);

        $row.attr('data-id', id);
        $name.text(item.articleName);
        $amount.text(item.amount);
        $price.text(item.price.toFixed(2));
        $productSum.text(item.totalPrice.toFixed(2));
        $buttons.append($addButton, $removeButton);
        $row.append($name, $amount, $price, $productSum, $buttons)
        this.$outputfield.append($row);

    }

    createAddButton(id) {
        let $addButton = $('<button class="btn btn-success mx-2"></button>');
        $addButton.attr({
            'id': id,
            'value': 'http://localhost/theShop/API/index.php?resource=cart&articleId=' + id,
            'name': "AddButton"
        });
        $addButton.text('Add');
        return $addButton
    }

    createRemoveButton(id) {
        let $removeButton = $('<button class="btn btn-danger mx-2" name="RemoveButton"></button>');
        $removeButton.attr({
            'id': id,
            'value': 'http://localhost/theShop/API/index.php?resource=cart&articleId=' + id,
            "name": "RemoveButton"
        });
        $removeButton.text('Remove');
        return $removeButton
    }
}