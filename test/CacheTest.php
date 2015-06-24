<?php
include(__DIR__ . '/local.php');
class CacheTest extends PHPUnit_Framework_TestCase {
	public function test() {
		global $SETTINGS;
		$config = $SETTINGS;
		$cache = Yii::createObject($config);
		$cache->set('THISISONLYATEST', '----', 10);
		$this->assertEquals('----', $cache->get('THISISONLYATEST'));
		$this->assertEquals(EXPECTED_NODE, count($cache->getServers()));
	}
}
