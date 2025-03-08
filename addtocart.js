function addToCart(id) {
    var cartItem = {
        id: productId
    };

    
    if (sessionStorage.cart) {
        
        var cartItems = JSON.parse(sessionStorage.cart);

        
        var existingItem = cartItems.find(function(item) {
            return item.id === cartItem.id;
        });

        if (existingItem) {
            
            existingItem.quantity += 1;
        } else {
            
            cartItems.push({
                id: cartItem.id,
                quantity: 1
            });
        }


        sessionStorage.cart = JSON.stringify(cartItems);
    } else {
        // Create a new cart and add the item
        var cartItems = [{
            id: cartItem.id,
            quantity: 1
        }];

        // Store the cart in the session
        sessionStorage.cart = JSON.stringify(cartItems);
    }

    alert('Product added to cart!');
}
