<?php if (isset($_POST["vark"]))
        {
          //var_dump($_POST);
          $counter=0;
            foreach($_POST as $key=>$value) 
              if(strlen($value)==0) 
                $counter++;
          if ($counter==0){
          $massiv = array();
          foreach ($_POST as $var => $value) 
          {
            array_push($massiv, $var);
          }
          
          for ($i=1; $i < count($massiv)-1;$i+=2)
             {  $f = $massiv[$i];
                $s = $massiv[$i+1];
              // var_dump($_POST[$s]);
              echo "<br>Исходное число: <br><table><tr><td><div class='dash'>".$_POST[$f]."</div></td></tr><tr><td>".$_POST[$s]."</td></tr></table><br>";
               $first = getBinString(ConvertFromExp($_POST[$f]),100);
               $sign_f = $first[0];
               $first = format($first);
               $second = getBinString(ConvertFromExp($_POST[$s]),100);
               $sign_s = $second[0];
               $second = format($second);
               $sign = "+";
               if ($sign_f != $sign_s)
                $sign = "1";
              else 
                $sign = "0";

              echo "<div class='number' style='overflow: auto; white-space: nowrap'><table><tr align='center'><td class = 'sign'>S<td class='poryadok'>E</td><td class='mantissa'>M</td><td class='poryadok'>E</td><td class='mantissa'>M</td></tr><tr><td class = 'sign'>".$sign."</td>".$first.$second."</tr></table></div>";
           }
           var_dump($stack);
           foreach ($stack as $key => $value) {
             echo $value."<br>";
           }
         } else echo "<div class='error'>Поле с числом пустое</div>";
        } 

        ?>