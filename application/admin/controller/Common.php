<?php
namespace app\admin\controller;

use think\Session;
use think\Controller;

class Common extends Controller
{
	public $extend = null;

	public function _initialize()
	{
		// register common class
		$this->extend = [
			'http' => new \utils\Http(),
			'password' => new \account\Password(),
		];

		//login
		$controller = strtolower(request()->controller());
		$action = strtolower(request()->action());
	    if ($controller != 'account' && $action != 'login') {
	    	$this->checkLogin();
	    }
	}

	protected function checkLogin()
	{
	    //验证是否登录成功
	    if (!Session::has('login_id')) {
	        $this->redirect('account/login', ['redirect' => (isAjax() || isPost()) ? null : url()]);
	    }

	    //登录是否过期 无操作1h即为过期
	    $login_time = Session::get('member_logtime');
	    if (time() - $login_time > 3600) {
	        Session::clear();
	        $this->redirect('account/login');
	    }

	    Session::set('member_logtime', time());
	}
}