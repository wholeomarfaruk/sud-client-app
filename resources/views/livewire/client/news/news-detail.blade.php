<x-client.content-detail
    :title="$article['title']"
    :date="$article['date']"
    :image="$article['image']"
    :back="route('client.news')"
>
    {!! $article['body'] !!}
</x-client.content-detail>
