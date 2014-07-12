<?php 
include "class.parse.php";
$str = "-FF+";
echo $str[strpos($str,"-")-1];
echo $_POST['equation'];
if (isset($_POST["formula"]) && $_POST["formula"]!="")
	{
		$formula = new parse();
		$checkResult = checkRight($formula);
		echo  '<meta http-equiv="refresh" content="0; url=input_variables.php?f='.$_POST['equation'].'">';
	}

?>
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

	function checkForm(){
		
	 var form = $('#formula').val();
	 var brack = 0;
	 for (var i=0; i<form.length; i++){
		if (form[i]=="(")
			brack++;
		if (form[i]==")")
			brack--;
	 }
	 if (brack!=0)
		{ 	$('#checkbtn').removeClass().addClass("btn btn-danger");
			$('#formSubm')[0].disabled = true;
			$("#error").html("<div class='alert alert-danger'>Проверьте правильность скобок!</div>").fadeIn(300).delay(3000).slideUp( 300 );
			return 0;
		}
	$('#formSubm')[0].disabled = false;
	$('#checkbtn').removeClass().addClass("btn btn-success");
	$("#error").html("<div class='alert alert-success'>Формула введена верно!</div>").fadeIn(300).delay(3000).slideUp( 300 );
	// тут же поставить защиту от дурака
	}
  </script>
</head>
<body>	
 <div id="wrapper">
 <header>
  <center><h1>ДРОБНЫЙ ФОРМАТ 256/64fp</h1></center>
  <center><p class="lead">Модифицированный формат с динамической разрядностью.</p></center>
 </header>
    <center>
	<h2>Введите формулу:</h2>
	<div id="error" style="position : absolute"></div>
  <div id="mainform">
	<form method="get" action="input_variables.php" id="ff">
	<table class="table_formula">
		<tr>
			<td colspan=2>
				<input type="text" onchange = "checkForm()"id="formula" name="equation" value="<?= @$_POST['equation'] ?>" class="form-control" style="top: 24px; left: 0px; width: 600px; font-size: 24px; height: 75px; padding-left: 15px; padding-right: 15px; lineheight: 44px; border-radius:10px;" required></input>
			</td>
			<td>
				<div class="btn btn-warning" id="checkbtn" onclick="checkForm()"><span class="glyphicon glyphicon-cog"></span></div>
			</td>
		</tr>
		<tr>
			  <td>
				  <input type="submit" id="formSubm" value="Ввести формулу" name="formula"  class="btn btn-primary btn-lg btn-block" style="margin-top:5px;" disabled>
			  </td>
			  <td>
				  <input type="reset" value="Очистить" class="btn btn-info btn-lg btn-block" style="margin-top:5px;">
			  </td>
		</tr>   
  </table>
  </form>
  </div>
  </center>
    			
	 <blockquote class="rules" id="quota">
    
    <small>Переменные необходимо вводить одной латинской буквой. Например "R" </small>
    <small>Числовые значения можно вводить в любом формате. Например "65" или "1.е+4". </small>
    <small>Разрешается ввод следующих арифметических знаков: + - / *.</small><br>
  </blockquote>
 </div>

<center style="color : white">Copyright (c) Ivanitsa S.V., Dorozhko L.I., Rusinka B.V. - 2014</center>

</body>
</html>