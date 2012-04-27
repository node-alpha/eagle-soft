<?php

function idump ($the_a_Data, $die = true)
{
	echo '<pre>';
	var_dump($the_a_Data);
	echo '</pre>';
	if ($die)
		die();
}

function iprint ($the_a_Data, $die = true)
{
	echo '<pre>';
	print_r($the_a_Data);
	echo '</pre>';
	if ($die)
		die();
}
?>