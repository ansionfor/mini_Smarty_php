# mini_Smarty_php
小型Smarty模板引擎
 
###原文1：http://www.cnblogs.com/isuhua/  
###原文2：http://blog.csdn.net/johnny_nicolas/article/details/51574728
###修改整合by ansion 2017年1月28日
 
###有if和foreach两种模板标签语法



    {{foreach ($data.0 as $k => $v)}
      {if ($v > 1)}
      {$v}--判断
      {/if} 
     {/foreach}} 
	 
     {{foreach ($data.0 as $k => $v)}
        <p>{$v}</p>   
     {/foreach}} 
	 
	  {{if ($data.0 == 1)}
	   	<p>true</p>
	   {/if}}

	  {{if ($data.0 > 0)}
	   	<p>true</p>
	   {/if}}
	  

	   {$data.0.1}
