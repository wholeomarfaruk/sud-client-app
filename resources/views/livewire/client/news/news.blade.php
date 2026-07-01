<div class="screen screen--with-nav">
    <x-client.screen-header title="News & Blogs" />

    <div class="news-list">
        <a href="#" class="news-featured">
            <div class="news-featured__banner">
                <span class="badge badge--solid-accent">FEATURED</span>
            </div>
            <div class="news-featured__body">
                <div class="news-featured__title">{{ $featured['title'] }}</div>
                <div class="news-featured__meta">{{ $featured['meta'] }}</div>
            </div>
        </a>

        @foreach ($articles as $article)
            <a href="#" class="news-row">
                <span class="news-row__thumb news-row__thumb--{{ $article['thumb'] }}"></span>
                <div>
                    <div class="news-row__title">{{ $article['title'] }}</div>
                    <div class="news-row__meta">{{ $article['meta'] }}</div>
                </div>
            </a>
        @endforeach
    </div>

    <x-client.bottom-nav active="news" />
</div>
