<?php
function Restring($string) {
	return str_replace("'", "''", $string); // Replaces all spaces with hyphens.
}
function ReDate1($string) {
	return str_replace("T", " ", ReDate2($string)); // Replaces all spaces with hyphens.
}
function ReDate2($string) {
	return str_replace("Z", "", $string); // Replaces all spaces with hyphens.
}
?>