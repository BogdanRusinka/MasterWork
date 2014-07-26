<?php
class math 
{
	public function __construct($first="", $second="", $buf = "0", $format = "PB32") 
	{
		$this->First = $first;
		$this->Second = $second;
		$this->Buf = $buf;
		$this->Format = $format;
	}
	
	public function TetraSum()
	{		
		$this->CorrectLength();
	//	echo $this->Second."<br>";
		unset($this->Result);
		//echo strlen($this->First);
		for ($i = strlen($this->First)-1; $i >= 0; $i--)
		{ 
			$this->Tres=$this->Sum($i,$this->Buf);
		    $this->Result = $this->Tres[1].$this->Result;
		    $this->Buf = $this->Tres[0];
		    if ($i==0)
		    	$this->Result = $this->Buf.$this->Result;
		    unset($this->Tarr);
		}
		$this->Buf = "0";
		return $this->Result;
	}
	
	private function Sum($i,$buf){
	$this->Tarr = array($this->First[$i], $this->Second[$i], $buf);
	sort($this->Tarr);
			if ($this->Tarr[0]==$this->Tarr[1])
				return $this->Tarr[0].$this->Tarr[2];
			if ($this->Tarr[0]==$this->Tarr[2])
				return $this->Tarr[0].$this->Tarr[1];
			if ($this->Tarr[1]==$this->Tarr[2])
				return  $this->Tarr[1].$this->Tarr[0];
			if (count(array_diff_assoc($operand = array ("1","A","M"), $this->Tarr))==0)
				return  "10";
			if (count(array_diff_assoc($operand = array ("0","A","M"), $this->Tarr))==0)
				return  "01";
			if (count(array_diff_assoc($operand = array ("0","1","M"), $this->Tarr))==0)
				return  "MA";
			if (count(array_diff_assoc($operand = array ("0","1","A"), $this->Tarr))==0)
				return  "AM";
	}
	
	public function TetraMul(){
		$this->CorrectLength();
		$first = $this->First;
		$second = $this->Second;
		$this->First = substr($this->zeros,0,strlen($this->Second));
		//echo $this->First."<br>";
		$iteration = 0;
		for($i = strlen($second)-1; $i >= 0; $i--){
			for($j = strlen($first)-1; $j >= 0; $j--){
				$this->Tres = $this->Mul($first[$j],$second[$i]).$this->Tres;
			}
		$this->Second = $this->Tres.substr($this->zeros,0,$iteration); 
		//echo "F: ".$this->Second."<br>";
		//echo "S: ".$this->First."<br>";
		$this->Result = $this->TetraSum();
		//echo "Result".$this->Result."<br>";
		$iteration++;
		$this->First = $this->Result; 
		$this->Result = "";
		$this->Tres = "";
		$this->Second = "";
		}
		//echo $this->First."<br>";
		return $this->First;
	}
	
	private function Mul($a, $b){
		if ($a=="0" || $b=="0")
			return "0";
		if ($a=="A" && $b=="M")
			return "0";
		if ($a=="M" && $b=="A")
			return "0";
		if ($a==$b)
			return $a;
		if ($a=="1") 
			return $b;
		if ($b=="1") 
			return $a;	
	}
	
	
	public function TetraSub(){
		$this->CorrectLength();
		$this->InvertTetrits();
		$this->Second = $this->TetraSum();
		$this->First = "1";
		return substr($this->TetraSum(),2);
	}
	
	public function InvertTetrits(){
		for ($i=0; $i<strlen($this->Second);$i++){
			if ($this->Second[$i]=="0")
				$this->Second[$i]="1";
			else if ($this->Second[$i]=="1")
				$this->Second[$i]="0";
			else if ($this->Second[$i]=="A")
				$this->Second[$i]="M";
			else if ($this->Second[$i]=="M")
				$this->Second[$i]="A";
		}
	}
	
	private function Dk(){
		for ($i=0; $i<strlen($this->Second);$i++){
			if ($this->Second[$i]=="0")
				$this->Second[$i]="1";
			else if ($this->Second[$i]=="1")
				$this->Second[$i]="0";
			else if ($this->Second[$i]=="A")
				$this->Second[$i]="M";
			else if ($this->Second[$i]=="M")
				$this->Second[$i]="A";
		}
		$this->First = "1";
		$this->Second = $this->TetraSum();
	}
	
	public function TetraDiv(){
		$result = ""; $cycle = strlen($this->First)+3;
		$this->Second = "01".$this->Second;
		$first = "01".$this->First;
		$B = $this->Second;
		$this->Dk();
		$this->First = $first;
		$nB = $this->Second; echo "nB: ".$nB."<br>";
		$C = $this->TetraSum(); echo "C1: ".$C."<br>";
		if (strlen($C) == $cycle) $C = substr($C,1);
		for ($i = 0; $i < $cycle; $i++){
			$t=$i+1; $t1 = $i+2;
			$result .= $this->InvTetrit($C[0]);
			$this->First = substr($C,1)."0"; echo "2C$t: ".$this->First."<br>";
			if ($C[0] == "1" || $C[0] == "M") $this->Second = $B; 
			else $this->Second = $nB; echo "+-B: ".$this->Second."<br>";
			$C = $this->TetraSum(); echo "C$t1: ".$C."<br>";
			if (strlen($C) == $cycle) $C = substr($C,1);	
		}
		return $result;
	}
	
	public function Divide($p1,$m1,$p2,$m2,$format){
	require "vars.php";
		$resPor = $this->SubPor($p1,$p2,8);
		$this->First = $m1;
		$this->Second = $m2;
		$mantissa = $this->TetraDiv();
		if ($mantissa[0]=="1"){
		$mantissa = substr($mantissa,1,$NumberMantissaLength[$format]);
		} else
			$mantissa = substr($mantissa,2,$NumberMantissaLength[$format]);
		return array(0=>$resPor,1=>$mantissa);
	}
	
	public function SubPor($p1,$p2,$num){
		$p2 = $this->Invert($p2);
		$result = intval(bindec($p1))+intval(bindec($p2));
		$bin = decbin($result); //echo $bin;
		$bin = substr("000000000000",0,$num - strlen($bin)+1).$bin;
		return $this->FirstBitInvert(substr($bin,1,strlen($bin)-1));
	}
	
	public function Invert($str){
		for ($i=0; $i < strlen($str); $i++){
			if ($str[$i]=="0")
				$str[$i]="1";
			else
				$str[$i]="0";
		}
		return $str;
	}
	
	public function InvTetrit($tetrit){
		if ($tetrit == "0") return "1";
		else if ($tetrit == "1") return "0";
		else if ($tetrit == "A") return "M";
		else if ($tetrit == "M") return "A";
	}
	
	public function OF($a,$b){
		return ~(intval($a) ^ intval($b))+2;
	}
	
	public function FirstBitInvert($str){
		if ($str[0]=="0")
			$str[0]="1";
		else 
			$str[0]="0";
		return $str;
	}
	
	public function AddPorr($a,$b,$num){
		$result = intval(bindec($a))+intval(bindec($b))+1;
		$bin = decbin($result); //echo $bin;
		$bin = substr("000000000000",0,$num - strlen($bin)+1).$bin;
		$of = $this->OF($bin[0],$bin[1]);
		//echo $this->FirstBitInvert(substr($bin,1,strlen($bin)-1));
		return array(0=>$this->FirstBitInvert(substr($bin,1,strlen($bin)-1)), 1=>$of);
	}
	
	public function PoryadokBigger($por){
		return $por[0].$this->ReturnThreeBits($por[0]).substr($por,1,strlen($por)-1);
	}
	
	private function ReturnThreeBits($bit){
		if ($bit == "1") return "000";
		else return "111";
	}
	
	public function MantissaBigger($mantissa){
	//echo "<h5>".$mantissa[strlen($mantissa)-1]."</h5>";
		if ($mantissa[strlen($mantissa)-1] == "A")
			return $mantissa."AAAAAAAAAAAAAAAAAAAAAAAAAAA";
		else
			return $mantissa."000000000000000000000000000";
	}
	
	public function PoryadokLess($str){
		$bits = substr($str,1,3);
		if (($str[0]!=$bits[0])&&($this->EqualPorBits($bits)))
			return $str[0].substr($str,4,7);
		else return "ERROR";
	}
	
	private function EqualPorBits($str){
		if ($str==="111" || $str==="000")
			return true;
		else return false;
	}
	
	public function MantissaLess($mantissa){
		$bits = substr($mantissa,-27,27);
		if ($this->EqualMantBits($bits))
			return substr($mantissa,0,21);
		else return "ERROR";
	}
	
	private function EqualMantBits($str){
		if ($str==="AAAAAAAAAAAAAAAAAAAAAAAAAAA" || $str==="000000000000000000000000000")
			return true;
		else return false;
	}
	
	public function makeEqualFormats($leftOp,$rightOp){
	//echo $leftOp["format"];
		if ($leftOp["format"]!=$rightOp["format"]){// echo "NOTEQ";
			if ($leftOp["format"]=="PB32"){
				$leftOp["m1"] = $this->MantissaBigger($leftOp["m1"]);
				$leftOp["p1"] =$this->PoryadokBigger($leftOp["p1"]);
				$leftOp["m2"] =$this->MantissaBigger($leftOp["m2"]);
				$leftOp["p2"] =$this->PoryadokBigger($leftOp["p2"]);
			} else 
			if ($rightOp["format"]=="PB32"){
				$rightOp["m1"] = $this->MantissaBigger($rightOp["m1"]);
				$rightOp["p1"] =$this->PoryadokBigger($rightOp["p1"]);
				$rightOp["m2"] =$this->MantissaBigger($rightOp["m2"]);
				$rightOp["p2"] =$this->PoryadokBigger($rightOp["p2"]);
			}
			return array(0=>$leftOp,1=>$rightOp);
		} else
		return array(0=>$leftOp,1=>$rightOp);
	}
	
	public function IncFormats($leftOp,$rightOp){
	//echo $leftOp["format"];
// echo "NOTEQ";
			if ($leftOp["format"]=="PB32"){
				$leftOp["m1"] = $this->MantissaBigger($leftOp["m1"]);
				$leftOp["p1"] =$this->PoryadokBigger($leftOp["p1"]);
				$leftOp["m2"] =$this->MantissaBigger($leftOp["m2"]);
				$leftOp["p2"] =$this->PoryadokBigger($leftOp["p2"]);
			}
			if ($rightOp["format"]=="PB32"){
				$rightOp["m1"] = $this->MantissaBigger($rightOp["m1"]);
				$rightOp["p1"] =$this->PoryadokBigger($rightOp["p1"]);
				$rightOp["m2"] =$this->MantissaBigger($rightOp["m2"]);
				$rightOp["p2"] =$this->PoryadokBigger($rightOp["p2"]);
			}
			return array(0=>$leftOp,1=>$rightOp,2=>"PB64");
	}
	
	public function Calculate($ops,$opn,$s){
	//var_dump($ops);
		$op = $this->DefineOperation($opn);
		$opcode = 0;
		if ($op=="-")
			$opcode = 1;
		switch ($op){
		case "*":
			$sign = strval($s ^ intval($ops[0]["sign"]) ^ intval($ops[1]["sign"])); 
			$resultNom = $this->Multiply($ops[0]["m1"],$ops[0]["p1"], $ops[1]["m1"],$ops[1]["p1"],$ops[0]["format"]);
			$this->Tres="";
			$resultDeNom = $this->Multiply($ops[0]["m2"],$ops[0]["p2"], $ops[1]["m2"],$ops[1]["p2"],$ops[0]["format"]);
			return array(0=>$resultNom,1=>$resultDeNom,2=>$sign);
		case "/":
			$sign = strval($s ^ intval($ops[0]["sign"]) ^ intval($ops[1]["sign"])); 
			$resultNom = $this->Multiply($ops[0]["m1"],$ops[0]["p1"], $ops[1]["m2"],$ops[1]["p2"],$ops[0]["format"]);
			$this->Tres="";
			$resultDeNom = $this->Multiply($ops[0]["m2"],$ops[0]["p2"], $ops[1]["m1"],$ops[1]["p1"],$ops[0]["format"]);
			return array(0=>$resultNom,1=>$resultDeNom,2=>$sign);
		case "+" || "-":
			$signl = $s ^ intval($ops[0]["sign"]);
			$realoperation = strval($signl ^ $ops[1]["sign"] ^ $opcode);
			$a = $this->Multiply($ops[0]["m1"],$ops[0]["p1"], $ops[1]["m2"],$ops[1]["p2"],$ops[0]["format"]);
			$b = $this->Multiply($ops[0]["m2"],$ops[0]["p2"], $ops[1]["m1"],$ops[1]["p1"],$ops[0]["format"]);
			$resultNom =  $this->Summarize($a["poryadok"],$a["mantissa"], $b["poryadok"],$b["mantissa"],$a["format"],$realoperation,$signl,intval($ops[1]["sign"]),$opcode);
			$this->Tres="";
			$resultDeNom = $this->Multiply($ops[0]["m2"],$ops[0]["p2"], $ops[1]["m2"],$ops[1]["p2"],$ops[0]["format"]);
			return array(0=>$resultNom,1=>$resultDeNom,2=>$resultNom["sign"]);
		default:
			echo "NOP";
		
		}		
	}
	
	public function MantissEquilizer($rb,$m,$decp){
		$rb = substr($m,-1,1).$rb[0];
		$m = "0".substr($m,0,strlen($m)-1);
		$decp++;
		return array( 0 => $m, 1 => $rb, 2 => $decp );
	}
	
	public function Summarize($p1,$m1,$p2,$m2,$format,$realoperation,$sA,$sB,$opcode){
	echo $sA;
	//echo $m1."<br>"; echo $m2;
	require "vars.php";  //echo $realoperation;
	$nulls = ("000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
	$decp1 = intval(bindec($p1)); $decp2 = intval(bindec($p2));
	$dif = $decp1 - $decp2;
	if ($dif == 0){
		$m1 = "1".$m1; $m2 = "1".$m2;
	} else if ( $dif < 0 ){ //echo "gi";
			$rb = "00"; $m1 = "1".$m1;
			while($decp1!=$decp2){
				$result = $this->MantissEquilizer($rb,$m1,$decp1);
				$m1 = $result[0];
				$rb = $result[1];
				$decp1 = $result[2];
			}
		$len = strlen($m1);
		$m1 = $this->RoundMantissa($m1,$rb);
		if (strlen($m1)>$len) $m1 = substr($m1,1);
		$m2 = "1".$m2;
		$p1 = $p2;
	} else if ($dif > 0) { //echo "gi";	
	//	echo $m2."<br>";
			$rb = "00"; $m2 = "1".$m2;
			while($decp2!=$decp1){
				$result = $this->MantissEquilizer($rb,$m2,$decp2);
				$m2 = $result[0];
				$rb = $result[1];
				$decp2 = $result[2];
			}
	//		echo $m2."<br>";
		$len = strlen($m2);
		$m2 = $this->RoundMantissa($m2,$rb);
		if (strlen($m2)>$len) $m2 = substr($m2,1);
		$m1 = "1".$m1;
		$p2 = $p1;
	}
	//echo $m1."<br>"; echo $m2;
	$eps = $this->Compare($m1, $m2);
	//echo $eps;  die;
	if($eps==0){
		$this->First = $m1; echo "<br>";
		$this->Second = $m2;
	} else if($eps==1){
		$this->First = $m2; echo "<br>";
		$this->Second = $m1;
	}
	
	if(!$realoperation) {
		$this->Tres = "";
		if ($eps == 2) 
			{	$p = bindec($p1); $p++;
				if ($p>2*$NumberOffset[$format]+1) $err = 1;
				return array("poryadok"=>substr($nulls,0,$NumberExponentLength[$format]-strlen(decbin($p))).decbin($p), "mantissa"=>substr($m1,1),"format"=>$format,"error"=>$err,"sign"=>$sA | $realoperation);
			}
		$sum = $this->TetraSum();
	}
	else { 
	if ($eps == 2) return array("poryadok"=>substr($nulls,0,$NumberExponentLength[$format]), "mantissa"=>substr($nulls,0,$NumberMantissaLength[$format]),"format"=>$format,"error"=>$err,"sign"=>"0");
	$sum = $this->TetraSub(); //if(strlen($sum)>$NumberMantissaLength[$format]+1) $sum = substr($sum,1);
	}
	
	$realsign = !$eps & $sA | $eps & ($opcode ^ $sB ); 
		if ($sum == "00000000000000000000000")
		$sum[1]="1";
	//echo $sum; 
	//die;
	$comp = 2+intval($NumberMantissaLength[$format]); 
	if (strlen($sum)<$comp){
		$sum="0".$sum; 
	}
	//echo "<h3>".$sum."</h3>";
	if ($sum[0] == "0" && $sum[1] == "0"){
		$p = bindec($p2);
		while($sum[1]!="1"){
			$last = substr($str,-1,1);
				if ($last!="A")
					$last = "0";
			$sum = substr($sum,1).$last;
			//echo $sum; die;
			$p--;
			if($p == 0) $err = 1;
		}	
		$p1 = substr($nulls,0,$NumberExponentLength[$format] - strlen(decbin($p))).decbin($p);
	} else if ($sum[0] == "1"){
		$rb = "00"; $p = bindec($p1);
		while ($sum[0]=="1"){
			$rb = substr($sum,-1,1).$rb[0];
			$sum = "0".substr($sum,0,strlen($sum)-1);
			$p++; 
			//echo $sum."<br>";
			if ($p>2*$NumberOffset[$format]+1) $err = 1;
			//echo "<h3>".$sum."</h3>";
			//echo "<h3>".$rb."</h3>";
			$len = strlen($sum);
			$sum = $this->RoundMantissa(substr($sum,1),$rb);
			if ( strlen($sum) < $len ) $sum = "0".$sum;
			//echo "<h3>".$sum."</h3>";
			//echo $sum;
		}
		
		$p1 = substr($nulls,0,$NumberExponentLength[$format] - strlen(decbin($p))).decbin($p);
	}
	//echo "P ".$p1." M ".substr($sum,1,$NumberMantissaLength[$format])."<br>";

	
	return array("poryadok"=>$p1, "mantissa"=>substr($sum,2,$NumberMantissaLength[$format]),"format"=>$format,"error"=>$err,"sign"=>$realsign);
	}
	
	public function Compare($a,$b){
		if ( bindec($a) > bindec($b) ) return 0; 
			else if ( bindec($a) < bindec($b) ) return 1;
		else return 2;
	}
	
	public function TetraToDec($poryadok, $mantissa){
	//include "class.parse.php"; 
	//echo strlen($poryadok);
	//echo $poryadok." ".$mantissa; $snum = 0;
		if (strlen($poryadok)==8){
			$exp = 127; $snum = 10; $exact = 38;
		} else { 
			$exp = 1023; $snum = 25; $exact = 308;
		}
		$A = intval(bindec($poryadok)) - $exp; //echo $A;
		if($A>=0)
			$A_2 =  gmp_strval(gmp_pow(2,$A));
		else {
			$A_2 = bcdiv("1", gmp_strval(gmp_pow(2,abs($A))),$exact);
		}
		
		$M = number_format(bindec($mantissa),0,'',''); 
		$B = gmp_strval(gmp_pow(2,strlen($mantissa)));
		$Nominator = bcadd(bcmul($A_2,$B,$exact), bcmul($A_2,$M,$exact),$exact);
		$res = bcdiv($Nominator,$B,$exact); 
		$value = substr($res, 0, strpos($res,".")); 
		$drob = substr($res, strpos($res,".")+1);  //echo "<h6>".$i."</h6>";
		$i=0;
		while ($drob[$i]=="0"){
				$i++;
		}
		if ($i==$exact) 
			return $value;
		else return $value= $value.".".substr($drob,0,$i+$snum);
		
	}
	
	
	public function Multiply($m1,$p1,$m2,$p2,$format){
		require "vars.php";
		//echo $format;
		$nulls = "0000000000000000";
		$inc=0; $err=0;
		//echo $NumberExponentLength[$format];
		$addpor = $this->AddPorr($p1,$p2,$NumberExponentLength[$format]);
		$err = $addpor[1];
		$this->First = "1".$m1;  $this->Second = "1".$m2;
		//echo "<br>".$this->First."<br>"; 
		//echo $this->Second."<br>"; 
		$mantissaRes = $this->TetraMul();
		//echo "<h3>".$this->First."</h3>";
		$len_pl_2 = 2*$NumberMantissaLength[$format]+2;
		//echo "<h3>".$mantissaRes."</h3><br>";
		if (strlen($mantissaRes)< $len_pl_2){ //echo strlen($mantissaRes)."yeah";
			$diff = 2*$NumberMantissaLength[$format]+2-strlen($mantissaRes);// echo $diff;
			$mantissaRes = substr($nulls,0,$diff).$mantissaRes;
		}
		$cycle = true;
		//echo "<h3>".$mantissaRes."</h3><br>";
		if ($mantissaRes[0]=="1"){
		$el = 1; $inc++;} else $el = 2;
		//echo "yes";
		//	$mantissa = substr($mantissaRes,2,$NumberMantissaLength[$format]);
			//echo $mantissa;
			//$roundBits = substr($mantissaRes,$NumberMantissaLength[$format]+2,2);
			//echo $roundBits;
		//	$mantissa = $this->RoundMantissa("1".$mantissa, $roundBits);
			//$inc++; //echo strlen($mantissa);
			
			/*while(strlen($mantissa)>$NumberExponentLength[$format]+1){
			/	$roundBits = $mantissa[strlen($mantissa)-1]."0";
				$mantissa = substr($mantissa,1,$NumberMantissaLength[$format]);
				$mantissa = $this->RoundMantissa("1".$mantissa, $roundBits);
				$inc++;
			}*/
		//	$mantissa = substr($mantissa,2,$NumberMantissaLength[$format]);
		while($cycle) 
		{
			$result = $this->Round($el,$mantissaRes,$format,$nulls,$inc);
			$cycle = $result[0];
			$mantissaRes = $result[1];
			$inc = $result[2];
		}
		$mantissa = substr($mantissaRes,2,$NumberMantissaLength[$format]);
		//echo "<h3>".$mantissa."</h3><br>";
		$poryadok = $this->SummarizeOfInc($addpor[0],$inc, $NumberOffset[$format]+1, $NumberExponentLength[$format]);
		$err = $err | $poryadok[1];
		//echo "P ".$poryadok[0]." M ".$mantissa."<br>";
		return array("poryadok"=>$poryadok[0], "mantissa"=>$mantissa, "error"=>$err,"format"=>$format);
	}
	
	public function Round($el,$mantissa,$format,$nulls,$inc){
		require "vars.php";
		$roundBits = substr($mantissa,$NumberMantissaLength[$format]+$el,2);
		$mantissa = substr($mantissa,$el,$NumberMantissaLength[$format]);
		$mantissa = $this->RoundMantissa("1".$mantissa, $roundBits);
		if (strlen($mantissa)<$NumberMantissaLength[$format]+2)
			{
				$diff = $NumberMantissaLength[$format]+2-strlen($mantissa);// echo $diff;
				$mantissa = substr($nulls,0,$diff).$mantissa;
			}
		if ($mantissa[0]=="1"){
			$inc++;
			$mantissa = $mantissa."00";
		} else $cycle = false;	
		return array(0=>$cycle, 1=>$mantissa, 2=>$inc);
	}
	
	public function SummarizeOfInc($por, $inc, $off,$num){
	//echo intval(bindec($por))." ".$inc." ".$off;
		$bin = decbin(intval(bindec($por))+$inc+$off); //echo $bin;
		$bin = substr("000000000000",0,$num - strlen($bin)+1).$bin;
		$of = $this->OF($bin[0],$bin[1]);
		return array(0=>$this->FirstBitInvert(substr($bin,1,strlen($bin)-1)),1=>$of);
	}
	
	public function RoundMantissa($mantissa, $roundBits){
	//echo $mantissa;
		if ($roundBits=="00" || $roundBits=="0A" || $roundBits=="0M" || $roundBits=="A0" || $roundBits=="A1" || $roundBits=="AM" || $roundBits=="AA")
			return $mantissa;
		if ($roundBits=="01" || $roundBits=="10" || $roundBits=="1A" || $roundBits=="M0" || $roundBits=="M1" || $roundBits=="MM" || $roundBits=="MA"){
			$this->First = $mantissa; $this->Second = "M"; $this->Buf="0";
			$mantissa = $this->TetraSum(); ///echo $mantissa;
		}
		if ($roundBits=="1M" || $roundBits=="11"){
			$this->First = $mantissa; $this->Second = "1";
			$mantissa = $this->TetraSum();
		}
		//echo $mantissa;
		return $mantissa;
	}
	
	public function DefineOperation($str){
		if(preg_match("/\+/", $str)) 
			return "+"; 
		if(preg_match("/\-/", $str)) 
			return "-"; 
		if(preg_match("/\*/", $str)) 
			return "*"; 
		if(preg_match("/\//", $str)) 
			return "/"; 
	}
	

	
	private function CorrectLength(){
	if (strlen($this->First)!=strlen($this->Second))
		{ 
			if (strlen($this->First)<strlen($this->Second))
			{ 
				$this->First = substr($this->zeros,0,strlen($this->Second)-strlen($this->First)).$this->First;
			} else
			{
				$this->Second = substr($this->zeros,0,strlen($this->First)-strlen($this->Second)).$this->Second;
			}
		}
	}
	
	private $Tres;
	private $Result;
	private $Tarr;
	private $First;
	private $Second;
	private $Buf;
	private $zeros = "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
}
?>