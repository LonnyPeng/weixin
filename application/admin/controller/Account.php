<?php
namespace app\admin\controller;

use think\Session;

class Account extends Common
{
    public function login()
    {
    	$this->view->engine->layout(false);

    	if (isAjax()) {
    		$loginName = input('post.login');
    		$passwd = input('post.passwd');

    		$user = db('t_member')->where(['member_name' => $loginName])->find();

    		if (!$user) {
    			return [
    				'status' => 'error',
    				'msg' => '登录帐号无效',
    			];
    		}

    		if (!$this->extend['password']->validate($passwd, $user['member_password'])) {
    			return [
    				'status' => 'error',
    				'msg' => '密码错误',
    			];
    		}
    		if ($user['member_status'] < 1) {
    			return [
    				'status' => 'error',
    				'msg' => '对不起，您的帐户已被禁用',
    			];
    		}

            Session::set('login_id', intval($user['member_id']));
            Session::set('login_name', $loginName);
            Session::set('member_logtime', time());
    		
    		// record login time and ip
    		$data = [
    			'member_logtime' => ['exp', 'NOW()'],
    			'member_logip' => $this->extend['http']->getIp(),
    			'member_lognum' => ['exp', 'member_lognum + 1'],
    		];
    		db('t_member')->where('member_id', $user['member_id'])->update($data);
    		
    		// get redirect url
    		$redirect = null;
    		if (input('post.redirect')) {
    		    $redirect = input('post.redirect');
    		    $controller = strtolower(request()->controller());
    		    $action = strtolower(request()->action());
    		    if ($controller != 'account' && $action != 'login') {
    		        $redirect = null;
    		    }
    		}

    		if (!$redirect) {
    		    $redirect = url('admin/index/index');
    		}

    		return [
    			'status' => 'ok',
    			'msg' => '登录成功',
    			'redirect' => $redirect,
    		];
    	}

        return view('', ['type' => 'login']);
    }

    public function register()
    {
    	$this->view->engine->layout(false);

    	if (isAjax()) {
    		$loginName = input('post.login');
    		$passwd = input('post.passwd');

    		$memberId = db('t_member')->field('member_id')->where('member_name', $loginName)->find();
    		if ($memberId) {
    			return [
    				'status' => 'error',
    				'msg' => sprintf('%s 已注册', $loginName),
    			];
    		}
    		
    		// insert new member info
    		$data = [
    			'member_name' => $loginName,
    			'member_password' => $this->extend['password']->encrypt($passwd),
    			'member_logip' => $this->extend['http']->getIp(),
    		];
    		$status = db('t_member')->insert($data);
    		if ($status) {
                Session::set('login_id', db('user')->getLastInsID());
                Session::set('login_name', $loginName);
                Session::set('member_logtime', time());

    			return [
    				'status' => 'ok',
    				'msg' => '注册成功',
    				'redirect' => url('admin/index/index'),
    			];
    		} else {
    			return [
    				'status' => 'error',
    				'msg' => '注册失败',
    			];
    		}
    	}

    	return view('account/login', ['type' => 'register']);
    }

    public function logout()
    {
        Session::clear();
        $this->redirect('account/login');
    }

    public function forgotPassword()
    {
        $this->view->engine->layout(false);

        if (isAjax()) {
            $loginName = input('post.login');
            $passwd = input('post.passwd');

            if ($loginName == 'admin') {
                return [
                    'status' => 'error',
                    'msg' => '超级用户禁止修改',
                ];
            }

            $user = db('t_member')->where(['member_name' => $loginName])->find();

            if (!$user) {
                return [
                    'status' => 'error',
                    'msg' => '帐号无效',
                ];
            }

            if (!$this->extend['password']->validate($passwd, $user['member_password'])) {
                return [
                    'status' => 'error',
                    'msg' => '密码错误',
                ];
            }
            if ($user['member_status'] < 1) {
                return [
                    'status' => 'error',
                    'msg' => '对不起，您的帐户已被禁用',
                ];
            }

            // record login time and ip
            $data = [
                'admin_modified' => ['exp', 'NOW()'],
                'member_password' => $this->extend['password']->encrypt($passwd),
            ];
            $status = db('t_member')->where('member_id', $user['member_id'])->update($data);
            if ($status) {
                return [
                    'status' => 'ok',
                    'msg' => '修改成功',
                    'redirect' => input('post.redirect'),
                ];
            } else {
                return [
                    'status' => 'error',
                    'msg' => '修改失败',
                ];
            }
        }

        return view();
    }

    public function mine()
    {
        $user = db('t_member')->field('member_name, member_regtime, admin_modified')->where(['member_id' => Session::get('login_id')])->find();

        return view('', ['user' => $user]);
    }
}