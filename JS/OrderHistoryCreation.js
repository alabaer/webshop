class OrderHistoryCreation {
    constructor() {
        this.$outputfield = $('#order-history-output');
        this.$orderTableOutput = $('#order-history-table-output');
        this.$emptyHistoryMessage = $('#empty-order-history');
    }

    createHistory(response) {
        for (let order of response.orders)
            this.drawOrder(order);
    }

    drawOrder(order) {
        this.createOrderBody(order);
    }

    createOrderBody(order) {
        let $row = $('<tr></tr>');
        let $orderNumber = $('<td class="text-center" title="order-number"></td>');
        let $orderDate = $('<td class="text-center" title="order-date"></td>');
        let $price = $('<td class="text-center" title="price"></td>');

        $orderNumber.text(order.orderId);
        $orderDate.text(order.date);
        $price.text(order.total);
        $row.append($orderNumber, $orderDate, $price)
        this.$outputfield.append($row);
    }
}