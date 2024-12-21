$(document).ready(function () {
    $('#success-modal').load("assets/templates/successModal.html");
    $('#failure-modal').load("assets/templates/failureModal.html");
    let myView = new Views();
    let myProductHandling = new ProductsHandling();
    let myCartHandling = new CartHandling();
    let myCategories = new CategoryCreation();
    let myProductsCreation = new ProductsCreation();
    let myAuthentication = new AuthenticationHandling();
    let myOrder = new OrdersHandling();

    myCategories.initGUIevents();
    myProductsCreation.initGUIevents();
    myView.initViewEvents();
    myProductHandling.productsHandlingEvents();
    myCartHandling.cartHandlingEvents();
    myAuthentication.authenticationEvent();
    myOrder.orderEvents();
});