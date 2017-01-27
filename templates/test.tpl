<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

	 {{foreach ($data.0 as $k => $v)}
        <p>{$v}</p>   
     {/foreach}} 

	 {{foreach ($data.0 as $k => $v)}
        {if ($v > 1)}
        {$v}--判断
        {/if} 
     {/foreach}} 

	 
	 {{if ($data.0 == 1)}
	 	<p>true</p>
	 {/if}}

	 {{if ($data.0 > 0)}
	 	<p>true</p>
	 {/if}}
	

	 {$data.0.1}
</body>
</html>