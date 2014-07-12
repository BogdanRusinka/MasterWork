<?php 


  function formula(){  
  ?>
  <form method="POST">
	<table>
		<tr><td><div class="input">Введите формулу</div></td><td><input id="formula" type="text" size="40" name="equation" value="<?= @$_POST['equation'] ?>" class="field"></td></tr>
		<tr><td></td><td><input type="submit" value="Ввести формулу" name="formula" id="button"><input type="button" value="Очистить" name="enter" id="button" onclick="clearForm()"></td></tr>
	</table>
	<table>
	<tr><td><input class="formula_buttons" type="button" value="A"></td><td><input class="formula_buttons" type="button" value="B"></td><td><input class="formula_buttons" type="button" value="C"></td><td><input class="formula_buttons" type="button" value="D"></td><td><input class="formula_buttons" type="button" value="F"></td>
	<td><input class="formula_buttons" type="button" value="G"></td><td><input class="formula_buttons" type="button" value="H"></td><td><input class="formula_buttons" type="button" value="I"></td><td><input class="formula_buttons" type="button" value="J"></td><td><input class="formula_buttons" type="button" value="K"></td>
	<td><input class="formula_buttons" type="button" value="L"></td><td><input class="formula_buttons" type="button" value="M"></td><td><input class="formula_buttons" type="button" value="N"></td><td><input class="formula_buttons" type="button" value="O"></td><td><input class="formula_buttons" type="button" value="P"></td>
	<td><input class="formula_buttons" type="button" value="Q"></td><td><input class="formula_buttons" type="button" value="R"></td><td><input class="formula_buttons" type="button" value="S"></td><td><input class="formula_buttons" type="button" value="T"></td><td><input class="formula_buttons" type="button" value="U"></td></tr>
	<tr><td><input class="formula_buttons" type="button" value="V"></td><td><input class="formula_buttons" type="button" value="W"></td><td><input class="formula_buttons" type="button" value="X"></td><td><input class="formula_buttons" type="button" value="Y"></td><td><input class="formula_buttons" type="button" value="Z"></td>
	<td><input class="formula_buttons" type="button" value="1"></td><td><input class="formula_buttons" type="button" value="2"></td><td><input class="formula_buttons" type="button" value="3"></td><td><input class="formula_buttons" type="button" value="4"></td><td><input class="formula_buttons" type="button" value="5"></td>
	<td><input class="formula_buttons" type="button" value="6"></td><td><input class="formula_buttons" type="button" value="7"></td><td><input class="formula_buttons" type="button" value="8"></td><td><input class="formula_buttons" type="button" value="9"></td><td><input class="formula_buttons" type="button" value="0"></td>
	<td><input class="formula_buttons" type="button" value="."></td><td><input class="formula_buttons" type="button" value="*"></td><td><input class="formula_buttons" type="button" value="/"></td><td><input class="formula_buttons" type="button" value="+"></td><td><input class="formula_buttons" type="button" value="-"></td></tr>
	<tr><td><input class="formula_buttons" type="button" value="("></td><td><input class="formula_buttons" type="button" value=")"></td><td><input class="formula_buttons" type="button" value="e"></td></tr>
	</table>
  <form>

  <?php
}
?>