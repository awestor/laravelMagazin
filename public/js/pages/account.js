document.getElementById("edit-btn").addEventListener("click", function () {
    document.querySelectorAll(".account-info span").forEach(span => {
        let input = document.createElement("input");
        input.type = "text";
        input.value = span.innerText;
        input.dataset.field = span.id;
        input.classList.add("edit-field");
        span.replaceWith(input);
    });

    this.style.display = "none";
    document.getElementById("save-btn").style.display = "block";
    document.getElementById("cancel-btn").style.display = "block";
});

document.getElementById("cancel-btn").addEventListener("click", function () {
    window.location.reload();
});

document.getElementById("save-btn").addEventListener("click", function () {
    let updatedData = {};

    document.querySelectorAll("input.edit-field").forEach(input => {
        updatedData[input.dataset.field] = input.value;
    });

    fetch("/account/update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(updatedData)
    }).then(response => response.json())
      .then(data => {
          document.getElementById("notification").style.display = "block"; // Показываем уведомление
          setTimeout(() => window.location.reload(), 1500); // Обновляем страницу через 1.5 сек
      })
      .catch(error => console.error("Ошибка:", error));
});

document.getElementById("change-password-btn").addEventListener("click", function () {
    let oldPassword = document.getElementById("old-password").value;
    let newPassword = document.getElementById("new-password").value;
    let confirmPassword = document.getElementById("confirm-password").value;

    if (!oldPassword || !newPassword || !confirmPassword) {
        alert("Заполните все поля смены пароля.");
        return;
    }

    if (newPassword !== confirmPassword) {
        alert("Новый пароль не совпадает с подтверждением.");
        return;
    }

    fetch("/account/password-update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ old_password: oldPassword, new_password: newPassword })
    }).then(response => response.json())
      .then(data => {
          alert(data.message);
          document.getElementById("old-password").value = "";
          document.getElementById("new-password").value = "";
          document.getElementById("confirm-password").value = "";
      })
      .catch(error => console.error("Ошибка:", error));
});
