<?php
namespace app\admin\model;

use think\Db;

class Account
{
	public function getMemberByName($loginName)
	{
		// 查询
		return db('t_member')->field('*')->where(['member_name' => $loginName])->find();
	}

	public function isMemberByName($loginName)
	{
		return db('t_member')->where('member_name', $loginName)->select();
	}
}