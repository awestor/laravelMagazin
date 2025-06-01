document.addEventListener("DOMContentLoaded", function () {
    let page = 1; // Номер страницы
    const productList = document.querySelector('.product-list');
    let isScrolling = false;
    loadFlag = true;

    function loadProducts() {
        fetch(`/mostProducts?page=${page}`)
            .then(response => response.json())
            .then(data => {
                const products = data;

                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.classList.add('product-card');
                    productCard.innerHTML = `
                        <img src="${product.image_url ?? '/images/no-image.png'}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>${product.total_sold ? `Продано: ${product.total_sold} шт.` : `Рейтинг: ⭐ ${product.avg_review ?? 'Нет отзывов'}`}</p>
                        <p class="brand">${product.brand_name}</p>
                    `;

                    productCard.addEventListener("click", function () {
                        window.location.href = `/productInfoList/${encodeURIComponent(product.encrypted_id)}`;
                    });

                    productList.appendChild(productCard);
                });

                page++;

                if (!(products.length < 30)) {
                    loadFlag = false;
                }
            })
            .catch(error => console.error("Ошибка загрузки товаров:", error));
    }

    function debounceScroll() {
        if (!isScrolling) {
            isScrolling = true;
            setTimeout(() => {
                if(loadFlag){
                    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                        loadProducts();
                    }
                }
                isScrolling = false;
            }, 300); // Ограничение в 0.3 секунды
        }
    }

    window.addEventListener('scroll', debounceScroll);

    loadProducts(); // Первая загрузка
});
