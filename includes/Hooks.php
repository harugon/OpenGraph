<?php

namespace MediaWiki\Extension\OpenGraph;

use Action;
use ApiMain;
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
     * @return bool
     * @throws MWException
     */
	public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {

        $conf = MediaWikiServices::getInstance()->getMainConfig();
        $Sitename = $conf->get( 'Sitename' );
        $TwitterSite = $conf->get( 'OpenGraphTwitterSite' ); //Twitterアカウント
        $FbAppId = $conf->get( 'OpenGraphFbAppId' ); //fb:app_id
        $OnTw = $conf->get( 'OpenGraphTw' ); //
        $OnFb = $conf->get( 'OpenGraphFb' ); //
        $Namespaces = $conf->get( 'OpenGraphNamespaces' ); //


        //view以外表示させない
        $action = Action::getActionName($out->getContext());
        if ($action !== 'view'){
            return true;
        }

        if(!$out->getTitle()->inNamespaces($Namespaces)){
            return true;
        }

		$site_name = $Sitename;
		$page = $out->getTitle();
		$url = $page->getFullURL();
		$title = $page->getText();
        $page_id  = $page->getArticleID();
        $description = self::getPageExtracts($page_id);

        if($OnFb){
            //Add OpenGraph
            $ogp = [
                'og:site_name' =>$site_name,
                'og:title'=>$title,
                'og:type'=>'article',
                'og:url'=>$url,
                'og:description'=>$description,
            ];

            if ($FbAppId !== ''){
                $ogp['fb:app_id'] = $FbAppId;
            }

            foreach ($ogp as $property => $value) {
                $out->addHeadItem($property,Html::element( 'meta', ['property' => $property, 'content' => $value ] ));
            }
        }

        if($OnTw){
            //Add Twitter
            $twitter = [
                'twitter:card'=>'summary',//“summary”、“summary_large_image”
            ];
            if(!$OnFb){
                $twitter+= [
                    'twitter:title'=>$title,
                    'twitter:description'=>$description,
                ];
            }

            if($TwitterSite !== ''){
                //カードフッターで使用されるウェブサイトの@ユーザー名。
                $twitter['twitter:site'] = $TwitterSite;
            }
            foreach ($twitter as $property => $value) {
                $out->addMeta($property,$value);
            }
        }
        return true;
	}

    /**
     * ページのコンテンツを抽出
     *
     * @url https://www.mediawiki.org/wiki/Extension:TextExtracts/ja
     * @param int $page_id
     * @return string
     * @throws MWException
     */
	private static function getPageExtracts(int $page_id):string{
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
        return $data[$page_id]['extract']['*'] ?? '';
    }

}