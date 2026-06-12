<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>NiKCCIMA News</title>
        <link>{{ route('news.index') }}</link>
        <atom:link href="{{ route('news.feed') }}" rel="self" type="application/rss+xml" />
        <description>Latest news, press releases and announcements from NiKCCIMA and the Nigeria-Kenya AfCFTA trade corridor.</description>
        <language>en</language>
        <lastBuildDate>{{ ($articles->first()?->published_at ?? now())->toRssString() }}</lastBuildDate>
        @if($articles->isEmpty())
            {{-- No published articles yet — still a valid, empty RSS channel. --}}
        @endif
        @foreach($articles as $article)
            <item>
                <title>{{ $article->title }}</title>
                <link>{{ route('news.show', $article->slug) }}</link>
                <guid isPermaLink="true">{{ route('news.show', $article->slug) }}</guid>
                <pubDate>{{ $article->published_at?->toRssString() }}</pubDate>
                @if($article->category)
                    <category>{{ $article->category->name }}</category>
                @endif
                <description><![CDATA[{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 300) }}]]></description>
            </item>
        @endforeach
    </channel>
</rss>
