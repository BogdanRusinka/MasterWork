<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="keywords" content="jquery,ui,easy,easyui,web">
  <meta name="description" content="easyui help you build your web page easily!">
  <title>Tetracomputing</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/style.css">  

  <script type="text/javascript" src="js/jquery-1.3.1.min.js"></script>
  <script type="text/javascript" src="js/jQuery.js"></script>
  <script type="text/javascript" src="js/jquery.filter_input.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
   $(document).ready(function() {
    $('input').filter_input({regex:'[0-9e+-.]'});
    $('input#formula').filter_input({regex:'[0-9e+-.*/A-Z()]'});
    });
  </script>
</head>
<body>
 <div id="wrapper">
 <header>
  <center><h1>ДРОБНЫЙ ФОРМАТ 256/64fp</h1></center>
  <center><p class="lead">Модифицированный формат с динамической разрядностью.</p></center>
 </header>
    <center>
<?php
require "class.parse.php";
//require "db.php";
if ($_GET["equation"]==""){// по хорошемусчету редирект
	echo "<div class='alert alert-danger'>Поле пустое! Введите формулу</div>"; die;
}

/*if (!$mysqli->query("DROP TABLE IF EXISTS stack") ||
    !$mysqli->query("CREATE TABLE stack(PID INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(PID), operand VARCHAR(32),operation VARCHAR(32), value VARCHAR(200), sign VARCHAR (1), p1 VARCHAR (20), m1 VARCHAR (50), p2 VARCHAR (20), m2 VARCHAR (50), mf VARCHAR (12), cf VARCHAR (5), format VARCHAR (4))") || 
	!$mysqli->query("DROP TABLE IF EXISTS variables") || 
	!$mysqli->query("CREATE TABLE variables(PID INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(PID), variable VARCHAR(5),lpart VARCHAR(5000),lpartreal VARCHAR(100), rpart VARCHAR(5000), rpartreal VARCHAR(100), bin_form VARCHAR (10000), sign VARCHAR (1), p1 VARCHAR (20), m1 VARCHAR (50), p2 VARCHAR (20), m2 VARCHAR (50), mf VARCHAR (12), cf VARCHAR (5),format VARCHAR (4))")) {
    echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
}
*/

if ($_GET["equation"]!="")
{ 
$connection = new MongoClient();
$db = $connection->Master;
  $formula = new parse($_GET["equation"],$db);
  $vars = $formula->variables();
  $formula->expFind();
  echo $formula->Formula;
	$stack = $db->Stack;
	$equation = $db->Formula;
	$equation->drop();
	$equation->insert(array ("eq"=>$_GET["equation"]));
$response = $stack->drop();
  $formula->parseFormula(0,0);
  echo "<h3>Введите значения переменных</h3><form method='post' action='format.php' id='mainform'>";
  foreach ($vars as $var)
	input($var);
  echo "<table width='500px'><tr>
			  <td style='padding-right:5px'>
          <input type='submit' value='Ввести значения переменных' name='variables' class='btn btn-primary btn-lg btn-block'></td>
          <td><input type='reset' value='Очистить' class='btn btn-info btn-lg btn-block'>
      </td></tr></table>
	  </form>";
  $string = "";
	

} ?>
<blockquote class='rules'>
        <small>Переменные должны быть числом в обычной или экспоненциальной форме</small>
        <small>Необходимо вводить значения числителя и знаменателя. Если число не дробное, то знаменатель равен '1'</small>
        <small>Числа могут отрицательными и положительными</small><br></blockquote>
<?php 

function input($id){
echo '
<table>
    <tr>
      <td rowspan="2">'.$id.' = </td>
      <td style="border-bottom : 1px solid grey; padding-bottom : 3px;">
        <input class="form-control" type="text" size="40" name="f_part_'.$id.'" value="'.@$_POST["f_part"].'" style="top: 24px; left: 0px; width: 300px; font-size: 24px; height: 50px; padding-left: 15px; padding-right: 15px; lineheight: 44px; border-radius:10px;" required>
      </td>
      <td class="emptyfield"></td>
      </tr>
      <tr> 
      <td style="padding-top : 3px;">
        <input class="form-control" type="text" size="40" name="s_part_'.$id.'" value="'.@$_POST["s_part"].'" style="top: 24px; left: 0px; width: 300px; font-size: 24px; height: 50px; padding-left: 15px; padding-right: 15px; lineheight: 44px; border-radius:10px;" required>  
      </td>
        <td class="emptyfield"></td>
      </tr> 
</table><br>';
}



?>
</div>
<center style="color : white">Copyright Ivanitsa S.V., Dorozhko L.I., Rusinka B.V. - 2014</center>
</body>
</html>