# OpenGraph
![MediawikiにOpenGraph](https://github.com/harugon/OpenGraph/blob/main/.gituhb/screenshot/opengraph.png?raw=true "OpenGraph")

MediawikiにOpenGraph,twitterカードを追加します

## 依存する拡張
* [Extension:TextExtracts\-MediaWiki](https://www.mediawiki.org/wiki/Extension:TextExtracts/ja)　
* [Extension:PageImages \- MediaWiki](https://www.mediawiki.org/wiki/Extension:PageImages) 

の拡張に依存しています

## インストール

Composer でインストールします [composer.local.json](https://www.mediawiki.org/wiki/Composer#Using_composer-merge-plugin)
```bash
COMPOSER=composer.local.json composer require harugon/open-graph
```

LocalSettings.php に下記を追記
```php
wfLoadExtension( 'OpenGraph' );
```

## 設定

```php
 $wgOpenGraphTwitterSite = "@wiki";//Twitterアカウント
```

## link
* [The Open Graph protocol](https://ogp.me/)
* [カードの利用開始 \| Docs \| Twitter Developer](https://developer.twitter.com/ja/docs/tweets/optimize-with-cards/guides/getting-started)
