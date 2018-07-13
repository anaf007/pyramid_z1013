<?php
class TestAction extends Action {
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
	}

	public function xxx(){

		$result = M('fck')->execute('update __TABLE__ set user_id="100000" where id=1');
		dump($result);

	}




}
?>