@extends('website.layout.main')

@section('title', $product->city->name . ' - تجربة المنتج')

@section('content')
<style>
    :root {
        --main-gradient: linear-gradient(135deg, #FFD700 10%, #000 100%);
        --main-gold: #FFD700;
        --main-black: #23242a;
        --main-gray: #e9e9e9;
        --radius-xl: 1.2rem;
        --radius-lg: .9rem;
        --radius-md: .7rem;
        --radius-sm: .5rem;
        --shadow-soft: 0 2px 20px rgba(0,0,0,0.10);
        --shadow-card: 0 3px 18px rgba(51,51,51, 0.08), 0 1.5px 7px rgba(0,0,0,0.04);
        --transition: all .24s cubic-bezier(.6,.3,.6,1);
        --font-main: "Cairo", Tahoma, Arial, sans-serif;
    }
    body, section, .container {
        font-family: var(--font-main);
        background: #fafbfc;
    }
    /* --- Product Hero --- */
    .qr-hero-min {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: var(--main-gradient);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-card);
        padding: 1.5rem 2rem;
        margin-bottom: 2.8rem;
        max-width: 450px;
        margin-right: auto;
        margin-left: 0;
        position: relative;
        overflow: hidden;
    }
    .qr-hero-min::before {
        content:"";
        position:absolute;
        z-index:0;
        left:0;top:0;right:0;bottom:0;
        opacity:.08;
        background:url('/images/decor-abstract.svg') center/cover no-repeat;
        pointer-events:none;
    }
    .qr-mini-imgbox {
        background: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        padding: .45rem;
        display: flex;
        align-items: center;
        justify-content:center;
        width:64px;
        height:64px;
        min-width:64px;
        min-height:64px;
        z-index:1;
    }
    .qr-mini-imgbox img {
        width: 54px;
        height: 54px;
        border-radius: var(--radius-md);
        object-fit: cover;
        border: 2.5px solid #f6f6f6;
        background: #fff;
        transition: var(--transition);
    }
    .qr-mini-qr {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        margin-left: 0.7rem;
        z-index:1;
    }
    .qr-mini-qr img {
        width: 54px !important;
        height: 54px !important;
        object-fit: contain;
        border-radius: var(--radius-md);
        background: #f5f5f7;
        box-shadow: 0 2px 12px #0002;
        border:2px solid #eee;
        padding:3px;
    }
    .qr-mini-product-name {
        font-size: 1.21rem;
        font-weight: 800;
        color: var(--main-black);
        margin-bottom: 0.22rem;
        letter-spacing: 0.2px;
        margin-top:2px;
    }
    .qr-mini-product-price {
        font-size: 1.05rem;
        color: #5a5a5a;
        font-weight: 600;
        background:rgba(255,255,255,0.6);
        border-radius:var(--radius-md);
        padding:3px 9px;
        display:inline-block;
        margin-top:.14rem;
    }
    .qr-mini-product-price span {
        color:#e03440;
        font-weight:700;
        font-size:1rem;
        margin-right:.15em;
    }
    .qr-scan-label{
        font-size:.93rem;
        color: var(--main-black);
        text-align:center;
        font-weight:600;
        background:rgba(255,255,255,0.75);
        border-radius:var(--radius-sm);
        padding: 2px 7px 1px 7px;
        margin-top:-3px;
    }
    /* --- City Panel --- */
    .city-panel {
        background: #fff;
        border-radius:var(--radius-xl);
        box-shadow: var(--shadow-card);
        padding:1.4rem 2rem 1.25rem 2rem;
        margin-bottom:2.6rem;
        max-width: 700px;
        margin-right:auto;
        margin-left:0;
    }
    .city-panel h4 {
        margin:0 0 8px 0;
        font-size:1.52rem;
        color: var(--main-black);
        font-weight:900;
        letter-spacing:.3px;
    }
    .city-region {
        color:#888;
        font-size:.99rem;
        margin-bottom: 7px;
        font-weight:600;
    }
    .city-desc {
        font-size:1.1rem;
        color:#191a23;
        font-weight:500;
        line-height:1.9;
    }
    /* --- Landmarks --- */
    .landmarks-main-title {
        font-size: 1.35rem;
        font-weight: bold;
        color:var(--main-black);
        margin:1.5rem 0 1.1rem 0;
        border-right:5px solid var(--main-gold);
        padding-right:10px;
        background:rgba(255,215,0,0.12);
        border-radius:var(--radius-md);
        display:inline-block
    }
    .landmark-card {
        background: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        display: flex;
        flex-direction: row;
        align-items:flex-start;
        margin-bottom: 2.5rem;
        padding:1.05rem 1.3rem 1rem 1.3rem;
        gap: 2rem;
        transition: box-shadow .19s;
        max-width:900px;
        position:relative;
    }
    .landmark-card:hover{
        box-shadow: 0 5px 26px #ddb81c22, 0 2px 8px #16110b12;
    }
    .landmark-imgbox {
        flex: 0 0 200px;
        max-width: 200px;
        position:relative;
        display:flex;
        flex-wrap:wrap;
        gap:.5rem;
        min-height: 120px;
    }
    .landmark-imgbox img {
        width: 97px;
        height: 97px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border:2px solid #f5efe0;
        box-shadow:0 2px 11px #0001;
        background: #f8f8f8;
        margin-bottom:7px;
        transition:var(--transition);
    }
    .landmark-content {
        flex:1 1 auto;
        min-width: 220px;
        display: flex;
        flex-direction: column;
        gap: .40rem;
    }
    .landmark-title {
        color:var(--main-gold);
        font-size:1.27rem;
        font-weight: 700;
        margin-bottom: 7px;
        letter-spacing:.1px;
    }
    .landmark-type {
        background:#f8fafc;
        border-radius:var(--radius-sm);
        color:#777;
        padding:1px 12px;
        display:max-content;
        font-size:.98rem;
        margin-bottom:6px;
        font-weight:500;
    }
    .landmark-short {
        font-size:1.02rem;
        color: #4d4d53;
        margin-bottom:3px;
    }
    .landmark-description {
        font-size:1.09rem;
        color: #171921;
        line-height:1.7;
        margin-bottom: 8px;
        font-weight:400;
    }
    /* --- Media --- */
    .video-container, .audio-container {
        margin-top: .65rem;
        margin-bottom: .60rem;
        border-radius: var(--radius-sm);
        overflow: hidden;
        background: #f2f2f2;
        box-shadow:0 1px 7px #0001;
        padding:6px 3px 4px 3px;
        max-width:370px;
    }
    .video-container video {
        width: 100%;
        max-height: 250px;
        border-radius: inherit;
        background: #000;
    }
    .audio-container audio {
        width: 100%;
        outline:none;
    }
    /* --- Artifacts --- */
    .artifacts-list {
        list-style:none;
        margin:0;
        margin-top:1.5rem;
        padding:0;
        display:flex;
        flex-direction:row;
        flex-wrap:wrap;
        gap:1.6rem;
        border-top:1px dashed #ece3b6;
        padding-top:1.3rem;
    }
    .artifacts-list li {
        flex:1 1 265px;
        min-width:250px;
        max-width:350px;
        min-height:200px;
    }
    .artifact-card {
        background: #f9fafc;
        border-radius: var(--radius-md);
        box-shadow:0 2px 9px #e5b9001b;
        display: flex;
        flex-direction: row;
        gap:1.15rem;
        align-items:flex-start;
        padding: .7rem .6rem;
        transition:box-shadow .18s;
        height:100%;
        min-height:168px;
        position:relative;
    }
    .artifact-card:hover{
        box-shadow:0 8px 22px #ffd70033;
    }
    .artifact-imgbox {
        flex: 0 0 74px;
        max-width:75px;
        display: flex;
        flex-direction: column;
        gap: .27rem;
        align-items:center;
    }
    .artifact-imgbox img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius:var(--radius-sm);
        border:1.6px solid #ececec;
        background:#fff;
        margin-bottom: 0;
        transition: var(--transition);
    }
    .artifact-content {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        gap: .21rem;
        padding-top: 2px;
    }
    .artifact-title {
        color: var(--main-gold);
        font-size: 1.08rem;
        font-weight: bold;
        line-height: 1.4;
        margin-bottom: 4px;
    }
    .artifact-short {
        font-size: .99rem;
        color:#555;
        margin-bottom: 2px;
    }
    .artifact-desc {
        font-size: 1.01rem;
        color: #1b1b1b;
        line-height:1.5;
        margin-bottom:3px;
    }
    /* --- Misc Responsive --- */
    @media(max-width: 900px){
        .landmark-card {flex-direction:column;gap:1.06rem;max-width:99vw;}
        .landmark-imgbox {flex-direction:row;gap:.4rem;max-width: 99vw;}
    }
    @media (max-width: 650px) {
        .container {padding:0 .4rem;}
        .qr-hero-min{padding: .7rem .8rem; gap:1.15rem;max-width:99vw;}
        .city-panel{padding:1rem .9rem 1rem .6rem;}
        .landmark-card{padding:.8rem .5rem;}
        .artifacts-list{gap:.7rem; padding-top:.7rem;}
        .artifact-imgbox{max-width:56px;}
        .artifact-imgbox img{width:44px;height:44px;}
    }
    @media (max-width:400px){
        .city-panel h4{font-size:1rem}
        .qr-mini-product-name{font-size:.97rem;}
        .landmark-card{gap:.3rem}
    }
</style>

<section class="container mx-auto px-1 py-8" dir="rtl">
    {{-- --- Product Presentation & QR --- --}}
    <div class="qr-hero-min mt-4 mb-4">
        <div class="qr-mini-imgbox" title="{{ $product->name }}">
            <img src="{{ $product->image }}" alt="{{ $product->name }}">
        </div>
        <div class="qr-mini-qr">
            <img src="{{ $product->qr_code ? asset('storage/'.$product->qr_code) : asset('/images/default-qr.svg') }}"
                 alt="QR - {{ $product->name }}">
            <div class="qr-scan-label">
                امسح QR
                <svg width="18" height="18" style="vertical-align:middle;margin-right:3px;opacity:.7;" fill="none" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="6" height="6" rx="1.2" stroke="#FFD700" stroke-width="1.5"/>
                    <rect x="15" y="3" width="6" height="6" rx="1.2" stroke="#FFD700" stroke-width="1.5"/>
                    <rect x="3" y="15" width="6" height="6" rx="1.2" stroke="#FFD700" stroke-width="1.5"/>
                    <rect x="19" y="15" width="2" height="6" rx="1.2" fill="#FFD700" fill-opacity="0.2"/>
                    <rect x="15" y="15" width="6" height="6" rx="1.2" stroke="#FFD700" stroke-width="1.5"/>
                </svg>
            </div>
        </div>
        <div>
            <div class="qr-mini-product-name">{{ $product->name }}</div>
            <div class="qr-mini-product-price">
                {{ number_format($product->price,2) }}₪
                @if($product->discount > 0)
                  <span>خصم {{ $product->discount }}%</span>
                @endif
            </div>
        </div>
    </div>

    {{-- --- City Section --- --}}
    <div class="city-panel">
        <h4>
            <svg width="19" height="19" style="vertical-align:-5px;margin-left:7px;" fill="none" viewBox="0 0 24 24">
                <path d="M2 20V10.5A8.5 8.5 0 0 1 19 6" stroke="#BA9600" stroke-width="1.6"/>
                <rect x="6" y="15" width="12" height="6" rx="2" stroke="#FFD700" stroke-width="1.7" fill="#FFFDE8"/>
            </svg>
            {{ $city->name }}
        </h4>
        <div class="city-region">{{ $city->region }} - {{ $city->subregion }}</div>
        <div class="city-desc">{{ $city->description }}</div>
    </div>

    {{-- --- Landmarks Section --- --}}
    <div class="landmarks-main-title">معالم المدينة التاريخية</div>
    @foreach($city->landmarks as $landmark)
        <div class="landmark-card">
            <div class="landmark-imgbox">
                <img src="{{ $landmark->image ?? asset('images/landmark-default.jpg') }}" alt="{{ $landmark->name }}">
                @foreach($landmark->media->where('type','image') as $mediaItem)
                    <img src="{{ $mediaItem->url }}" alt="{{ $landmark->name }}">
                @endforeach
            </div>
            <div class="landmark-content">
                <div class="landmark-title">
                    <svg width="22" height="22" style="vertical-align:-5px;margin-left:2px;" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="#FFD700" stroke-width="1.5" fill="#FFFDE8"/>
                    </svg>
                    {{ $landmark->name }}
                </div>
                <div class="landmark-type">نوع المعلم: {{ $landmark->type }}</div>
                <div class="landmark-short">{{ $landmark->short_description }}</div>
                <div class="landmark-description">{{ $landmark->description }}</div>

                {{-- Media --}}
                @foreach($landmark->media as $mediaItem)
                    @if($mediaItem->type === 'video')
                        <div class="video-container">
                            <video controls poster="{{ $landmark->image }}">
                                <source src="{{ $mediaItem->url }}" type="video/mp4">
                                متصفحك لا يدعم عرض الفيديو.
                            </video>
                        </div>
                    @elseif($mediaItem->type === 'audio')
                        <div class="audio-container">
                            <audio controls>
                                <source src="{{ $mediaItem->url }}" type="audio/mpeg">
                                متصفحك لا يدعم تشغيل الصوت.
                            </audio>
                        </div>
                    @endif
                @endforeach

                {{-- Artifacts --}}
                <ul class="artifacts-list">
                @foreach($landmark->artifacts as $artifact)
                    <li>
                        <div class="artifact-card">
                            <div class="artifact-imgbox">
                                <img src="{{ $artifact->image ?? asset('images/artifact-default.jpg') }}" alt="{{ $artifact->title }}">
                                @foreach($artifact->media->where('type','image') as $mediaItem)
                                    <img src="{{ $mediaItem->url }}" alt="{{ $artifact->title }}">
                                @endforeach
                            </div>
                            <div class="artifact-content">
                                <div class="artifact-title">{{ $artifact->title }}</div>
                                <div class="artifact-short">{{ $artifact->short_description }}</div>
                                <div class="artifact-desc">{{ $artifact->description }}</div>
                                @foreach($artifact->media as $mediaItem)
                                    @if($mediaItem->type === 'video')
                                        <div class="video-container">
                                            <video controls poster="{{ $artifact->image }}">
                                                <source src="{{ $mediaItem->url }}" type="video/mp4">
                                                متصفحك لا يدعم عرض الفيديو.
                                            </video>
                                        </div>
                                    @elseif($mediaItem->type === 'audio')
                                        <div class="audio-container">
                                            <audio controls>
                                                <source src="{{ $mediaItem->url }}" type="audio/mpeg">
                                                متصفحك لا يدعم تشغيل الصوت.
                                            </audio>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</section>
@endsection