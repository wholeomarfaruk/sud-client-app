<div class="screen screen--with-nav">
    <x-client.screen-header title="Notifications" :bell="false">
        <x-slot:trailing>
            <button type="button" class="screen-header__action">Mark read</button>
        </x-slot:trailing>
    </x-client.screen-header>

    <div class="notifications-list">
        @foreach ($notifications as $n)
            <div class="list-row {{ $n['unread'] ? 'list-row--unread' : '' }}">
                <span class="list-row__icon {{ match($n['type']) { 'due' => 'list-row__icon--accent', 'offer' => 'list-row__icon--gold', default => '' } }}">
                    @switch($n['type'])
                        @case('due')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                            @break
                        @case('paid')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            @break
                        @case('offer')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 12v8H4v-8M2 7h20v5H2zM12 22V7"/></svg>
                            @break
                        @default
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 2v4M16 2v4M3 8h18v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    @endswitch
                </span>
                <div class="list-row__body">
                    <div class="list-row__title">{{ $n['title'] }}</div>
                    <div class="list-row__desc">{{ $n['body'] }}</div>
                    <div class="list-row__meta">{{ $n['time'] }}</div>
                </div>
                @if ($n['unread'])
                    <span class="list-row__unread-dot"></span>
                @endif
            </div>
        @endforeach
    </div>

    <x-client.bottom-nav active="menu" />
</div>
