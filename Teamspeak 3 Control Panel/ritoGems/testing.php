<?php
include('php-riot-api.php');
include('FileSystemCache.php');

$summoner_name = "My Team Hates Me"; 
$summoner_id = 24720258;

$test = new riotapi('na');


$testCache = new riotapi('na', new FileSystemCache('cache/'));

try {
	$r = $test->getSummonerByName($summoner_name);
//	print_r($r);
	$r = $test->getLeague($summoner_id,"entry");
//	echo '<br><br>';
//	print_r($r);
//	echo '<br><br><br>';
	echo 'Rank: '.$r[$summoner_id][0]['tier'].' ';
	echo $r[$summoner_id][0]['entries'][0]['division'];
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
};

?>