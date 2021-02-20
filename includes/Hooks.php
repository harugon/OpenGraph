<?php

namespace OpenGraph;

use Action;
use ApiMain;
use ApiResult;
use FauxRequest;
use Html;
use MediaWiki\MediaWikiServices;
use MWException;
use OutputPage;
use Skin;

class Hooks {

	/**
	 * onBeforePageDisplay
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @throws MWException
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
        global $wgSitename;

        $config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'opengraph' );
        $image = $config->get( 'OpenGraphFallbackImage' );//フォールバック画像
        $TwitterSite = $config->get( 'OpenGraphTwitterSite' ); //Twitterアカウント


        //view以外表示させない
        $action = Action::getActionName($out->getContext());
        if (!($action === 'view')){
            return true;
        }

        //標準名前空間以外表示させない
        $namespace = $out->getTitle()->getNamespace();
        if(!($namespace === 0)){
            return true;
        }

		$site_name = $wgSitename;
		$page = $out->getTitle();
		$url = $page->getFullURL();
		$title = $page->getText();
        $page_id  = $page->getArticleID();
        $description = self::getPageExtracts($page_id);


        //https://developer.twitter.com/ja/docs/tweets/optimize-with-cards/guides/getting-started
		$ogp=[
		    "og:site_name" =>$site_name,
            "og:title"=>$title,
            "og:type"=>"article",
            "og:url"=>$url,
            "og:description"=>$description,
            //"og:locale"=>"ja_JP",
        ];
		$twitter = [
            "twitter:card"=>"summary",//“summary”、“summary_large_image”
            "twitter:site"=>$TwitterSite,//カードフッターで使用されるウェブサイトの@ユーザー名。
        ];

		//PageImageでセットされてるか
        if(!$out->hasHeadItem('og:image')){
            if(!($image === "")){
                $ogp['og:image']= $image;
            }
        }

        //OpenGraph
        foreach ($ogp as $property => $value) {
            $out->addHeadItem($property,Html::element( 'meta', ['property' => $property, 'content' => $value ] ));
        }
        //Twitter
        foreach ($twitter as $property => $value) {
            $out->addMeta($property,$value);
        }
        return true;
	}


	private static function getPageExtracts(int $page_id){
        $request = [
            'action' => 'query',
            'prop' => 'extracts',
            'exintro' => 'true',
            'exsentences' => '2',
            'explaintext' => 'true',
            'pageids' =>$page_id,
        ];
        //API
        $api = new ApiMain( new FauxRequest( $request ) );
        //API実行
        $api->execute();
        $data = $api->getResult()->getResultData(['query','pages']);
        return $data[$page_id]['extract']['*']??"";
    }



}
