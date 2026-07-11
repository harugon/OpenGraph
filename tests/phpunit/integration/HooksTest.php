<?php

namespace MediaWiki\Extension\OpenGraph\Tests;

use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OpenGraph\Hooks;
use MediaWiki\Output\OutputPage;
use MediaWiki\Title\Title;

/**
 * @group Database
 * @covers \MediaWiki\Extension\OpenGraph\Hooks
 */
class HooksTest extends \MediaWikiIntegrationTestCase {

	public function testOnBeforePageDisplay() {
		$services = $this->getServiceContainer();
		$config = $services->getMainConfig();
		$hooks = new Hooks( $config );

		$context = new RequestContext();

		// 1.46互換のTitleオブジェクト作成
		$title = Title::newFromText( 'Test Page' );
		$context->setTitle( $title );

		$out = new OutputPage( $context );
		$out->setTitle( $title );

		$skinFactory = $services->getSkinFactory();
		$skin = $skinFactory->makeSkin( 'vector' );

		// フックを実行し、例外が発生しないことを検証する
		$hooks->onBeforePageDisplay( $out, $skin );

		$this->assertTrue( true, 'Hook executed without throwing exceptions' );
	}
}
