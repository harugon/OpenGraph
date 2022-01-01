# OpenGraph
![MediawikiにOpenGraph](https://github.com/harugon/OpenGraph/blob/main/.gituhb/screenshot/opengraph.png?raw=true "OpenGraph")

MediawikiにOpenGraph,Twitter　Cardを追加します

## 依存する拡張
* [Extension:TextExtracts](https://www.mediawiki.org/wiki/Extension:TextExtracts/ja)　直接依存していませんが PageImagesが``og:image``,``twitter:image``を追加しています
* [Extension:PageImages](https://www.mediawiki.org/wiki/Extension:PageImages) TextExtractsを使用しmeta descriptionを追加します。





## インストール

Composer でインストールします [composer.local.json](https://www.mediawiki.org/wiki/Composer#Using_composer-merge-plugin)
```bash
COMPOSER=composer.local.json composer require harugon/open-graph
```

LocalSettings.php に下記を追記
```php
wfLoadExtension( 'OpenGraph' );
$wgOpenGraphTwitterSite = "";//Twitter
$wgOpenGraphFbAppId = "";//FbAppID
```

## 設定

| 変数                    | デフォルト | 説明 |
|-------------------------|------|------|
| $wgOpenGraphTwitterSite | ""   |      |
| $wgOpenGraphFbAppId     | ""   |      |
| $wgOpenGraphFb          | true |OpenGraphを追加する|
| $wgOpenGraphTw          | true |TwitterCardを追加する|
| $wgOpenGraphNamespaces   | [0]  |追加する名前空間 |


###　メモ
* PageImageは標準名前空間にしか画像追加しない
* $wgPageImagesOpenGraphFallbackImage

## link
* [The Open Graph protocol](https://ogp.me/)
* [カードの利用開始 \| Docs \| Twitter Developer](https://developer.twitter.com/ja/docs/tweets/optimize-with-cards/guides/getting-started)
