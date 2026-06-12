<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>NiKCCIMA Blog</title>
        <link>{{ route('blog.index') }}</link>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />
        <description>Latest news, insights and announcements from NiKCCIMA and the Nigeria-Kenya AfCFTA trade corridor.</description>
        <language>en</language>
        @if($articles->isNotEmpty())
            <lastBuildDate>{{ $articles->first()->published_at?->toRssString() }}</lastBuildDate>
        @endif
        @foreach($articles as $article)
            <item>
                <title>{{ $article->title }}</title>
                <link>{{ route('blog.show', $article->slug) }}</link>
                <guid isPermaLink="true">{{ route('blog.show', $article->slug) }}</guid>
                <pubDate>{{ $article->published_at?->toRssString() }}</pubDate>
                @if($article->category)
                    <category>{{ $article->category->name }}</category>
                @endif
                <description><![CDATA[{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 300) }}]]></description>
            </item>
        @endforeach
    </channel>
</rss>
