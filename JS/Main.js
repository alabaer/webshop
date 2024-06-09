$(document).ready(function () {
    let myProductHandling = new ProductsHandling();
    let myCartHandling = new CartHandling();
    let myCategories = new CategoryCreation();
    let myProductsCreation = new ProductsCreation();
    let myView = new View();
    myCategories.initGUIevents();
    myProductsCreation.initGUIevents();
    myView.initViewEvents();
    myProductHandling.productsHandlingEvents();
    myCartHandling.cartHandlingEvents();
});