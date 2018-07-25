<?php
class System extends IController
{
    public $layout = 'admin';

	//后台登陆
	function login_act()
	{
		$admin_name = IFilter::act(IReq::get('admin_name'));
		$password   = IReq::get('password');
		$captcha    = IReq::get('captcha','post');
		$_captcha   = ISafe::get('captcha');

		$message    = '';

		if($admin_name == '')
		{
			$message = '登录名不能为空';
		}
		else if($password == '')
		{
			$message = '密码不能为空';
		}
		else if(!$captcha || !$_captcha || $captcha != $_captcha)
		{
            $message = '验证码输入不正确';
        }
        else
		{
			$adminObj = new IModel('admin');
			$adminRow = $adminObj->getObj('admin_name = "'.$admin_name.'"');
			if($adminRow && ($adminRow['password'] == md5($password)) && ($adminRow['is_del'] == 0))
			{
				$dataArray = array(
					'last_ip'   => IClient::getIp(),
					'last_time' => ITime::getDateTime(),
				);
				$adminObj->setData($dataArray);
				$where = 'id = '.$adminRow["id"];
				$adminObj->update($where);

				//通知事件
				plugin::trigger("adminLoginCallback",$adminRow);

				$this->redirect('/system/default');
			}
			else
			{
				$message = '用户名与密码不匹配';
			}
		}

		if($message != '')
		{
			$this->admin_name = $admin_name;
			$this->redirect('index',false);
			Util::showMessage($message);
		}
	}

	//后台登出
	function logout()
	{
    	$this->redirect('index');
	}
}