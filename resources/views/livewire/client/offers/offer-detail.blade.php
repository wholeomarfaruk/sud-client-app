<x-client.content-detail
    :title="$offer['title']"
    :date="$offer['date']"
    :image="$offer['image']"
    :back="route('client.offers')"
    :badge="$offer['is_featured'] ? 'FEATURED' : null"
>
    {!! $offer['body'] !!}
</x-client.content-detail>
