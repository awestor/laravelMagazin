@if(auth()->check())
    <div id="review-modal-container" class="modal hidden">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Добавить отзыв</h2>

            <form method="POST" action="{{ route('storeComment') }}">
                @csrf
                <label>Рейтинг:</label>
                <div class="rating-select">
                    @for ($i = 1; $i <= 10; $i++)
                        <span class="star" data-value="{{ $i }}">★</span>
                    @endfor
                    <input type="hidden" name="rating" id="rating-input" required>
                </div>

                <label for="comment">Комментарий:</label>
                <textarea id="comment" name="comment" required></textarea>

                <button type="submit" class="btn btn-success">Добавить</button>
            </form>
        </div>
    </div>
@endif
