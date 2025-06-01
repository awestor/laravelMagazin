
document.addEventListener('DOMContentLoaded', () => {
    const name = document.getElementById('name').value;
    console.log('name:', name); // название категории для дебага 

    if (name && !(sessionStorage.getItem('categoryName') == name)) {
        sessionStorage.setItem('categoryName', name);
    }

    const categoryName = sessionStorage.getItem('categoryName');
    let page = 1;
    let timeout;
    let requestCount = 0;
    let delay = 300;
    const observerTarget = document.getElementById("loadMoreTrigger");
    let canFetch = true;

    function getFilters() {
        return {
            category_name: categoryName,
            search: document.getElementById('searchInput').value,
            price_min: document.getElementById('filterPriceMin').value,
            price_max: document.getElementById('filterPriceMax').value,
            brand: document.getElementById('filterBrand').value,
            rating_min: document.getElementById('filterRating').value,
            discount: document.getElementById('filterDiscount').checked ? 1 : 0,
            sort: document.getElementById('filterSort').value,
            page: page,
        };
    }

    // Фильтрация товаров
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            requestCount++;
            delay = 300 + Math.min(requestCount * 50, 2000); // 0.3 сек + 0.05 сек * запросы (макс 2 сек)
            page = 1;
            loadProducts(this.value, true);
        }, delay);
    });

    document.querySelectorAll('.filter').forEach(filter => {
        filter.addEventListener('change', () => {
            page = 1;
            canFetch = true;
            loadProducts( true);
        });
    });





    
    // Автодогрузка товаров при прокрутке
    const observer = new IntersectionObserver((entries) => {
        if ( canFetch) {
            loadProducts();
        }
    }, { threshold: 1.0 });

    observer.observe(observerTarget);

    function loadProducts(reset = false) {
        fetch("/load-products", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(getFilters())
        })
        .then(response => response.json())
            .then(data => {
                const productContainer = document.getElementById("productList");
                if (reset) productContainer.innerHTML = ""; // Очистка списка при новом поиске

                if (data.length < 20) {
                    canFetch = false;
                }

                data.forEach(product => {
                    const productElement = document.createElement("div");
                    productElement.classList.add("product-card");
                    productElement.dataset.id = product.hashed_id;

                    let priceHTML = `<p class="price">${product.price} ₽</p>`; // ✅ Обычная цена

                    if (product.discount_price < product.price) {
                        priceHTML = `
                            <div class="price-container">
                                <p class="old-price">${product.price} ₽</p> 
                                <p class="discount-price">${product.discount_price.toFixed(2)} ₽</p>
                            </div>
                        `;
                    }

                    productElement.innerHTML = `
                        <h3>${product.name}</h3>
                        <img src=/${product.image} alt="${product.name}">
                        ${priceHTML} <!-- ✅ Показываем скидку -->
                        <p class="brand">${product.brand_name}</p>
                        <p class="rating">⭐ ${product.review}</p>
                    `;

                    productElement.addEventListener("click", function () {
                        window.location.href = `/productInfoList/${this.dataset.id}`;
                    });

                    productContainer.appendChild(productElement);
                });

                page++;
            });
    }    
});