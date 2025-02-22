document.addEventListener('DOMContentLoaded', function () {
    const productList = document.getElementById('product_list');
    if (!productList) {
        return;
    }
    productList.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-remove')) {
            const productId = event.target.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/wishlist/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ game_id: productId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateWishlistCount();
                    document.getElementById(`product-${productId}`).remove();
                    if (document.getElementById('product_list').childElementCount < 1) {
                        noProductsInWishlist();
                    }
                } else {
                    alert('An error occurred while removing the product.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
});

function noProductsInWishlist() {
    const wishlistItems = document.getElementById('wishlist_id');
    wishlistItems.innerHTML = ''; // Clear existing content

    const noProducts = document.createElement('div');
    noProducts.classList.add('empty-wishlist-message');
    noProducts.innerHTML = `
        <i class="fas fa-heart"></i>
        <p id="primary-empty-message">Your wishlist is empty.</p>
        <p id="secondary-empty-message">Time to fill it up with epic deals! Browse now and snag your next favorite game at an unbeatable price!</p>
        <a href="/explore" class="btn">Explore games</a>
    `;
    wishlistItems.appendChild(noProducts);

    // Remove the wishlist header
    const wishlistHeader = document.querySelector('.wishlist-header');
    if (wishlistHeader) {
        wishlistHeader.remove();
    }
}