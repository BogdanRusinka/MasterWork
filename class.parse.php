<?php

class parse 
{ 	
	public function __construct ($formula=""){
		$this->Formula = $formula;
		$this->Stack = array();
	}
	
	public function variables(){
	//	require "db.php";
		$connection = new MongoClient();
		$db = $connection->Master;
		$response = $db->Variables->drop();
		$variables = $db->Variables;
		preg_match_all("/[A-Z]/", $this->Formula, $matches);
		$keywords = array();
		$matches[0] = array_unique($matches[0]);
		foreach ($matches[0] as $match)
		{
		//	$mysqli->query("INSERT INTO `variables` (`variable`) VALUES ('$match')");
			$insert = array ("variable"=>$match);
			$variables->insert($insert);
			array_push($keywords,$match);
		}
		return $keywords;
	}
	
	public function  expFind() { //echo "hi";
	//	require "db.php";
		preg_match_all("/[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)?/",$this->Formula, $matches);
		$i=0;
		foreach ($matches[0] as $match)
		{  
			$real = $match;
			$this->Formula = substr_replace($this->Formula, "VAR".$i, strpos($this->Formula, $match), strlen($match)); 
			$match = $this->makePatternExp($match); 
			$match = $this->ExpToNum($match);
			$connection = new MongoClient();
			$db = $connection->Master;
			$variables = $db->Variables;
		//	if (!$mysqli->query("INSERT INTO `variables` (`variable`, `lpart`,`lpartreal`, `rpart`,`rpartreal`) VALUES ('VAR$i', '$match', '$real', '+1.0', '+1.0')"))
			// echo "Trouble with DB ".$mysqli->errno;
			$insert = array ("variable"=>'VAR'.$i,"lpart"=>$match,"lpartreal"=>$real,"rpart"=>"+1.0","rpartreal"=>"+1.0");
			$variables->insert($insert);
			$i++;	
		}
		return $this->Formula;
	}
	
	public function makePatternExp($formula){
		//var_dump($formula);
		for ($i=0; $i<strlen($formula);$i++){
		if (preg_match("/[.]/", $formula[$i]) && (@preg_match("/[-+\\/*]/", $formula[$i-1]) || $i==0)){
			$result = substr($formula, 0, $i)."0".substr($formula, $i);
			$formula = $result;
		}
		  if (preg_match("/[.]/", $formula[$i]) && @preg_match("/[e]/", $formula[$i+1])){
			$result = substr($formula, 0, $i+1)."0".substr($formula, $i+1);
			$formula = $result;
		  }
	  if (preg_match("/[e]/", $formula[$i]) && @preg_match("/[0-9]/", $formula[$i+1])){
		$result = substr($formula, 0, $i+1)."+".substr($formula, $i+1);
		$formula = $result;
	  }
	  if (preg_match("/[e]/", $formula[$i]) && (@preg_match("/[-+\\/*]/", $formula[$i-1])|| $i==0)){
		$result = substr($formula, 0, $i)."1.0".substr($formula, $i);
		$formula = $result;
	  }
	}
	 return $formula;
	}
	
	public function ExpToNum($str) {//echo $str."<br>";
		if (($str[0]!="+") && ($str[0]!="-"))
			$str = "+".$str;
		$ePos = strpos($str, "e"); //echo $ePos."<br>";
		if($ePos==""){ //echo "kl";
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
			   //echo $eLeft;
			   $eLeft = rtrim(str_replace(".", "", $eLeft),'0');
			   //echo $eLeft."<br>";
			  // $eLeft = str_replace($eLeft, substr($eLeft, strpos("."), strpos("e")-strpos(".")), ltrim(substr($eLeft, strpos("."), strpos("e")-strpos(".")),'0'));
               if ($nullNumbers<0)
				{
					$eLeft = substr($eLeft, 0, abs($nullNumbers)).".".substr($eLeft, abs($nullNumbers));
					return $sign.$eLeft;
				}  else if ($nullNumbers==0)
				{

					return $sign."0.".$eLeft;
				} else 
				{
					
					for ($i = 0; $i < $nullNumbers; $i++)
					{
					 $eLeft = "0".$eLeft;
					}
					return $sign."0.".$eLeft;
				}
			} 
		}
		
		
		public function parseFormula($num, $iterator){
		$connection = new MongoClient();
		$db = $connection->Master;
		$stack = $db->Stack;
		 if(preg_match("/\([^()]*\)/", $this->Formula,$match))
			{
				$brackets = $this->makePairsOfNum(substr($match[0],1,-1),$iterator);
				$iterator = $brackets["N"]; unset($brackets["N"]);
				$this->Stack["BR".$num] = array_pop($brackets);
				foreach ($brackets as $key=>$val)
					$this->Stack[$key]=$val; 
				$this->Formula = substr_replace($this->Formula, "BR".$num, strpos($this->Formula, $match[0]),strlen($match[0]));
				$num++;
				return $this->parseFormula($num, $iterator); 
			} else 
			{ 
				$brackets = $this->makePairsOfNum($this->Formula,0);
				unset($brackets["N"]);
				foreach ($brackets as $key=>$val)
					$this->Stack[$key]=$val;
				//require "db.php";
	
				foreach ($this->Stack as $key => $value) {
						//$mysqli->query("INSERT INTO `stack` (`operand`, `operation`) VALUES ('$key', '$value')");
						$insert = array ("operand"=>$key,"operation"=>$value);
						$stack->insert($insert);
				}
		}
		}
		
		private function makePairsOfNum($str, $num){
			$stack = array(); //echo $str;
			while (preg_match("/([A-Z]+[0-9]?)([\*\/])([A-Z]+[0-9]?)/", $str, $match)==1)
			{ //echo "44";
				$position = strpos($str, $match[0]);
				$length = strlen($match[0]);
				$signleft = "";
				if ($str[strpos($str, $match[0])-1] == "+" || $str[strpos($str, $match[0])-1] == "-")
				{
					if ($str[strpos($str, $match[0])-2] == "(" || $str[strpos($str, $match[0])-2] == "")
						{ 
							$position -= 1;
							$length += 1;
							$signleft = $str[strpos($str, $match[0])-1];
						}
				}
				$stack["OP".$num] = $signleft.$match[0];
				$str = substr_replace($str, "OP".$num, $position, $length);
				$num++;
			}
			while (preg_match("/([A-Z]+[0-9]?)([\+\-])([A-Z]+[0-9]?)/", $str, $match)==1)
			{ //echo "44";
				$position = strpos($str, $match[0]);
				$length = strlen($match[0]);
				$signleft = "";
				if ($str[strpos($str, $match[0])-1] == "+" || $str[strpos($str, $match[0])-1] == "-")
				{
					if ($str[strpos($str, $match[0])-2] == "(" || $str[strpos($str, $match[0])-2] == "")
						{ 
							$position -= 1;
							$length += 1;
							$signleft = $str[strpos($str, $match[0])-1];
						}
				}
				$stack["OP".$num] = $signleft.$match[0];
				$str = substr_replace($str, "OP".$num, $position, $length);
				$num++;
			}
			$stack["N"] = $num;
			return $stack;
		}
		
		
		public function numNorm($str){
			if (strpos($str,'.')===false)
				$str = $str.".0";
			if ($str[0]!=("+") && $str[0]!=("-"))
				$str = "+".$str;
			return $str;
		}
		public function getFormula(){
			return $this->Formula;
		}
	
	
}

?>