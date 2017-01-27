<?php 

	require_once 'Smarty.class.php';

	$smarty = new Smarty();
	$data[0][] = 1;
	$data[0][] = 2;
	$data[0][] = 3;
	$smarty->assign('da', 111);
	$smarty->assign('data', $data);
	$smarty->display('test.tpl');

