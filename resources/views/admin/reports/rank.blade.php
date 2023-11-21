@php
$numbers = array( 101, 201, 301, 301, 401, 401, 401, 501, 501, 501, 501);
rsort($numbers);

$arrlength = count($numbers);
$rank = 1;
$prev_rank = $rank;

for($x = 0; $x < $arrlength; $x++) {

    if ($x==0) {
         echo $numbers[$x]."- Rank".($rank);
    }

   elseif ($numbers[$x] != $numbers[$x-1]) {
        $rank++;
        $prev_rank = $rank;
        echo $numbers[$x]."- Rank".($rank);
   }

   else{
        $rank++;
        echo $numbers[$x]."- Rank".($prev_rank);
    }

   echo "<br>";
}

@endphp
