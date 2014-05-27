<?php
require_once "jarowinkler.php";

function mongeElkanJaroWinkler($str1, $str2) {
	$clean_str1 = array();
	$clean_str2 = array();
	preg_match_all('/\S+/', $str1, $token1);
	preg_match_all('/\S+/', $str2, $token2);
	$score = 0;

	foreach ($token1[0] as $key => $value) {
		$clean_str1[] = preg_replace('/[^a-zA-Z0-9]+/', '', $value);
	}

	foreach ($token2[0] as $key => $value) {
		$clean_str2[] = preg_replace('/[^a-zA-Z0-9]+/', '', $value);
	}

	$token1 = array_filter($clean_str1);
	$token2 = array_filter($clean_str2);

	foreach ($token1 as $k => $v) {
		foreach ($token2 as $key => $value) {
			if (jarowinkler($v, $value) > 0.85) {
				$score++;
			}
		}
	}

	return $score / ((count($token1) + count($token2))/2);
}