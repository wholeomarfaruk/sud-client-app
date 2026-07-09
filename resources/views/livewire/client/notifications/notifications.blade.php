<div class="screen screen--with-nav">
    <x-client.screen-header title="Notifications" :bell="false">
        <x-slot:trailing>
            <button type="button" class="screen-header__action" wire:click="markAllRead" wire:loading.attr="disabled">Mark read</button>
        </x-slot:trailing>
    </x-client.screen-header>

    <div class="notifications-list">
        @forelse ($notifications as $n)
            <div class="list-row {{ $n['unread'] ? 'list-row--unread' : '' }}" wire:click="view({{ $n['id'] }})" style="cursor:pointer">
                <span class="list-row__icon {{ match($n['icon']) { 'due' => 'list-row__icon--accent', 'offer' => 'list-row__icon--gold', default => '' } }}">
                    @switch($n['icon'])
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
        @empty
            <div class="list-row">
                <div class="list-row__body">
                    <div class="list-row__desc">No notifications yet.</div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($selected)
        <div class="modal-scrim" wire:click="closeModal">
            <div class="modal-panel" wire:click.stop>
                <div class="modal-panel__header">
                    <span class="modal-panel__title">{{ $selected['title'] }}</span>
                    <button type="button" class="screen-header__icon-btn" aria-label="Close" wire:click="closeModal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="modal-panel__body">
                    <p>{{ $selected['body'] }}</p>
                    <div class="list-row__meta">{{ $selected['time'] }}</div>
                </div>
                @if ($selected['action_url'])
                    <a href="{{ $selected['action_url'] }}" class="btn btn--outline" style="margin-top:16px">View details</a>
                @endif
            </div>
        </div>
    @endif

    <x-client.bottom-nav active="menu" />
</div>
