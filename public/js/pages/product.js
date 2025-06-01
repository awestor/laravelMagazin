fetch("/getProductInfo", {
    method: "GET",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }

})
.then(response => response.json())
    .then(data => {
        const infoContainer = document.getElementById("main-info");


        data.forEach(product => {
            const productInfo = document.createElement("div");
            productInfo.classList.add("info");
            console.log(product);
            productInfo.innerHTML = //<input type="hidden" id="id" value="${product.hashed_id}">
            `
                <h3>${product.name}</h3>
                <p class="rating">⭐ ${product.review}</p>
                <p>${product.price} ₽</p>
                <p class="brand">${product.brand_name}</p>
                <p class="brand">Товара в наличии: ${product.stock_quantity}</p>
                
            `;
            infoContainer.appendChild(productInfo);

            const productDescr = document.createElement("div");
            productDescr.classList.add("description");
            productDescr.innerHTML = 
            `
                <h3>${product.description}</h3>
            `;
            infoContainer.appendChild(productDescr);
        });
    }
);















async function fetchProductImages() {
    try {
        const response = await fetch(`/product-images`);
        if (!response.ok) throw new Error("Ошибка загрузки изображений");
        
        const images = await response.json();
        updateGallery(images);
    } catch (error) {
        console.error("Ошибка при получении изображений:", error);
    }
}
    

function updateGallery(images) {
    const mainImage = document.getElementById('main-image');
    const thumbnailsContainer = document.querySelector('.thumbnails-scroll');
    
    thumbnailsContainer.innerHTML = ""; // Очищаем контейнер миниатюр

    let currentMainImageIndex = 0;

    // Устанавливаем главное изображение
    const mainImageObj = images.find(img => img.image_type === "MAIN") || images[0];
    mainImage.src = mainImageObj.image_url;
    mainImage.alt = "Главное изображение";

    // Добавляем миниатюры
    images.forEach((image, index) => {
        const thumbnail = document.createElement('img');
        thumbnail.src = image.image_url;
        thumbnail.alt = `Миниатюра ${index + 1}`;
        thumbnail.className = 'thumbnail';

        if (image.image_type === "MAIN") {
            currentMainImageIndex = index;
            thumbnail.classList.add('active');
        }

        thumbnail.addEventListener('click', () => swapImages(index, images));

        thumbnailsContainer.appendChild(thumbnail);
    });
}

function swapImages(thumbnailIndex, images) {
    const mainImage = document.getElementById('main-image');

    mainImage.src = images[thumbnailIndex].image_url;
    mainImage.alt = `Изображение ${thumbnailIndex + 1}`;

    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.classList.toggle('active', index === thumbnailIndex);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    fetchProductImages();
});
    











let reviewsData = [];
let currentPage = 1;
const perPage = 3;
const batchSize = 15;
let page = 1;
let loadflag = true;

// Изначально загружаем первую порцию данных
document.addEventListener('DOMContentLoaded', () => {
    fetchReviews();
});

function fetchReviews() {
    fetch('/prodReviews', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Получение CSRF-токена
        },
        body: JSON.stringify({
            page: page
        })
    })
    .then(response => response.json())
    .then(newReviews => {
        console.log(newReviews);
        if (newReviews.length > 0) {
            if(newReviews.length<batchSize){
                loadflag = false;
            }
            reviewsData = [...reviewsData, ...newReviews]; // Добавляем новые данные
            renderPage(currentPage);
        } else {
            console.log('Нет новых данных');
        }
    })
    .catch(error => console.error('Ошибка загрузки:', error));
    page++;
}


function renderPage(page) {
    let container = document.getElementById('reviews');
    let paginate = document.createElement('div');
    paginate.classList.add('paginate');
    container.innerHTML = '';

    let start = (page - 1) * perPage;
    let end = start + perPage;
    let paginatedReviews = reviewsData.slice(start, end);

    paginatedReviews.forEach(review => {
        let reviewElement = document.createElement('div');
        reviewElement.classList.add('review');
        reviewElement.innerHTML = `
            <div class="review-corner" onclick="window.location.href='exampleUrl_${review.user_id}'"></div>
            <p><strong>Дата:</strong> ${review.created_at}</p>
            <p><strong>Рейтинг:</strong> ${review.rating} ⭐</p>
            <p><strong>Комментарий:</strong> ${review.comment}</p>
        `;
        container.appendChild(reviewElement);
    });

    if (reviewsData.length > perPage) {
        let totalPages = Math.ceil(reviewsData.length / perPage);
        for (let i = 1; i <= totalPages; i++) {
            let pageLink = document.createElement('button');
            pageLink.textContent = i;
            pageLink.classList.add('page-btn');
            pageLink.onclick = () => {
                currentPage = i;
                renderPage(i);
            };
            paginate.appendChild(pageLink);
        }
    }

    if (end >= reviewsData.length) {
        if(loadflag){
            let loadMoreBtn = document.createElement('button');
            loadMoreBtn.textContent = 'Загрузить еще';
            loadMoreBtn.classList.add('load-more-btn');
            
                loadMoreBtn.onclick = () => {
                    fetchReviews();
                };
            
            paginate.appendChild(loadMoreBtn);
        }
    }
    container.appendChild(paginate);
}


















