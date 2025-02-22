document.addEventListener('DOMContentLoaded', function() {
    const gameCards = document.querySelectorAll('.game-card');

    gameCards.forEach(card => {
        card.addEventListener('click', function(event) {
            // Prevent the click event from propagating if the target is a button or link
            if (event.target.tagName !== 'BUTTON' && event.target.tagName !== 'A' && !event.target.closest('.add-to-wishlist')) {
                window.location.href = card.getAttribute('data-url');
            }
        });
    });
});