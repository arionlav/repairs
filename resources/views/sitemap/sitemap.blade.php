<?
echo "<?xml version='1.0' encoding='UTF-8'?>\n";
echo "<urlset xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'>\n";
?>
@foreach ($urlsAll as $url)
@foreach ($url as $u)
    <url>
        <loc>{{ $u['url'] }}</loc>
        <lastmod>{{ (isset($u['lastMod'])) ? $u['lastMod'] : '2015-01-01T00:00:00+03:00' }}</lastmod>
        <changefreq>{{ $u['change'] }}</changefreq>
        <priority>{{ $u['priority'] }}</priority>
    </url>
@endforeach
@endforeach
<?='</urlset>' ?>