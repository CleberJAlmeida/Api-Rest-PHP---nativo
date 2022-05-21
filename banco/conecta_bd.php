<?php
$login="id18040749_admin2";
$senha="cleber1234C*";
$banco="id18040749_banco2";
$host = "localhost";
$con = mysqli_connect($host,$login,$senha,$banco);
if($con){
	//echo("conectado<br/>");
}
else{
	echo("Não Conectado<br/>");
}
?>