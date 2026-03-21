<x-app-layout>
    <section class="catalog-hero">
        <div class="container">
            <p class="market-kicker">FAQ</p>
            <h1>Frequently asked questions</h1>
            <p>Clear answers to common planning, preparation, and booking questions.</p>
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="faq-stack">
                @foreach ($faqs as $faq)
                    <article class="faq-card">
                        <h3>{{ $faq['question'] }}</h3>
                        <p>{{ $faq['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
