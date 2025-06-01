document.querySelectorAll('.favorite-item').forEach(item => {
    item.addEventListener('click', function () {
        let encryptedProductId = this.dataset.productId; // Получаем зашифрованный ID товара

        // Переход к странице товара
        window.location.href = `/productInfoList/${encryptedProductId}`;
    });
});


document.querySelectorAll('.remove-favorite-btn').forEach(button => {
    button.addEventListener('click', function (event) {
        event.stopPropagation(); // Предотвращаем переход на страницу товара

        let encryptedProductId = this.dataset.productId;

        fetch('/favorite/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: encryptedProductId })
        })
        .then(response => response.json())
        .then(data => {
            this.closest('.favorite-item').remove(); // Удаляем карточку со страницы
            alert('Товар был удалён из избранного');
        })
        .catch(error => console.error('Ошибка:', error));
    });
});