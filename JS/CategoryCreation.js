class CategoryCreation {
    constructor() {
        this.$outputField = $('#category-output')
    }

    initGUIevents() {
        this.getCategories();
    }

    getCategories() {
        let self = this;

        $.ajax({
            url: "http://localhost/theShop/API/index.php?resource=types",
            method: "GET"
        })
            .done((response) => {
                self.fillCategories(response)


            })
            .fail(function (error) {
                self.showError(error);
            });
    }

    fillCategories(response) {
        for (let category of response) {
            this.createCategories(category)
        }
    }

    createCategories(category) {
        let $name = category.productType;
        let $id = category.productType;
        let $value = category.url;
        let $button = $('<button class="btn btn-light btn-lg col-auto"></button>');
        $button.attr({'id': $id, 'value': $value});
        $button.text($name);
        this.$outputField.append($button);
    }

    showError(error) {
        console.error(error);
    }
}