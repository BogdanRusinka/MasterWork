<?php
class tetra {

	public function __construct ($number=""){
		//$this->Number = $number;
	}
	
	public function getBinString($str,$format){
	require "vars.php";
	//echo $str."<br>";
		$StrInputBeginNumber = $str;
		$Sign = $StrInputBeginNumber[0]; //echo $Sign."<br>";
		$StrInputBeginNumber = substr($StrInputBeginNumber, 1, strlen($StrInputBeginNumber)- 1);
		$PositionComma = strpos($StrInputBeginNumber, ".");
		if ($PositionComma == "")                                    // xxxxx -> xxxxx,0
		{
		  $StrInputBeginNumber = $StrInputBeginNumber.".0";
		  $PositionComma = strpos($StrInputBeginNumber, ".");
		}
		$IntegerPart = substr($StrInputBeginNumber, 0, $PositionComma);
		//echo $IntegerPart."<br>";
		$FloatPart = substr($StrInputBeginNumber, $PositionComma+1, strlen($StrInputBeginNumber)- $PositionComma - 1);
		//echo $FloatPart."<br>";
		$IntegerPart = $this->BigDigToBinStr($IntegerPart);
		//echo "IntegerPart ".$IntegerPart."<br>";
		$bool = false;
		if ($IntegerPart == "0")
			$bool=true;
		//echo $FloatPart."<br>";
		$FloatPart = $this->BigDigToBinFrac($FloatPart, !$bool,$NumberMantissaLength[$format]+3);
		//echo "<h3>".$FloatPart."</h3>";
		$ResultStrBeginNumber = $Sign.$IntegerPart.".".$FloatPart;
		if (strlen($ResultStrBeginNumber)<255)
		for ($i=0; $i<255-strlen($ResultStrBeginNumber); $i++)
			$ResultStrBeginNumber.="0";
		//echo "return : ".$ResultStrBeginNumber."stop";
		return $ResultStrBeginNumber;
	}


public function BigDigToBinStr($strdec){
//echo $strdec;
    $str = "";
	$strdec = ltrim(gmp_strval($strdec),'0');
	//echo $strdec."<br>";
	if (gmp_cmp($strdec, "0")==0)
		return "0";
    while (gmp_cmp($strdec, "0")>=1)
    {
      $mod=gmp_mod($strdec,"2");
      $str = gmp_strval($mod).$str; 
      $strdec = gmp_div($strdec,"2");
    }
	//var_dump($str)."<br>";
    return $str;
}

public function BigDigToBinFrac($strFrac, $intPartNoZero,$formatLength){

  // echo "input : ".$strFrac."<br>";
   //var_dump($intPartNoZero)."<br>";
   $str = "";
   $flen = strlen($strFrac);
   //echo $flen;
   $n = 0; $exp = 0;
   $yesOne = false;
   if ($strFrac == "0") return "0";
   $strFrac = ltrim($strFrac,'0');
   //echo "<br>".$strFrac."<br>";
   $end = "";
            while ($end == "")
            {	//echo gmp_strval($strFrac)."<br>";
                $strFrac = gmp_mul($strFrac, "2");
                if ($flen < strlen(gmp_strval($strFrac)))
                {
                    $str .=  "1";
                    $strFrac = gmp_sub($strFrac, gmp_pow("10", $flen));
                    $yesOne = true;
                }
                else
                {
                    $str .= "0";
				//	$exp++; 
				//	if (!$yesOne && $exp==127)
				//		{echo "suck"; $end = "END"; return "ERROR";}
                }
				if($yesOne)
				{
					$NumOfPos++;
					if ($NumOfPos==$formatLength)
						$end="END";
				}
				//echo gmp_strval($strFrac)."<br>";
				
            }
		//	echo "<br>==".$str."<br>";
		//echo $exp;
            return $str;
}


public function BinToBigDig($input){
    $dig = gmp_init("0");
    $len = strlen($input);
    for ($i = 0; $i < $len; $i++)
        $dig = gmp_add($dig, gmp_mul(gmp_pow(2, $len - 1 - $i), gmp_init(substr($input, $i, 1))));
    return $dig;
}

public function format($number, $format) {
	require "vars.php"; //echo "NUMBER: ".$number."<br>";echo "STRPOS: ".strpos($number,'1')."<br>";	
	$Zero=false; //echo strpos($number,"1"); 
	if (strpos($number,"1")=="")
		{ 
			$Zero=true;
			$exp = -$NumberOffset[$format];
		} else
	$exp = strpos($number,".") - strpos($number,"1");//echo "EXP".$exp."<br>";
	$number = str_replace(".", "", $number);//$number = substr_replace($number, ".", strpos($number,"1")+1, 0);//   echo $number;
	if ($exp>0) $exp--;//if ($exp<=-127) return "OVERFLOW";
	$poryadok_dec = $NumberOffset[$format] + $exp;//echo "<br><em>".$poryadok_dec."<em><br>";
	if ($poryadok_dec<0) { //echo "uuu";
		$result["AI"]="U";
		$result["poryadok"] = "00000000000";
		$result["mantissa"] = "000000000000000000000000000000000000000000000000";
		return $result;
	}
	$poryadok_bin = $this->BigDigToBinStr($poryadok_dec); //echo $poryadok_bin."<br>";
	if ($NumberExponentLength[$format]<strlen($poryadok_bin))
		{	$result["AI"]="O";
			$result["poryadok"] = "11111111111";
			$result["mantissa"] = "000000000000000000000000000000000000000000000000";
			return $result;
		
		}//var_dump($poryadok_bin);//echo strlen($poryadok_bin);
	if (strlen($poryadok_bin) < $NumberExponentLength[$format])
	{
	$nulls = strlen($poryadok_bin);
	  for ($i = 0; $i < $NumberExponentLength[$format] - $nulls; $i++)
	  {//echo $i."<br>";// echo $res = 11 - strlen($poryadok_bin);
		$poryadok_bin = "0".$poryadok_bin;//echo $poryadok_bin."<br>";
	  }
	} //echo $poryadok_bin."<br>";
	$result = array("poryadok" => $poryadok_bin);//left_part.poryadok = poryadok_bin;
	//echo $number;
	$mantissa = substr($number, strpos($number,"1")+1,$NumberMantissaLength[$format]);//echo "<em>".$mantissa."</em><br>";
	$two_bits = substr(substr($number, strpos($number,"1")+1,$NumberMantissaLength[$format]+2), -2,2);//echo "<br><em>".$two_bits."</em><br>";
	$res = $this->RoundMantissa($mantissa, $two_bits,$format);
	$result["mantissa"] = $res[0]; //echo $result["mantissa"];
	if ($res[1])
		$result["poryadok"] = $this->IncPoryadok($result["poryadok"],$format);
		if (!$Zero)
			$result["AI"] = $this->CheckForExceptions($result);
		else 
			$result["AI"] = "Z";
return $result;
}

public function CheckForExceptions($input){ //echo "3456789";
	$m0 = $this->checkForAllNullsMant($input["mantissa"]); //var_dump($m0);
	$p0 = $this->checkForAllNullsExp($input["poryadok"]); //var_dump($p0);
	$m1 = $this->checkForAllOnesMant($input["mantissa"]); //var_dump($m1);
	$p1 = $this->checkForAllOnesExp($input["poryadok"]); //var_dump($p1);
	if	($m0 && $p0)
		return "Z";
	if (!$m0 && $p0)
		return "U";
	if ($p1 && $m0)
		return "Inf";
	if (!$m0 && $p1)
		return "NaN"; 
	if (!$p0 && !$p1)
		return "N";

}

public function RoundMantissa($mantissa, $two_bits, $format){ //echo "<h3>".$two_bits."</h3>";
require "vars.php";
if ($two_bits == "00"){
 return array(0=>$mantissa,1=>0);
}
else if ($two_bits == "11")
 { $inc=0;//\echo "<h3>".strlen($mantissa)."</h3>"; 
  $decimal = $this->BinToBigDig("1".$mantissa);//echo "<h3>".gmp_strval($decimal)."</h3>";
  $decimal = gmp_add($decimal,"1");// echo "<h3>".gmp_strval($decimal)."</h3>";
  $mantissa = $this->BigDigToBinStr($decimal);
  //echo "<h3>".$mantissa."</h3>";
  //echo strlen($mantissa);
  //echo $NumberMantissaLength[$format];
  if (strlen($mantissa)>$NumberMantissaLength[$format]+1)
	{ //echo $NumberMantissaLength[$format];
		$mantissa = substr($mantissa,1,strlen($mantissa) - 1);
		$inc=1;
	} else
	if (strlen($mantissa) == $NumberMantissaLength[$format] + 1)
	{ //echo "dfghj";
		$mantissa = substr($mantissa,1,strlen($mantissa) - 1);
	}
  $nulls = strlen($mantissa);
  for ($i=0; $i<$NumberMantissaLength[$format]-$nulls;$i++)
	$mantissa = "0".$mantissa;//echo $mantissa;
  return $a=array(0=>$mantissa,1=>$inc);
 }
  else if ($two_bits == "10" || "01")
            {   //echo $mantissa."<br>";
				$allOnes = $this->checkForAllOnesMant($mantissa);
				//var_dump($allOnes);
				if ($allOnes)
				{ 
					$mantissa = $this->IncMantissa("1".$mantissa, $format);
					 return $a=array(0=>$mantissa[0],1=>$mantissa[1]);
				}
                $cut = substr($mantissa, 0, strrpos($mantissa, "0"));//echo "<br><br>".$mantissa."<br><br>".$cut."<br><br>";
                $cut.="M";//echo $cut;
                $len = strlen($cut);//echo strlen($mantissa);
                for ($i=0; $i<strlen($mantissa)-$len;$i++)
                  $cut.="A";//echo $cut;
                return $a=array(0=>$cut,1=>0);//var_dump($result);
            }
}

public function IncPoryadok($poryadok_bin, $format){
require "vars.php";
$dec_poryadok = $this->BinToBigDig($poryadok_bin);//echo "<h3>".gmp_strval($dec_poryadok)."</h3>";
		$dec_poryadok = gmp_add($dec_poryadok,"1");//echo "<h3>".gmp_strval($dec_poryadok)."</h3>";
		$poryadok_bin = $this->BigDigToBinStr($dec_poryadok); //echo "POR ".$poryadok_bin."<br>";
		$nulls = strlen($poryadok_bin);//echo $poryadok_bin;
		for ($i = 0; $i < $NumberExponentLength[$format] - $nulls; $i++)
		  { //echo $i."<br>";// echo $res = 11 - strlen($poryadok_bin);
			$poryadok_bin = "0".$poryadok_bin;//echo $poryadok_bin."<br>";
		  }
		return $poryadok_bin;
}

public function IncMantissa($mantissa, $format){
//echo $mantissa;
require "vars.php"; $inc=0;
$dec_mantissa = $this->BinToBigDig($mantissa);//echo "<h3>".gmp_strval($dec_poryadok)."</h3>";
		$dec_mantissa = gmp_add($dec_mantissa,"1");//echo "<h3>".gmp_strval($dec_poryadok)."</h3>";
		$mantissa = $this->BigDigToBinStr($dec_mantissa); //echo "POR ".$mantissa."<br>";
		$nulls = strlen($mantissa);//echo $mantissa;
		if (strlen($mantissa) > $NumberMantissaLength[$format] + 1)
	{ $inc=1;
		$mantissa = substr($mantissa,1,strlen($mantissa) - 1);
	} else
	if (strlen($mantissa) == $NumberMantissaLength[$format] + 1)
	{ 
		$mantissa = substr($mantissa,1,strlen($mantissa) - 1);
	}
			for ($i = 0; $i < $NumberMantissaLength[$format] - $nulls; $i++)
		  { //echo $i."<br>";// echo $res = 11 - strlen($mantissa);
			$mantissa = "0".$mantissa;//echo $mantissa."<br>";
		  }
		return array(0=>$mantissa,1=>$inc);
}

public function checkForAllOnesMant($str){
	if ($str==="111111111111111111111" || $str==="111111111111111111111111111111111111111111111111")
		{
			return true;
		}
	else return false;
}

public function checkForAllNullsMant($str){
	if ($str==="000000000000000000000" || $str==="000000000000000000000000000000000000000000000000")
		return true;
	else return false;
}

public function checkForAllOnesExp($str){
	if ($str==="11111111" || $str==="11111111111")
		return true;
	else return false;
}

public function checkForAllNullsExp($str){
	if ($str==="00000000" || $str==="00000000000")
		return true;
	else return false;
}

public function ConvertFromExp($str) {//echo $str."<br>";
if (($str[0]!="+") && ($str[0]!="-"))
	$str = "+".$str;
$ePos = strpos($str, "e"); //echo $ePos."<br>";
if($ePos==""){
	if (strpos($str, ".")=="")
		$str = $str.".0";
	return $str; 
}
$sign = $str[0];
$eLeft = substr($str,1, $ePos-1); //echo $eLeft."<br> RIght ";
$eRight = substr($str, $ePos+2); //echo $eRight."<br>";
if ($eLeft[strlen($eLeft)-1]==".")
	$str = $eLeft."0".substr($str, $ePos);
if ($eLeft[0]==".")
	$str = "0".$str;
if (strpos($eLeft, ".")=="")
	{
	$str = $eLeft.".0".substr($str, $ePos);
	$eLeft=$eLeft.".0";
	}
$ePos = strpos($str, "e");
if ($str[$ePos+1] == '+')
 {
  $nullNumbers = (int)$eRight - (strlen($eLeft)- strpos($eLeft,".")-1);
  if ($nullNumbers<0)
  {
    $eLeft = str_replace(".", "", $eLeft);
    $eLeft = substr($eLeft, 0, strlen($eLeft)-abs($nullNumbers)).".".substr($eLeft, strlen($eLeft)-abs($nullNumbers));
	$eLeft = ltrim($eLeft,'0');
	if ($eLeft[0]==".") $eLeft = "0".$eLeft;
	return $sign.$eLeft;
  }
  else if ($nullNumbers==0)
	{
	$eLeft = str_replace(".", "", $eLeft);
	return $sign.$eLeft;
	} else
    {
	$eLeft = str_replace(".", "", $eLeft);
	for ($i = 0; $i < $nullNumbers; $i++)
		{
	         $eLeft = $eLeft."0";
		}
	$eLeft = ltrim($eLeft,'0');
	if ($eLeft[0]==".") $eLeft = "0".$eLeft;
	return $sign."".$eLeft;
    }
  }
            else
            {
               $nullNumbers = (int)$eRight - strpos($eLeft,"."); //echo $nullNumbers;
               if ($nullNumbers<0)
				{
					$eLeft = str_replace(".", "", $eLeft);
					$eLeft = substr($eLeft, 0, abs($nullNumbers)).".".substr($eLeft, abs($nullNumbers));
					return $sign.$eLeft;
				}  else if ($nullNumbers==0)
				{
					$eLeft = str_replace(".", "", $eLeft);
					return $sign."0.".$eLeft;
				} else 
				{
					$eLeft = str_replace(".", "", $eLeft);
					for ($i = 0; $i < $nullNumbers; $i++)
					{
					 $eLeft = "0".$eLeft;
					}
					return $sign."0.".$eLeft;
				}
			} 
}
}
?>