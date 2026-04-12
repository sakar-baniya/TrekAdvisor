<x-app-layout>
    <style>
        .guide-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .guide-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            min-height: 320px;
        }

        .guide-card__header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem 0;
            color: #1f2937;
            font-weight: 700;
        }

        .guide-card__header i {
            color: #e67e22;
        }

        .guide-card__body {
            padding: 0.5rem 1.5rem 1.5rem;
            display: grid;
            gap: 0.75rem;
        }

        .guide-meta {
            display: flex;
            gap: 0.75rem;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .guide-footer {
            margin-top: auto;
            padding: 0 1.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #6b7280;
        }

        @media (max-width: 1100px) {
            .guide-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .guide-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="catalog-hero">
        <div class="container">
            <p class="market-kicker">Resources</p>
            <h1>Stories, advice, and trek planning notes</h1>
            <p>Read practical guidance and inspiration for building stronger adventures.</p>
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="guide-grid">
                @foreach ($posts as $post)
                    <article class="guide-card">
                        <div class="guide-card__header">
                            <i class="fas fa-book-open"></i>
                            <span>{{ $post['category'] }}</span>
                        </div>
                        <div class="guide-card__body">
                            <div class="guide-meta">
                                <span>{{ $post['reading_time'] }}</span>
                                <span>{{ $post['date'] }}</span>
                            </div>
                            <h3>{{ $post['title'] }}</h3>
                            <p>{{ $post['excerpt'] }}</p>
                        </div>
                        <div class="guide-footer">
                            <strong>{{ $post['author'] }}</strong>
                            <span class="market-button market-button--ghost">Read More</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
