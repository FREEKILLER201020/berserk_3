<?php
$f = "";
for ($i = 0; $i < count($argv); $i++) {
	if ($argv[$i] == "-f") {
		$f = $argv[$i + 1];
	}
}
exec("rm .config.json");
echo "ln -s .config_" . $f . ".json .config.json" . PHP_EOL;
exec("ln -s .config_" . $f . ".json .config.json");
?>