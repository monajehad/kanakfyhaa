@extends('website.layout.main')

@section('title', $product->city->name . ' - تجربة المنتج')

@section('content')
<style>
    .qr-section {
        background: var(--primary-white);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    [data-theme="dark"] .qr-section {
        background: #1a1a1a;
        box-shadow: 0 2px 15px rgba(255,255,255,0.05);
    }
    .qr-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--primary-black);
        margin-bottom: 25px;
        text-align: center;
    }
    .product-image {
        max-width: 300px;
        margin: 0 auto 30px auto;
        text-align: center;
    }
    .product-image img {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        object-fit: cover;
    }
    .city-info {
        text-align: center;
        margin-bottom: 40px;
    }
    .city-info h5 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-black);
        margin-bottom: 10px;
    }
    .city-info p {
        color: #555;
        line-height: 1.7;
        max-width: 700px;
        margin: 0 auto;
    }
    .landmark, .artifact {
        margin-bottom: 40px;
    }
    .landmark h5 {
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--primary-black);
        margin-bottom: 15px;
    }
    .artifact h6 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
    }
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    .grid img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .grid img:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .video-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        margin: 40px auto;
        width: 80%;
        max-width: 800px;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    @media (max-width: 768px) {
        .video-container {
            width: 100%;
        }
    }
    .video-container video {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 25px;
        object-fit: cover;
        filter: brightness(95%);
        transition: filter 0.3s ease;
    }
    .video-container:hover video {
        filter: brightness(100%);
    }
    .audio-container {
        position: relative;
        background: linear-gradient(135deg, #fafafa, #f1f1f1);
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin: 25px auto;
        width: 80%;
        max-width: 800px;
    }
    .audio-container audio {
        width: 100%;
        outline: none;
    }
</style>

<section class="container mx-auto px-4 py-12">
    <div class="qr-section">
        <h1 class="qr-title">{{ $product->name }} - تجربة {{ $city->name }}</h1>

        <div class="product-image">
            <img src="{{ $product->image }}" alt="{{ $product->name }}">
        </div>

        <div class="city-info">
            <h5>عن {{ $city->name }}</h5>
            <p>{{ $city->description }}</p>
        </div>

        @foreach($city->landmarks as $landmark)
            <div class="landmark">
                <h5>{{ $landmark->name }}</h5>

                @if($landmark->media->where('type','image')->isNotEmpty())
                    <div class="grid">
                        @foreach($landmark->media->where('type','image') as $mediaItem)
                            <img src="{{ $mediaItem->url }}" alt="{{ $landmark->name }}">
                        @endforeach
                    </div>
                @endif

                @foreach($landmark->media as $mediaItem)
                    @if($mediaItem->type === 'video')
                        <div class="video-container">
                            <video controls>
                                <source src="{{ $mediaItem->url }}" type="video/mp4">
                            </video>
                        </div>
                    @elseif($mediaItem->type === 'audio')
                        <div class="audio-container">
                            <audio controls>
                                <source src="{{ $mediaItem->url }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif
                @endforeach

                @foreach($landmark->artifacts as $artifact)
                    <div class="artifact">
                        <h6>{{ $artifact->title }}</h6>
                        <p>{{ $artifact->short_description }}</p>

                        @if($artifact->media->where('type','image')->isNotEmpty())
                            <div class="grid">
                                @foreach($artifact->media->where('type','image') as $mediaItem)
                                    <img src="{{ $mediaItem->url }}" alt="{{ $artifact->title }}">
                                @endforeach
                            </div>
                        @endif

                        @foreach($artifact->media as $mediaItem)
                            @if($mediaItem->type === 'video')
                                <div class="video-container">
                                    <video controls>
                                        <source src="{{ $mediaItem->url }}" type="video/mp4">
                                    </video>
                                </div>
                            @elseif($mediaItem->type === 'audio')
                                <div class="audio-container">
                                    <audio controls>
                                        <source src="{{ $mediaItem->url }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
@endsection
