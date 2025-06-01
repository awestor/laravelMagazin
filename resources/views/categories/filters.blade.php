<div class="filters">
    <input type="text" id="searchInput" placeholder="Поиск товаров..." />

    <label>Цена от:</label>
    <input type="number" id="filterPriceMin" class="filter" placeholder="Мин. цена">
    <label>до:</label>
    <input type="number" id="filterPriceMax" class="filter" placeholder="Макс. цена">

    <label>Бренд:</label>
    <select id="filterBrand" class="filter">
        <option value="">Любой</option>
    </select>

    <label>Рейтинг:</label>
    <input type="number" id="filterRating" class="filter" placeholder="Мин. рейтинг">

    <label>Товар:</label>
    <select id="leafCategory" class="filter">
        <option value="">Любой</option>
    </select>

    <label>
        <input type="checkbox" id="filterDiscount" class="filter"> Только со скидкой
    </label>

    <label>Сортировка:</label>
    <select id="filterSort" class="filter">
        <option value="price_asc">Цена ↑</option>
        <option value="price_desc">Цена ↓</option>
        <option value="rating_desc">Рейтинг ↑</option>
    </select>
</div>
