<x-app-layout>
    <section class="catalog-hero">
        <div class="container">
            <p class="market-kicker">Resources</p>
            <h1>Stories, advice, and trek planning notes</h1>
            <p>Read practical guidance and inspiration for building stronger adventures.</p>
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="market-card-grid market-card-grid--three">
                @foreach ($posts as $post)
                    <article class="market-card">
                        <div class="market-card__media market-card__media--icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="market-card__body">
                            <div class="market-card__meta">
                                <span>{{ $post['category'] }}</span>
                                <span>{{ $post['reading_time'] }}</span>
                            </div>
                            <h3>{{ $post['title'] }}</h3>
                            <p>{{ $post['excerpt'] }}</p>
                            <div class="market-card__footer">
                                <div>
                                    <strong>{{ $post['author'] }}</strong>
                                    <span>{{ $post['date'] }}</span>
                                </div>
                                <span class="market-button market-button--ghost">Read More</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
