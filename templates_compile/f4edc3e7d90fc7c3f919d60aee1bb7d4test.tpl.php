<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
	 <?php foreach ($this->template_var['data']['0'] as $k => $v){ ?>
        <p><?php echo $v ?></p>   
     <?php } ?> 

	 <?php foreach ($this->template_var['data']['0'] as $k => $v){ ?>
        <?php if ($v > 1){ ?>
        <?php echo $v ?>--判断
        <?php } ?> 
     <?php } ?> 

	 
	 <?php if ($this->template_var['data']['0'] == 1){ ?>
	 	<p>true</p>
	 <?php } ?>

	 <?php if ($this->template_var['data']['0'] > 0){ ?>
	 	<p>true</p>
	 <?php } ?>
	

	 <?php echo $this->template_var['data']['0']['1'] ?>
</body>
</html>