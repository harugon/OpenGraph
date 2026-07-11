<?php

namespace MediaWiki\Extension\OpenGraph;

use MediaWiki\Api\ApiMain;
use MediaWiki\Config\Config;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\FauxRequest;
use Skin;

class Hooks implements BeforePageDisplayHook {

	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * onBeforePageDisplay
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return void
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		$Sitename = $this->config->get( 'Sitename' );
		// Twitterアカウント
		$TwitterSite = $this->config->get( 'OpenGraphTwitterSite' );
		// fb:app_id
		$FbAppId = $this->config->get( 'OpenGraphFbAppId' );
		$OnTw = $this->config->get( 'OpenGraphTw' );
		$OnFb = $this->config->get( 'OpenGraphFb' );
		$Namespaces = $this->config->get( 'OpenGraphNamespaces' );

		// view以外表示させない
		$action = $out->getContext()->getActionName();
		if ( $action !== 'view' ) {
			return;
		}

		if ( !$out->getTitle()->inNamespaces( $Namespaces ) ) {
			return;
		}

		$site_name = $Sitename;
		$page = $out->getTitle();
		$url = $page->getFullURL();
		$title = $page->getText();
		$page_id  = $page->getArticleID();
		$description = $this->getPageExtracts( $out->getContext(), $page_id );

		if ( $OnFb ) {
			// Add OpenGraph
			$ogp = [
				'og:site_name' => $site_name,
				'og:title' => $title,
				'og:type' => 'article',
				'og:url' => $url,
				'og:description' => $description,
			];

			if ( $FbAppId !== '' ) {
				$ogp['fb:app_id'] = $FbAppId;
			}

			foreach ( $ogp as $property => $value ) {
				$metaElement = Html::element( 'meta', [ 'property' => $property, 'content' => $value ] );
				$out->addHeadItem( $property, $metaElement );
			}
		}

		if ( $OnTw ) {
			// Add Twitter
			$twitter = [
				// “summary”、“summary_large_image”
				'twitter:card' => 'summary',
			];
			if ( !$OnFb ) {
				$twitter += [
					'twitter:title' => $title,
					'twitter:description' => $description,
				];
			}

			if ( $TwitterSite !== '' ) {
				// カードフッターで使用されるウェブサイトの@ユーザー名。
				$twitter['twitter:site'] = $TwitterSite;
			}
			foreach ( $twitter as $property => $value ) {
				$out->addMeta( $property, $value );
			}
		}
	}

	/**
	 * ページのコンテンツを抽出
	 *
	 * @see https://www.mediawiki.org/wiki/Extension:TextExtracts/ja
	 * @param IContextSource $context
	 * @param int $page_id
	 * @return string
	 */
	private function getPageExtracts( IContextSource $context, int $page_id ): string {
		$request = [
			'action' => 'query',
			'prop' => 'extracts',
			'exintro' => 'true',
			'exsentences' => '2',
			'explaintext' => 'true',
			'pageids' => $page_id,
		];

		// API用コンテキスト作成
		$apiContext = new DerivativeContext( $context );
		$apiContext->setRequest( new FauxRequest( $request ) );

		// API
		$api = new ApiMain( $apiContext );
		// API実行
		$api->execute();
		$data = $api->getResult()->getResultData( [ 'query', 'pages' ] );
		return $data[$page_id]['extract']['*'] ?? '';
	}

}
