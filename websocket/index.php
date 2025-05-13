<?php session_start();
if(isset($_POST['submit'])){
	$name=$_POST['name'];
	$_SESSION['name']=$name;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
</head>

<body>
<?php if(!isset($_SESSION['name'])){?>
<form method="post" action="">
<input name="name" type="text" required />
<input name="submit" type="submit" value="submit" />
</form>
<?php }else{?>
<input type="hidden" id="name" value="<?php echo $_SESSION['name'];?>" />
<input name="msg" type="text" id="msg" />
<input name="" type="button" id="btn" value="Send" />
<div id="msgbox"></div>
<script>
var conn = new WebSocket('ws://159.223.42.201:60000');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
	var getData=jQuery.parseJSON(e.data);
	var html="<b>"+getData.name+": </b>"+getData.msg+"<br>";
   $("#msgbox").append(html); 
	//console.log(e.data);
};
$("#btn").click(function(){
	var msg=$("#msg").val();
	var name=$("#name").val();
	var content={
		msg:msg,
		name:name
		};
		conn.send(JSON.stringify(content));
		var html="<b>"+name+": </b>"+msg+"<br>";
   $("#msgbox").append(html); 
	var msg=$("#msg").val('');
	
	});
</script>
<?php } ?>
</body>
</html>
