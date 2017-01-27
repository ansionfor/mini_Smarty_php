<?php 
/**
 * mini_smarty模板引擎
 * @link http://www.cnblogs.com/isuhua/
 * @author 华仔_suhua <weibo.com/suhua123>
 *
 */
class SmartyCompile
{
	private $content = '';
	private $l_d = '';
	private $r_d = '';

	public function __construct($template_file, $left_detimiter, $right_detimiter)
	{
		$this->content = file_get_contents($template_file);
		$this->l_d = $left_detimiter;
		$this->r_d = $right_detimiter;
	}

	//替换变量
	public function replaceVar() 
	{  
        /** 
         *   $webname.name.cc.dd 
         *   $webname.name.cc[1].dd 
         *   $author[1][12].name[0] 
         *   $title[1] 
         *   $title.dd 
         *   $title.dd[1] 
         */  
        // \$(\w+(\.|(\d+)+)+)+(\w+(\d+)?)?        
        // \$((\w+(\.|(\d+)+)+)+(\w+(\d+)?)?)  
		//$pattern = '/\{\$((\w+(\.|(\d+)+)+)+(\w+(\d+)?)?)\}/';  
        $pattern = '/\{\$([^\{\}]+)\}/';  
        $matches = array();  
       
        preg_match_all($pattern, $this->content,$matches); 
        for($i = 0; $i < count($matches[1]); $i++) {  
            $str = $this->parseVar($matches[1][$i]);  
            $str = '<?php echo '.$str.' ?>';  
            $this->content = str_replace($matches[0][$i], $str, $this->content);  
        } 
       
    }  

    //替换普通变量
    /*public function replaceSimpleVar()
    {
    	$pattern = '/\{\$(\w+(?!\.|))\}/';
    	if ( preg_match($pattern, $this->content)) {
    		$this->content = preg_replace($pattern, '<?php echo \$this->template_var["$1"];?>', $this->content);
    	}
    }*/

    //编译二维及以下数组变量和普通变量
    //示例$data.arr.1, $data.0
    public function parseVar($arrStr) 
    {
    	$str = '$this->template_var';
    	$explode = '.';
    	$subStr = explode($explode, $arrStr);
    	for ($i=0; $i < count($subStr); $i++) { 
    		$pattern = '/(\w+)((\d+)+)/';
    		if ( !preg_match($pattern, $subStr[$i], $matches)) {
    			$str .= '['."'".$subStr[$i]."'".']';
    			continue;
    		}
    		$str .= '['."'".$matches[1]."'".']'.$matches[2];
    	}
    	return $str;
    }

 	//替换模板标签，即{{xxx}}，规定以{{xxx}开头，{/xx}}结尾，eg.{{if(true)}} 语句 {/if}}
    public function replaceSpecialWords()
    {
    	$pattern = '/\{(\{(\w+)(?:(?!\{\{).|\n)+\{\/\2\})\}/';
    	$matches = array();
    	preg_match_all($pattern, $this->content, $matches);

    	for ($i=0; $i < count($matches[1]); $i++) { 
    		$str = $this->parseSpecialWords($matches[1][$i]);
    		$this->content = str_replace($matches[0][$i], $str, $this->content);
    	}
    }

    //编译模板标签
    public function parseSpecialWords($str)
    {
    	//匹配出{}或者{{}或者{}}里面的字符串
    	$pattern = '/\{([^\{\{\}]+)\}/';
    	preg_match_all($pattern, $str, $matches);

    	//匹配第一行的变量，并替换
    	$pattern2 = '/\((\$([^\s]+))/';
    	preg_match($pattern2, $matches[0][0], $var);
    	$temp = $this->parseVar($var[2]);
    	$matches[1][0] = str_replace($var[1], $temp, $matches[1][0]);

    	//记录是foreach语句还是if语句
    	$flag = substr($matches[1][0], 0, 2);

    	$replace = '';
    	$count = count($matches[1]);  
    	for ($i=0; $i < $count; $i++) { 
    		//匹配循环里需要输出的变量
    		$pattern3 = '/^\$\w+$/';
    		if ( preg_match($pattern3, $matches[1][$i])) {
    			$replace = '<?php echo '.$matches[1][$i].' ?>';
    		} elseif ($matches[1][$i] == $matches[1][$count-1] || substr($matches[1][$i], 0, 1) == '/'){
    			$replace = '<?php } ?>';
    		} elseif (substr($matches[1][$i], 0 ,4) == 'else') {
    			$replace = '<?php } '.$matches[1][$i].'{ ?>';
    		} elseif ($flag == 'if' && substr($matches[1][$i], 0, 2) == 'fo') {
    			$pattern4 = '/\((\$([^\s]+))/';
    			preg_match($pattern4, $matches[0][$i], $var);
    			$temp = $this->parseVar($var[2]);
    			$matches[1][$i] = str_replace($var[1], $temp, $matches[1][$i]);
    			$flag = 'fo';
    			$replace = '<?php '.$matches[1][$i].'{ ?>';
    		} else {
    			$replace = '<?php '.$matches[1][$i].'{ ?>';
    		}
    		$str = str_replace($matches[0][$i], $replace, $str);
    		
    	}
    	return $str;
    }

    //模板编译
	public function parse($parse_file)
	{
		//设置正则最大回溯；
		ini_set('pcre.backtrack_limit', 99999); 
		//ini_set('pcre.recursion_limit', 99999);
		$this->replaceSpecialWords();
		$this->replaceVar();

		if ( !file_put_contents($parse_file, $this->content) ) {
			exit('编译文件出错');
		}
	}
}