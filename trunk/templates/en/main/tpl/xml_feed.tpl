<?xml version="1.0" encoding="iso-8859-2"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">

<channel>
    <!-- IFDEF: NEWS_FEED -->
    <title>./dev-log - RSS feed</title>
    <!-- ELSE -->
    <title>./dev-log - RSS comments</title>
    <!-- ENDIF -->
    <link>{MAINSITE_LINK}</link>
    <description>./dev-log | about work</description>
    <language>pl</language>
    <copyright>Copyright (c) 2005 Core | Łukasz Skowroń, Marcin Sztolcman</copyright>
	
    <!-- IFDEF: DISPLAY_XML -->
    <!-- BEGIN DYNAMIC BLOCK: xml_row -->
    <item>
        <pubDate>{DATE} GMT</pubDate>
        <title>{TITLE}</title>
        <dc:creator>{AUTHOR}</dc:creator>
        <link>http://{PERMALINK}</link>
        <description>{TEXT}</description>
        <!-- IFDEF: NEWS_FEED -->
        <category>
        <!-- BEGIN DYNAMIC BLOCK: cat_row -->
        {CATEGORY_NAME}{COMMA}
        <!-- END DYNAMIC BLOCK: cat_row -->
        </category>
        <comments>http://{COMMENTS_LINK}</comments>
        <!-- ELSE -->
        <!-- ENDIF -->
    </item>
    <!-- END DYNAMIC BLOCK: xml_row -->
    <!-- ELSE -->
    <!-- ENDIF -->
	
</channel>
</rss>