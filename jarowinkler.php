<?php
/**
 * This is a php implementation of the Jaro-Winkler distance algorithm
 * based on the strsmp95.c found here:
 * http://web.archive.org/web/20100227020019/http://www.census.gov/geo/msb/stand/strcmp.c
 * This only implements the algorithm but dosen't include the common
 * misspelling array or the options found in the orginal implementation.
 */

function jarowinkler($str1, $str2) {

	$str1 = strtolower(trim($str1));
	$str2 = strtolower(trim($str2));
	$str1_length = strlen($str1);
	$str2_length = strlen($str2);

	if ($str1_length <= 0 || $str2_length <= 0) {
		return 0.0;
	}

	if ($str1_length > $str2_length) {
		$search_range = $str1_length;
		$minv = $str2_length;
	} else {
		$search_range = $str2_length;
		$minv = $str1_length;
	}
	$str1_hold = str_split($str1);
	$str2_hold = str_split($str2);

	$str1_flag = array_fill(0, $search_range, '');
	$str2_flag = array_fill(0, $search_range, '');
	$search_range = floor(($search_range/2) - 1);

	$number_common = 0;
	$sl2 = $str2_length - 1;
	for ($i = 0;$i < $str1_length;$i++) {
		$lowlim = ($i >= $search_range) ? $i - $search_range : 0;
		$hilim = (($i + $search_range) <= $sl2) ? ($i + $search_range) : $sl2;
		for ($j = $lowlim;$j <= $hilim;$j++) {
			if (($str2_flag[$j] != '1') && ($str2_hold[$j] === $str1_hold[$i])) {
				$str2_flag[$j] = '1';
				$str1_flag[$i] = '1';
				$number_common++;
				break;
			}
		}
	}
	if ($number_common == 0) {
		return(0.0);
	}   

	$k = $number_transposition = 0;
	for ($i = 0;$i < $str1_length;$i++) {
		if ($str1_flag[$i] == '1') {
			for ($j = $k;$j < $str2_length;$j++) {
				if ($str2_flag[$j] == '1') {
					$k = $j + 1;
					break;
				}
			}
			if ($str1_hold[$i] != $str2_hold[$j]) {
				$number_transposition++;
			}
		}
	}
	$number_transposition = $number_transposition / 2;

	$weight = (($number_common/$str1_length) +
				($number_common/$str2_length) +
				(($number_common-$number_transposition)/$number_common)) / 3;

	if ($weight > 0.7) {
		$j = ($minv >= 4) ? 4 : $minv;
		for ($i=0;(($i<$j)&&($str1_hold[$i]==$str2_hold[$i])&&(!is_numeric($str1_hold[$i])));$i++);
		if ($i) $weight += $i * 0.1 * (1.0 - $weight);
	}
	return $weight;
}