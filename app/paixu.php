<?php

$arr = [2, 56, 66 ,3, 3, 4, 78, 4, 7, 87, 1];

$count = count($arr);

for ($i = 0; $i < $count; $i++) {
    for ($j = 0; $j < $count - $i - 1; $j++) {
        if ($arr[$j] > $arr[$j + 1]) {
            $temp = $arr[$j];
            $arr[$j] = $arr[$j + 1];
            $arr[$j + 1] = $temp;
        }
    }
}

print_r($arr);

function find(&$arr, $value)
{
    $left = 0;
    $right = count($arr) - 1;

    while ($left <= $right) {
        $middle = floor(($left + $right) / 2);
        if ($value > $arr[$middle]) {
            $left = $middle + 1;
        } elseif ($value < $arr[$middle]) {
            $right = $middle - 1;
        } else {
            return $middle;
        }
    }
    return null;
}
$result = find($arr, 6);
var_dump($result);
