<?php

function options() {
	$short_opt = "n:hevs";
	$long_opt = array("man", "number:", "hard", "easy", "versus", "second");
	$ret = getopt($short_opt, $long_opt);
	return $ret;
}

function display($matches_number = 15) {
	static $turn = 0;
	$h = 0;
	$v = 0;
	$options = options();
	foreach ($options as $key => $value) {
		if ($key === "n" || $key === "number")
			$matches_number = $value;
		if ($key === "h" || $key === "hard")
			$h = 1;
		if ($key === "v" || $key === "versus")
			$v = 1;
		if (($key === "s" || $key === "second") && $turn === 0)
			$turn += 1;
	}
	static $matches = 0;
	if ($matches === 0)
		$matches = $matches_number;
	$turn++;
	$i = 0;
	$str = "";
	while ($i < $matches) {
		$str .= "|";
		$i++;
	}
	echo $str . "\n";
	if ($v === 1) {
		if ($turn % 2 !== 0)
			get_matches_cpu_pgm($str, $matches);
		else
			get_matches_cpu_dummy($str, $matches);
	}
	elseif ($h === 1) {
		if ($turn % 2 !== 0)
			get_matches_player($str, $matches);
		else
			get_matches_cpu_pgm($str, $matches);
	}
	else {
		if ($turn % 2 !== 0)
			get_matches_player($str, $matches);
		else
			get_matches_cpu_dummy($str, $matches);
	}
}

function get_matches_player($str, &$matches) {
	while ($matches > 0) {
		echo "Retirez une à 3 allumettes\n";
		$line = readline();
		if ($line === "1")
			$matches--;
		elseif ($line === "2")
			$matches -= 2;
		elseif ($line === "3")
			$matches -= 3;
		elseif ($matches > 0) {
			echo "Veuillez entrer 1, 2 ou 3 pour choisir le nombre d'allumettes\n";
		}
		if ($matches > 0 && ($line === "1" || $line === "2" || $line === "3"))
			display($matches);
	}
	if ($matches <= 0) {
		echo "Vous avez tiré la dernière allumette, vous avez perdu!\n";
		die();
	}
}

function get_matches_cpu_dummy($str, &$matches) {
	while ($matches > 0) {
		echo "Tour de l'ordinateur dummy\n";
		$line = strval(rand(1, 3));
		echo "L'ordinateur dummy retire $line allumettes\n";
		if ($line === "1")
			$matches--;
		elseif ($line === "2")
			$matches -= 2;
		elseif ($line === "3")
			$matches -= 3;
		if ($matches > 0)
			display($matches);
	}
	if ($matches <= 0) {
		echo "L'ordinateur dummy a tiré la dernière allumette, il perd la partie!\n";
		die();
	}
}

function get_matches_cpu_pgm($str, &$matches) {
	$i = 0;
	while ($matches > 0) {
		echo "Tour de l'ordinateur pgm\n";
		while (($matches % 4) !== 1 && $matches > 0 && $i < 3) {
			$i++;
			$matches--;
		}
		if ($matches % 4 === 1 && $matches > 0 && $i === 0) {
			$i++;
			$matches--;
		}
		echo "L'ordinateur pgm retire $i allumettes\n";
		if ($matches > 0)
			display($matches);
	}
	if ($matches <= 0) {
		echo "L'ordinateur pgm a tiré la dernière allumette, il perd la partie!\n";
		die();
	}
}

display();

?>