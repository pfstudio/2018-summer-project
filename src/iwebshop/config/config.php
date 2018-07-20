<?php
return array(
	'logs'=>array(
		'path'=>'backup/logs',
		'type'=>'file'
	),
	'DB'=>array(
		'type'=>'mysqli',
        'tablePre'=>'iwebshop_',
		'read'=>array(
			array('host'=>'localhost:3306','user'=>'root','passwd'=>'','name'=>'demo'),
		),

		'write'=>array(
			'host'=>'localhost:3306','user'=>'root','passwd'=>'','name'=>'demo',
		),
	),
	'interceptor' => array('plugin'),
	'langPath' => 'language',
	'viewPath' => 'views',
	'skinPath' => 'skin',
    'classes' => 'classes.*',
    'rewriteRule' =>'url',
	'theme' => array('pc' => array('huawei' => 'default','sysdm' => 'default','sysseller' => 'default'),'mobile' => array('mobile' => 'default','sysdm' => 'default','sysseller' => 'default')),
	'timezone'	=> 'Etc/GMT-8',
	'upload' => 'upload',
	'dbbackup' => 'backup/database',
	'safe' => 'cookie',
	'lang' => 'zh_sc',
	'debug'=> '0',
	'configExt'=> array('site_config'=>'config/site_config.php'),
	'encryptKey'=>'d41d8cd98f00b204e9800998ecf8427e',
	'authorizeCode' => '',
	'uploadSize' => '10',
);
?>