<?php 
header('content-type:text/html;charset=utf-8');
/**
 * mini_smarty模板引擎
 * @link http://www.cnblogs.com/isuhua/
 * @author 华仔_suhua <weibo.com/suhua123>
 *
 */

class Smarty
{
	//模板文件目录
	public $template_dir = './templates';
	//编译文件目录
	public $compile_dir = './templates_compile';
	//缓存文件目录
	public $cache_dir = './cache';
	//模板变量
	public $template_var = array();
	//开启缓存
	public $cache_switch = false;
	//左定界符
	public $left_detimiter = "{";
	//右定界符
	public $right_detimiter = "}";

	public function __construct()
	{	

		$this->checkDir();
	}

	//自动创建目录
	private function checkDir()
	{
		!is_dir($this->template_dir) && mkdir($this->template_dir);
		!is_dir($this->compile_dir) && mkdir($this->compile_dir);
		!is_dir($this->cache_dir) && mkdir($this->cache_dir);
	}

	//模板变量赋值
	public function assign($tpl_var, $var = '')
	{
		if ( isset($tpl_var) && !empty($tpl_var) ) {
			$this->template_var[$tpl_var] = $var;
		} else {
			exit('模板变量出错');
		}
	}

	//模板展示
	public function display($file='')
	{
		$template_file = $this->template_dir.'/'.$file;
		if ( !file_exists($template_file) ) {
			exit('模板文件不存在');
		}

		//编译文件
		$parse_file = $this->compile_dir.'/'.md5($file).$file.'.php';
		//若编译文件不存在或者模板文件被修改过则重新编译文件
		//若在开发阶段，可以把条件设为true
		if (!file_exists($parse_file) || filemtime($parse_file) < filemtime($template_file)) {
			require_once 'SmartyCompile.class.php';
			$compile = new SmartyCompile($template_file,$this->left_detimiter,$this->right_detimiter);
			$compile->parse($parse_file);
		}

		//开启了缓存则加载缓存文件，否则加载编译文件
		if ( $this->cache_switch ) {
			$cache_file = $this->cache_dir.'/'.md5($file).$file.'.html';

			//当缓存不存在或者编译文件被修改过则重新生成缓存文件
			if ( !file_exists($cache_file) || filemtime($cache_file) < filemtime($parse_file) ) {
				include $parse_file;
				$content = ob_get_clean();
				if ( !file_put_contents($cache_file, $content) ) {
					exit('缓存文件生成失败');
				}
			}

			//载入缓存文件
			include $cache_file;
		} else {
			//载入编译文件
			include $parse_file;
		}

	}

}