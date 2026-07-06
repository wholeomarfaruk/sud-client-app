<div class="screen screen--with-nav">
    <x-client.screen-header title="News & Blogs" />

    <div class="news-list">
        @if ($loadError)
            <div class="unit-empty-state">{{ $loadError }}</div>
        @elseif (! $featured && count($articles) === 0)
            <div class="unit-empty-state">No articles published yet.</div>
        @else
            @if ($featured)
                <a href="{{ route('client.news-detail', $featured['id']) }}" class="news-featured">
                    <div class="news-featured__banner" @if($featured['image']) style="background-image:url('{{ $featured['image'] }}');background-size:cover;background-position:center" @endif>
                        <span class="badge badge--solid-accent">FEATURED</span>
                    </div>
                    <div class="news-featured__body">
                        <div class="news-featured__title">{{ $featured['title'] }}</div>
                        <div class="news-featured__meta">{{ $featured['date'] }} · {{ $featured['read_time'] }}</div>
                    </div>
                </a>
            @endif

            @foreach ($articles as $i => $article)
                <a href="{{ route('client.news-detail', $article['id']) }}" class="news-row">
                    @if ($article['image'])
                        <span class="news-row__thumb" style="background-image:url('{{ $article['image'] }}');background-size:cover;background-position:center"></span>
                    @else
                        <span class="news-row__thumb news-row__thumb--{{ $i % 2 === 0 ? 'gold' : 'green' }}"></span>
                    @endif
                    <div>
                        <div class="news-row__title">{{ $article['title'] }}</div>
                        <div class="news-row__meta">{{ $article['date'] }} · {{ $article['read_time'] }}</div>
                    </div>
                </a>
            @endforeach

            @if ($hasMore)
                <button type="button" class="btn btn--outline" wire:click="loadMore" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="loadMore">Load more</span>
                    <span wire:loading wire:target="loadMore">Loading&hellip;</span>
                </button>
            @endif
        @endif
    </div>

    <x-client.bottom-nav active="news" />
</div>
