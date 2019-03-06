<?php 
define('ROOT_PATH',str_replace('\\','/',dirname(__DIR__)) . '/');
#通过命名空间 加载各种类文件
spl_autoload_register( function($classname){

	#构建文件路径
	$classname = ROOT_PATH.str_replace('\\','/',$classname).'.php';

	// echo "$classname";
	if( file_exists($classname) ){
// echo "$classname";
		include $classname;
	}

} );
