<?php
//find matches
set_time_limit(0);
ini_set('memory_limit','3048M');
require_once "Database.php";
require_once "DBConfig.php";
require_once "jaro.php";
require_once "jarowinkler.php";
require_once "mongeelkan.php";
require_once "mongeelkan_jarowinkler.php";
require_once "mongeelkanmodified.php";
$config = new \Model\DBConfig();
$db = new \Model\Database();
$conn = $db->connect($config);

try {
	$stmt = $conn->prepare("SELECT name, store_id, id
								FROM product");
	$stmt->execute();
	
	$result = $stmt -> fetchAll(\PDO::FETCH_ASSOC);
	
	} catch(\PDOException $e) {
		echo $e->getMessage();
}

for ($i=0; $i < count($result); $i++) { 
	$compared = array();
	for ($j=0; $j < count($result); $j++) { 

		if ($result[$i]['store_id'] != $result[$j]['store_id']) {
			$test = array();
			$test['comp'] = $result[$i]['id'];
			$test['comparee'] = $result[$j]['id'];
			$test[1] = levenshtein($result[$i]['name'], $result[$j]['name']);
			$test[2] = jaro($result[$i]['name'], $result[$j]['name']);
			$test[3] = jarowinkler($result[$i]['name'], $result[$j]['name']);
			$test[4] = mongeElkan($result[$i]['name'], $result[$j]['name']);
			$test[5] = mongeElkanJaroWinkler($result[$i]['name'], $result[$j]['name']);

			$compared[] = $test;
		}
	}
	usort($compared, function($a, $b) {
		if($a[1]==$b[1]) return 0;
		return $a[1] > $b[1]?1:-1;
	});
	$query = $conn->prepare('INSERT INTO prod_algo (prod1_id, prod2_id, algo_id, score) 
							VALUES (:prod1_id, :prod2_id, :algo_id, :score)'); 

	$query->execute(array(	':prod1_id' => $compared[0]['comp'],
							':prod2_id' => $compared[0]['comparee'],
							':algo_id' => 1,
							':score' => $compared[0][1]
							));
	
	usort($compared, function($a, $b) {
		if($a[2]==$b[2]) return 0;
		return $a[2] < $b[2]?1:-1;
	});

	$query = $conn->prepare('INSERT INTO prod_algo (prod1_id, prod2_id, algo_id, score) 
							VALUES (:prod1_id, :prod2_id, :algo_id, :score)'); 

	$query->execute(array(	':prod1_id' => $compared[0]['comp'],
							':prod2_id' => $compared[0]['comparee'],
							':algo_id' => 2,
							':score' => $compared[0][2]
							));

	usort($compared, function($a, $b) {
		if($a[3]==$b[3]) return 0;
		return $a[3] < $b[3]?1:-1;
	});

	$query = $conn->prepare('INSERT INTO prod_algo (prod1_id, prod2_id, algo_id, score) 
							VALUES (:prod1_id, :prod2_id, :algo_id, :score)'); 

	$query->execute(array(	':prod1_id' => $compared[0]['comp'],
							':prod2_id' => $compared[0]['comparee'],
							':algo_id' => 3,
							':score' => $compared[0][3]
							));
	
	usort($compared, function($a, $b) {
		if($a[4]==$b[4]) return 0;
		return $a[4] < $b[4]?1:-1;
	});

	$query = $conn->prepare('INSERT INTO prod_algo (prod1_id, prod2_id, algo_id, score) 
							VALUES (:prod1_id, :prod2_id, :algo_id, :score)'); 

	$query->execute(array(	':prod1_id' => $compared[0]['comp'],
							':prod2_id' => $compared[0]['comparee'],
							':algo_id' => 4,
							':score' => $compared[0][4]
							));

	usort($compared, function($a, $b) {
		if($a[5]==$b[5]) return 0;
		return $a[5] < $b[5]?1:-1;
	});

	$query = $conn->prepare('INSERT INTO prod_algo (prod1_id, prod2_id, algo_id, score) 
							VALUES (:prod1_id, :prod2_id, :algo_id, :score)'); 

	$query->execute(array(	':prod1_id' => $compared[0]['comp'],
							':prod2_id' => $compared[0]['comparee'],
							':algo_id' => 5,
							':score' => $compared[0][5]
							));

}
$db->disconnect($conn);
echo "dun";
