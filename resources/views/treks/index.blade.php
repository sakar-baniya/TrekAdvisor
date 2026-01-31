<x-app-layout>
    <div class="trek-gallery-container">
        <header class="gallery-header">
            <h1>Explore the Majestic Himalayas</h1>
            <p>Discover your next adventure from our curated list of unique trekking experiences.</p>
        </header>

        <div class="gallery-filters">
            <form action="{{ route('treks.index') }}" method="GET">
                <select name="difficulty" onchange="this.form.submit()">
                    <option value="">All Difficulties</option>
                    <option value="Easy" {{ request('difficulty') == 'Easy' ? 'selected' : '' }}>Easy</option>
                    <option value="Moderate" {{ request('difficulty') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="Difficult" {{ request('difficulty') == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                    <option value="Extreme" {{ request('difficulty') == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                </select>
                @if(request('difficulty'))
                    <a href="{{ route('treks.index') }}" class="clear-filter">Clear</a>
                @endif
            </form>
        </div>

        <div class="trek-grid">
            @foreach($treks as $trek)
                <div class="trek-card">
                    <div class="card-image-wrapper">
                        <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=640&q=80' }}" alt="{{ $trek->title }}">
                        <div class="difficulty-badge difficulty-{{ strtolower($trek->difficulty) }}">{{ $trek->difficulty }}</div>
                    </div>
                    <div class="card-content">
                        <h3>{{ $trek->title }}</h3>
                        <p class="trek-price">Starting from <span>${{ number_format($trek->base_price) }}</span></p>
                        <p class="trek-excerpt">{{ Str::limit($trek->description, 100) }}</p>
                        <a href="{{ route('treks.show', $trek->slug) }}" class="btn-primary">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $treks->links() }}
        </div>
    </div>

    <style>
        .trek-gallery-container {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            font-family: 'Inter', sans-serif;
        }

        .gallery-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .gallery-header h1 {
            font-size: 3rem;
            color: #1a202c;
            margin-bottom: 1rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .gallery-header p {
            font-size: 1.25rem;
            color: #4a5568;
            max-width: 700px;
            margin: 0 auto;
        }

        .gallery-filters {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
        }

        .gallery-filters select {
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .gallery-filters select:hover {
            border-color: #3182ce;
        }

        .clear-filter {
            margin-left: 1rem;
            color: #3182ce;
            text-decoration: none;
            font-size: 0.9rem;
            align-self: center;
        }

        .trek-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 3rem;
        }

        .trek-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            border: 1px solid #f7fafc;
        }

        .trek-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-image-wrapper {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .card-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .trek-card:hover .card-image-wrapper img {
            transform: scale(1.1);
        }

        .difficulty-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            backdrop-filter: blur(8px);
        }

        .difficulty-easy { background: rgba(72, 187, 120, 0.9); color: white; }
        .difficulty-moderate { background: rgba(66, 153, 225, 0.9); color: white; }
        .difficulty-difficult { background: rgba(237, 137, 54, 0.9); color: white; }
        .difficulty-extreme { background: rgba(245, 101, 101, 0.9); color: white; }

        .card-content {
            padding: 2rem;
        }

        .card-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.75rem;
        }

        .trek-price {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .trek-price span {
            color: #3182ce;
            font-size: 1.25rem;
            font-weight: 800;
            margin-left: 0.25rem;
        }

        .trek-excerpt {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-primary {
            display: inline-block;
            width: 100%;
            text-align: center;
            background: linear-gradient(135deg, #3182ce 0%, #2b6cb0 100%);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(49, 130, 206, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2b6cb0 0%, #2c5282 100%);
            box-shadow: 0 10px 15px rgba(49, 130, 206, 0.3);
        }

        .pagination-wrapper {
            margin-top: 4rem;
            display: flex;
            justify-content: center;
        }
    </style>
</x-app-layout>
