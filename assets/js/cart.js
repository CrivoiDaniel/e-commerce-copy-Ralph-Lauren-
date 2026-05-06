
function toggleCart() {
    const cart = document.getElementById('cart');
    
    if (!cart) {
        console.error('Cart element not found');
        return;
    }
    
    if (cart.classList.contains('w-0')) {
        cart.classList.remove('w-0');
        cart.classList.add('w-96');
    } else {
        cart.classList.remove('w-96');
        cart.classList.add('w-0');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.location.search.includes('cart=open')) {
        toggleCart();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const cart = document.getElementById('cart');
    
    if (cart) {
        document.addEventListener('click', function(event) {
            const isClickInsideCart = cart.contains(event.target);
            const isCartButton = event.target.closest('[onclick*="toggleCart"]');
            
            if (!isClickInsideCart && !isCartButton && !cart.classList.contains('w-0')) {
                toggleCart();
            }
        });
    }
});