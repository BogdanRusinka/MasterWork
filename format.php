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

	$(document).ready(function()
	{
			$(".number").show();
			$("#StackOfOps").hide();
			$("#MathResults").hide();
	$(".showForm").click(function()
		{
			$(".number").show();
			$("#StackOfOps").hide();
			$("#MathResults").hide();
		});	
	$(".showSt").click(function()
		{
			$(".number").hide();
			$("#StackOfOps").show();
			$("#MathResults").hide();
		});	
	$(".showRes").click(function()
		{
			$(".number").hide();
			$("#StackOfOps").hide();
			$("#MathResults").show();
		});
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
<button class='btn btn-info showForm' name='Показать/скрыть форматы чисел' value='Показать/скрыть форматы чисел' style="margin:15px">Формат чисел</button>
<button class='btn btn-info showSt' name='Показать/скрыть форматы чисел' value='Показать/скрыть форматы чисел' style="margin:15px">Стек операций</button>
<button class='btn btn-info showRes' name='Показать/скрыть форматы чисел' value='Показать/скрыть форматы чисел' style="margin:15px">Результаты вычислений</button>
<?php
$vremiya_starta = microtime(true);
require "class.math.php";
require "class.parse.php";
require "class.tetra.php";//require "db.php";
require "vars.php";
$c = new math("01000010101010101010100","M");
//$opRes = $c->Divide("10000110", "1110000100100111011MA","10000001","00000100111111011111M","PB32");
//echo "<h3>".$opRes[1]."</h3>";
//$num = $opRes[1];
//$p = $opRes[0];
//	$arr = Arr($num);
//	foreach ($arr as $key=>$el){// echo $el;
//		$number = $c->TetraToDec($p,$el);
//		echo "<b>".$number."</b><br>";
//	}

					
//echo "<h3>".$c->TetraToDec("10000011","010000000000000000000")."</h3>";
//$res = $c->Summarize("10001111","111000100100000000100","10000101","11101100011100011100M","PB32");
//echo $c->TetraToDec($res[0],$res[1]);
//$c = floatval("22222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222");
//echo $c;
//$c = new math();
//$c->RoundMantissa("110101MAA","1A");
//echo $c->MantissaLess("111011100111110010011AAAAAAAAAAAAAAAAAAAAAAAAAAA");
echo $c->TetraSum();
//$res = $c->AddPorr("11110111","10001000",8);
//echo "Overfow: ".$res[1]." Result ".$res[0];

//$mysqli = new mysqli("127.0.0.1", "root", "", "master");
//if ($mysqli->connect_errno) {
  //  echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
//}
$counter=0;
$str = "-3e12"; $labell=""; $labelr="";
foreach($_POST as $key=>$value) 
  if(strlen($value)==0) 
    $counter++;
  if ($counter==0){
	$obj = new parse();
    $massiv = array();
	array_pop($_POST);
    $countPost=0;
	$connection = new MongoClient();
$db = $connection->Master;
	$variables = $db->Variables;
	$stack = $db->Stack;
	foreach ($_POST as $var => $value) { //echo $value;
	$real = $obj->numNorm($value);
      if (preg_match("/[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)/",$value, $match)==1)
		{
			$value = $obj->makePatternExp($value);// echo $value;
			$real = $value;
			$value = $obj->ExpToNum($value);
		}
	 $value = $obj->numNorm($value);
	 if ($countPost % 2 ==0){
		//$mysqli->query("UPDATE `variables` SET `lpart`='$value', `lpartreal`='$real' WHERE `variable`='".$var[strlen($var)-1]."'");
		
		$newdata = array('$set'=> array("lpart"=>$value,"lpartreal"=>$real));
		$variables->update(array("variable" => $var[strlen($var)-1]),$newdata);
	 } else{
	//	$mysqli->query("UPDATE `variables` SET `rpart`='$value', `rpartreal`='$real' WHERE `variable`='".$var[strlen($var)-1]."'");
		$newdata = array('$set'=> array("rpart"=>$value,"rpartreal"=>$real));
		$variables->update(array("variable" => $var[strlen($var)-1]),$newdata);
	 }
	 $countPost++;
    }
	//$result = $mysqli->query("SELECT * FROM `variables`");
	$test = $variables->find();
	
 
 
echo "			  <div class='number' style='overflow: auto; white-space: nowrap'>
<h3>ФОРМАТ ЧИСЕЛ</h3>";
	foreach ($test as $row)
	{		
			$formatType = "PB32";
				if (NumDec($row["lpartreal"])>=7)
					$formatType = "PB64";
			$first = FormatPack($row["lpart"],$formatType);
			$second = FormatPack($row["rpart"],$formatType);
			if (($first["AI"]!="N" && $first["AI"]!="Z")||($second["AI"]!="N" && $second["AI"]!="Z")){
				$formatType = "PB64";
				$first = FormatPack($row["lpart"],$formatType);
				$second = FormatPack($row["rpart"],$formatType);
			}
			$labell = checkAI($first["AI"]); 
			$labelr = checkAI($second["AI"]); //echo $first["AI"];
            $sign = "+";
			if ($first["SIGN"] != $second["SIGN"])
              $sign = "1";
			else 
              $sign = "0";
			  $format = "".$sign.$first["poryadok"].$first["mantissa"].$second["poryadok"].$second["mantissa"]."";
			  $variable = $row["variable"];
			  echo "
			
			<table class='table table-bordered'>
			<tr align='center'>
				<td><b>Переменная </b></td>
				<td><b>Значение</b></td>
				<td><b>Флаг</b></td>
				<td><b>Формат</b></td>
			</tr>
			<tr align='center'>	
				<td rowspan=2><h4>".$row['variable']."</h4></td>
				<td><i>".$row['lpartreal']."</i></td>
				<td>".$labell."</td>
				<td rowspan=2><h4>".$formatType."</h4></td>
			</tr>
			<tr align='center'><td><i>".$row['rpartreal']."</i></td>
				<td>".$labelr."</td>
			</tr>
			</table>
			  
			  <table class='table table-condensed table-bordered'>
			<tr align='center' class='info'>
					<td class='danger'>S</td>
					<td class='sign'>E</td>
					<td class='warning'>M</td>
					<td class='danger'>X</td>
					<td class='sign'>E</td>
					<td class='warning'>M</td>
					<td class='success'>MF</td>
					<td class='success'>CF</td>
			</tr>
			<tr>
					<td class = 'sign1' style='text-align:center'>".$sign."</td>
					<td class='poryadok1' style='text-align:center'>".$first["poryadok"]."</td>
					<td class='mantissa1' style='text-align:center'>".$first["mantissa"]."</td>
					<td style='text-align:center'>XX</td>
					<td class='poryadok1' style='text-align:center'>".$second["poryadok"]."</td>
					<td class='mantissa1' style='text-align:center'>".$second["mantissa"]."</td>
					<td class='mf1' style='text-align:center'>".$MF[$formatType]."</td>
					<td class='cf1' style='text-align:center'>".$CF[$formatType]."</td>
					
					</tr></table>"; //echo $second["mantissa"];
			 // $query = "UPDATE `variables` SET `bin_form`='$format',`sign`='$sign', `p1`='".$first['poryadok']."', `m1`='".$first['mantissa']."', `p2`='".$second['poryadok']."', `m2`='".$second['mantissa']."', `format`='$formatType',`mf`='$MF[$formatType]', `cf`='$CF[$formatType]' WHERE `variable`= '$variable' ";
			  $newdata = array('$set'=> array("sign"=>$sign,"p1"=>$first['poryadok'],"m1"=>$first['mantissa'],"p2"=>$second['poryadok'],"m2"=>$second['mantissa'],"format"=>$formatType,"cf"=>$CF[$formatType],"mf"=>$MF[$formatType]));
			  $variables->update(array("variable" => $variable),$newdata);
			//  if (!$mysqli->query($query))
		//	echo "Не удалось записать данные: (" . $mysqli->errno . ") " . $mysqli->error;					
	}

		   
			echo "</div><div id='StackOfOps'><h3>Стек операций</h3>";
			echo "<table class='table table-hover'><thead><tr><td>Операнд</td><td>Внутренняя операция</td></tr></thead>";
			$st = $stack->find();
			//$result = $mysqli->query("SELECT * FROM `stack`") ;
			foreach ($st as $row)
			 echo "<tr><td>".$row['operand']."</td><td>".$row['operation']."</td></tr>";
			  echo "</table></div>";
			  
		/*	$result = $mysqli->query("SELECT * FROM `stack` WHERE `operand`='F0'");
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
						{
						
						$op = $row['operation'];
			if (!$mysqli->query("DROP TABLE IF EXISTS nstack") ||
				!$mysqli->query("CREATE TABLE nstack (PID INT NOT NULL AUTO_INCREMENT, 
			PRIMARY KEY(PID), operand VARCHAR(32),operation VARCHAR(32), value VARCHAR(200))")) {
				echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
			} else echo "<div class='alert alert-success'>Stack's and variables are created </div>";
						$mysqli->query("INSERT INTO `nstack` (`operand`,`operation`) VALUES ('F0','$op')");
						stackOrganize($row['operation'],$mysqli);
						
						echo "<h3>Стек операций упорядоченный</h3>";
			  echo "<table class='table table-hover'><thead><tr><td>ID</td><td>Операнд</td><td>Внутренняя операция</td></tr></thead>";
			  
			$result = $mysqli->query("SELECT * FROM `nstack` ORDER BY  `nstack`.`PID` DESC ") ;
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
			 echo "<tr><td>".$row['PID']."</td><td>".$row['operand']."</td><td>".$row['operation']."</td></tr>";
			  echo "</table>";
			  // шлак для гифки
			 

			 }*/
			 $equation = $db->Formula;

			 $formul = $equation->findOne();
			echo "<h2>".$formul['eq']."</h2>";
			 echo "<div id='MathResults'><h3>РЕЗУЛЬТАТЫ ВЫЧИСЛЕНИЯ ФУНКЦИИ ".$formul['eq']."</h3>";	
			// $result = $mysqli->query("SELECT * FROM `stack` ");
			foreach ($st as $row)
			 {
				ShowFormatInfo($row["operand"],$row["operation"]);
				$sign = "+";
				if ($row["operation"][0] == "+" || $row["operation"][0] == "-"){
					$sign = $row["operation"][0];
					$row["operation"] = substr($row["operation"],1);
				}
				if($sign=="+")
					$sign = 0;
				else $sign = 1;
				$vars = preg_split("/[\+\-\*\/]/", $row['operation']);
				//var_dump($vars);
				$array_empty = array("");
				$vars = array_diff_assoc($vars, $array_empty);
				//$leftOp1 = $mysqli->query("SELECT * FROM `variables` WHERE `variable`='$vars[0]' ")->fetch_array(MYSQLI_ASSOC);
				//echo $vars[0];
				$leftOp = $variables->findOne(array('variable'=>$vars[0]));
			//	$rightOp2 = $mysqli->query("SELECT * FROM `variables` WHERE `variable`='$vars[1]' ")->fetch_array(MYSQLI_ASSOC);
				$rightOp = $variables->findOne(array('variable'=>$vars[1]));
				$func = new math();				
				$operands = $func->makeEqualFormats($leftOp,$rightOp);
				$s = microtime(true);
				$OpRes = $func->Calculate($operands,$row["operation"],$sign);
				$e = microtime(true); $t = $e-$s;
				echo "<br>Время выполнения операции: ".$t." секунд<br>";
				if ($OpRes[1]["error"]!=1 && $OpRes[0]["error"]!=1){
				if ($variables->findOne(array("variable"=>$row["operand"]))==NULL)
					{
						$variables->insert(array("variable" => $row["operand"], "sign"=>$OpRes[2],"p1"=>$OpRes[0]['poryadok'],"m1"=>$OpRes[0]['mantissa'],"p2"=>$OpRes[1]['poryadok'],"m2"=>$OpRes[1]['mantissa'],"format"=>$OpRes[0]['format']));
						ShowFormatResult($OpRes[2],$OpRes[0]['poryadok'],$OpRes[0]['mantissa'],$OpRes[1]['poryadok'],$OpRes[1]['mantissa'],$OpRes[0]['format']);
					}
					ShowDecimalValues($OpRes[0]["mantissa"],$OpRes[0]["poryadok"]);
					ShowDecimalValues($OpRes[1]["mantissa"],$OpRes[1]["poryadok"]);
				} else {
					if ($OpRes[1]["format"]=="PB64"){
						echo "OPERATION OVERFLOW! END OF CALCLATIONS!";
					} else {
						$operands = $func->IncFormats($leftOp,$rightOp);
						$operands[0]["format"]= $operands[2];
						$operands[1]["format"]= $operands[2];
						$OpRes = $func->Calculate($operands,$row["operation"],$sign); //var_dump($OpRes);
						if ($OpRes[1]["error"]==1 || $OpRes[0]["error"]==1){
							echo "OPERATION OVERFLOW! END OF CALCLATIONS!";
						} else {
							if ($variables->findOne(array("variable"=>$row["operand"]))==NULL)
								{
								$variables->insert(array("variable" => $row["operand"],  "sign"=>$OpRes[2], "p1"=>$OpRes[0]['poryadok'],"m1"=>$OpRes[0]['mantissa'],"p2"=>$OpRes[1]['poryadok'],"m2"=>$OpRes[1]['mantissa'],"format"=>$OpRes[0]['format']));
								ShowFormatResult($OpRes[2],$OpRes[0]['poryadok'],$OpRes[0]['mantissa'],$OpRes[1]['poryadok'],$OpRes[1]['mantissa'],$OpRes[0]['format']);
	
						}
						ShowDecimalValues($OpRes[0]["mantissa"],$OpRes[0]["poryadok"]);
						ShowDecimalValues($OpRes[1]["mantissa"],$OpRes[1]["poryadok"]);
						}
					}
				}  
					
				
			 }
			 echo "</div>";
			 
			 echo '<a href="index.php" class="btn btn-info" >На главную</a>';
			} else echo "<div class='error'>Поле с числом пустое</div>";
$vremya_okonchaniya = microtime(true);
$vremya = $vremya_okonchaniya - $vremiya_starta;
	echo "<br>Время выполнения скрипта: ".$vremya." секунд<br>";
?>	
</div>
<center style="color : white">Copyright &copy; Ivanitsa S.V., Dorozhko L.I., Rusinka B.V. - 2014</center>
</body>
</html>
<?php		 

function NumDec($str){
$num=0;
	for ($i=0; $i<strlen($str);$i++)
		if ($str[$i]!="0" && $str[$i]!="." && $str[$i]!="+" && $str[$i]!="-")
			$num++;
	return $num;
}	 
function checkAI($AI){
	switch ($AI){
		case "N": 
		 return "<label class='label label-primary'>Normalized</label>";
		case "NaN": 
		 return "<label class='label label-danger'>NaN</label>";
		case "Inf": 
		 return "<label class='label label-warning'>Inf</label>";
		case "U": 
		 return "<label class='label label-warning'>UNDERFLOW</label>";		
		case "Z": 
		 return "<label class='label label-default'>Zero</label>";
		case "O": 
		 return "<label class='label label-danger'>OVERFLOW</label>";
		default: 
		 return "<label class='label label-danger'>UNDEFINED</label>";
	}
}

function FormatPack($num,$format){
	$number = new tetra();
	$f = $number->getBinString($num,$format);
	$sign_f = $f[0];
    $first = $number->format($f,$format);
	$first["SIGN"] = $sign_f;
	return $first;
}
		 

function Arr($m){// echo $m;
for ($i=0; $i<=strlen($m)-1;$i++){
	if ($m[$i]=="A")
		{
			$f = $f."1";
			$s = $s."0";
		} else 
	if ($m[$i]=="M")
		{ //echo "ok";
			$f = $f."0";
			$s = $s."1";
		} else 	
	{
			$f = $f.$m[$i];
			$s = $s.$m[$i];
	}
}
return array(0=>$f,1=>$s);
}

function ShowFormatInfo($op,$opn){

echo "<center><h4>$op | $opn</h4></center>";
}

function ShowFormatResult($sign,$p1,$m1,$p2,$m2,$format){
echo "<table class='table table-condensed table-bordered'>
			<tr align='center' class='info'>
					<td class='danger'>S</td>
					<td class='sign'>E</td>
					<td class='warning'>M</td>
					<td class='danger'>X</td>
					<td class='sign'>E</td>
					<td class='warning'>M</td>
					<td class='success'>MF</td>
					<td class='success'>CF</td>
			</tr>
			<tr>
					<td class = 'sign1' style='text-align:center'>".$sign."</td>
					<td class='poryadok1' style='text-align:center'>".$p1."</td>
					<td class='mantissa1' style='text-align:center'>".$m1."</td>
					<td style='text-align:center'>XX</td>
					<td class='poryadok1' style='text-align:center'>".$p2."</td>
					<td class='mantissa1' style='text-align:center'>".$m2."</td>
					<td class='mf1' style='text-align:center'>".$MF[$format]."</td>
					<td class='cf1' style='text-align:center'>".$CF[$format]."</td>
					
					</tr></table>";
}

function ShowDecimalValues($mant,$por){
$func = new math();
echo "<br>[ ";
if (preg_match("/[MA]/",$mant, $match)){
	$arr = Arr($mant);
foreach ($arr as $key=>$el){
	$number = $func->TetraToDec($por,$el);
	$color = rand(11,99);
	echo "<b style='color : #e$color;'>".$number." </b>";
}
} else { //echo $OpRes[0]["poryadok"];
		$number = $func->TetraToDec($por,$mant);
		echo "<b>".$number." </b>";
		}
	echo "]<br>";
}





       

	   
?>