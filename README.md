# OpenGraph
![MediawikiにOpenGraph](https://github.com/harugon/OpenGraph/blob/main/.gituhb/screenshot/opengraph.png?raw=true "OpenGraph")

MediawikiにOpenGraph,twitterカードを追加します

## 依存する拡張
* [Extension:TextExtracts\-MediaWiki](https://www.mediawiki.org/wiki/Extension:TextExtracts/ja)　
* [Extension:PageImages \- MediaWiki](https://www.mediawiki.org/wiki/Extension:PageImages) 

の拡張に依存しています

## インストール

```php
wfLoadExtension( 'OpenGraph' );
```

## 設定

```php
 $OpenGraphFallbackImage = "";//フォールバック画像
 $OpenGraphTwitterSite = "@wiki";//Twitterアカウント
```

## link
* [The Open Graph protocol](https://ogp.me/)
* [カードの利用開始 \| Docs \| Twitter Developer](https://developer.twitter.com/ja/docs/tweets/optimize-with-cards/guides/getting-started)
