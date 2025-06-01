document.addEventListener("DOMContentLoaded", function () {
    const productContainer = document.querySelector(".product-container");

    // Загружаем сохраненные количества из LocalStorage
    document.querySelectorAll(".quantity-input").forEach(input => {
        const orderItemId = input.dataset.orderItemId;
        const savedQuantity = localStorage.getItem(`order_quantity_${orderItemId}`);
        if (savedQuantity) input.value = savedQuantity;
    });

    // Обрабатываем изменение количества товаров (через `event delegation`)
    productContainer.addEventListener("change", function (event) {
        if (event.target.classList.contains("quantity-input")) {
            const orderItemId = event.target.dataset.orderItemId;
            localStorage.setItem(`order_quantity_${orderItemId}`, event.target.value);
        }
    });

    // Автообновление в БД раз в 5 минут
    setInterval(async function () {
        const updatedQuantities = collectUpdatedQuantities();
        await updateOrderBatch(updatedQuantities);
    }, 300000);

    function collectUpdatedQuantities() {
        const updatedQuantities = {};
        document.querySelectorAll(".quantity-input").forEach(input => {
            const orderItemId = input.dataset.orderItemId;
            updatedQuantities[orderItemId] = input.value;
        });
        return updatedQuantities;
    }

    async function updateOrderBatch(updatedQuantities) {
        try {
            await fetch("/order/update-batch", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify(updatedQuantities)
            });
        } catch (error) {
            console.error("Ошибка обновления:", error);
        }
    }

    // Обновление при закрытии страницы с `sendBeacon()`
    window.addEventListener("beforeunload", function () {
        const updatedQuantities = collectUpdatedQuantities();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const data = new Blob([JSON.stringify({ csrf_token: csrfToken, quantities: updatedQuantities })], { type: "application/json" });
        navigator.sendBeacon("/order/update-batch", data);
    });

    // Удаление товара при `quantity = 0`
    productContainer.addEventListener("change", function (event) {
        if (event.target.classList.contains("quantity-input")) {
            const orderItemId = event.target.dataset.orderItemId;
            if (event.target.value <= 0) {
                if (!confirm("Вы уверены, что хотите удалить этот товар из заказа?")) {
                    event.target.value = 1;
                } else {
                    fetch(`/order/delete/${orderItemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    }).then(() => location.reload());
                }
            }
        }
    });

    // Удаление товара по кнопке
    productContainer.addEventListener("click", function (event) {
        if (event.target.classList.contains("delete-item-btn")) {
            const orderItemId = event.target.dataset.orderItemId;
            if (confirm("Вы уверены, что хотите удалить этот товар из заказа?")) {
                fetch(`/order/delete/${orderItemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                }).then(() => location.reload());
            }
        }
    });

    // Оплата заказа с отображением общей суммы
    document.getElementById("checkout-btn").addEventListener("click", async function () {
        let totalAmount = 0;
        const updatedQuantities = collectUpdatedQuantities();

        document.querySelectorAll(".quantity-input").forEach(input => {
            const productCard = input.closest(".product-card");
            const productPrice = parseFloat(productCard.querySelector(".price").innerText);
            totalAmount += productPrice * input.value;
        });

        if (!confirm(`Ваш общий счёт: ${totalAmount.toFixed(2)} ₽. Вы уверены, что хотите оплатить заказ?`)) return;

        try {
            await fetch("/order/update-batch", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify(updatedQuantities)
            });

            const response = await
                fetch("/checkout", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });
            const data = await response.json();
            alert(data.message);
        } catch (error) {
            console.error("Ошибка:", error);
        }
    });
});
