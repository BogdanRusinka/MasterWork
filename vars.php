<?php 

global $NumberOffset;
$NumberOffset = array(
                "PB32" => 127,
                "PB64" => 1023
            );

global $NumberExponentLength;
$NumberExponentLength = array(
                "PB32" => 8,
                "PB64" => 11
            );

global $NumberMantissaLength;
$NumberMantissaLength = array(
                "PB32" => 21,
                "PB64" => 48
            );
global $CF;
$CF = array(
				"PB32" => "011",
                "PB64" => "0111"
            );global $CF;
global $MF;
$MF = array(
				"PB32" => "00100",
                "PB64" => "000000000100"
            );
?>