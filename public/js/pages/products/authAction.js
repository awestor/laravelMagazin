document.addEventListener("DOMContentLoaded", function () {
    const favoriteBtn = document.getElementById("favorite-btn");

    // Проверка на нахождение товара в избранном
    fetch(`/favorite/status`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.isFavorite) {
            favoriteBtn.classList.add("active"); // Для смены цвета сердечка
            favoriteBtn.dataset.status = "on";
        } else {
            favoriteBtn.classList.remove("active"); 
            favoriteBtn.dataset.status = "off";
        }
    })
    .catch(error => console.error("Ошибка:", error));
});


document.addEventListener("DOMContentLoaded", function () {
    const favoriteBtn = document.getElementById("favorite-btn");
    let debounce = false; // Флаг, предотвращающий частые запросы

    favoriteBtn.addEventListener("click", function () {
        if (debounce) return;
        debounce = true;

        let isFavorite = favoriteBtn.dataset.status === "on";

        fetch("/favorite/toggle", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                favoriteBtn.dataset.status = isFavorite ? "off" : "on";
                favoriteBtn.classList.toggle("active", !isFavorite);
            } else {
                alert("Ошибка добавления в избранное");
            }
            debounce = false;
        })
        .catch(error => {
            console.error("Ошибка:", error);
            debounce = false;
        });
    });
});





document.getElementById('addToOrder-btn').addEventListener('click', function () {
    fetch('/addToOrder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(error => console.error('Ошибка:', error));
});