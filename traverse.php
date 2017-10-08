<?php

#########################################
#
# Author : Umer Ahmed
# Date : 10-6-2017
# Version: 1.0
# Comments : file traversal program
#
#########################################
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('display_errors', '0');
echo "\n";
echo "**********************************************\n";
echo "***      RUNING TRAVERSAL PROGRAM          ***\n";
echo "**********************************************\n\n";

$outfilename = 'file_list.txt';

if (file_exists($outfilename)) {
	echo "Output file \"{$outfilename}\" exists... truncating !" . "\n";
	$cleanfile = fopen("$outfilename", "w");
	ftruncate($cleanfile, 0);
	fclose($cleanfile);
}

$firstarg = "false";
$secondarg = "false";
$thirdarg = "false";

//var_dump($argv);
$opts = getopt('p:as:');
//$opts = getopt('p:as:', [], $optind);
//var_dump($opts);
//echo "optind -".$optind;
if (!$opts || $argv[1] != "-p") {
	$usage = "\n" . "Incorrect arguments have been passed - Usage is given below";
	$usage = $usage . "\n" . "REQUIRED: Give -p flag and PATH as its argument e.g. -p C:\Users\ ";
	$usage = $usage . "\n" . "OPTIONAL: Give -a flag to get the average size of all the files traversed";
	$usage = $usage . "\n" . "OPTIONAL: Give -s flag and STRING that you want to search from all files traversed";
	echo "---------->" . "\n";
	echo " Please correct per below application usage" . "\n";
	echo "---------->";
	echo $usage, "\n";
	echo "\n";
	echo "**********************************************\n";
	echo "***            RUN COMPLETE                ***\n";
	echo "**********************************************\n";
	exit(1);
} else {
// Handle command line arguments
	foreach (array_keys($opts) as $opt) {
		switch ($opt) {

		case 'p':
			$pathvalue = $opts['p'];
			$firstarg = "true";

			//echo $opt."\n";
			//echo "first is $firstarg."."\n";
			break;
		case 'a':
			$secondarg = "true";

			//echo $opt."\n";
			//echo "sec $secondarg."."\n";
			break;
		case 's':
			$search = $opts['s'];
			$thirdarg = "true";

			//echo $opt."\n";
			//echo "third $thirdarg."."\n";
			break;
		}
	}

}
echo "\n";
echo "---------->" . "\n";
echo "Traversal path given \"{$pathvalue}\"" . "\n";
//var_dump($secondarg);
//var_dump($opts);
//var_dump($pathvalue);

//$files = glob($pathvalue . '*.{txt,csv,rtf}', GLOB_BRACE);
$files = glob($pathvalue . '*.*');
//var_dump($files);
$reccount = 0;
foreach ($files as $rec) {
	if (file_exists($rec)) {
		//echo "$rec"."," . date ("m-d-Y@H:i:s", filemtime($rec)),"\n";

		$outfile = fopen($outfilename, "a") or die("Unable to open file!");
		$record = "$rec" . "," . date("m-d-Y@h:i:s A", filemtime($rec));
		$reccount++;
		//echo $record;
		fwrite($outfile, $record . "\n");
		fclose($outfile);

	}
}
echo "Traversed and found $reccount files." . "\n";
echo "filepath/filename and modified date written to output file \"{$outfilename}\"" . "\n";
echo "---------->" . "\n";

if ($secondarg == "true") {

	echo " \"a\" flag given to calculate average size of files traversed." . "\n";
	//var_dump($files);
	foreach ($files as $fsize) {
		if (file_exists($fsize)) {
			//echo $fsize . ': ' . filesize($fsize) . ' bytes',"\n";
			//$size[] = explode (',',$fsize.",".filesize($fsize));
			$size[] = explode(',', filesize($fsize));
		}
	}

	echo "---------->" . "\n";
	$num_of_files = count($size);
	echo "Number of files : " . $num_of_files . "\n";
	$sum = array_sum(array_column($size, 0));
	echo "Sum of filesize of {$num_of_files} files in bytes : {$sum}" . "\n";
	$average_size = round($sum / $num_of_files, 2);
	echo "Average size of {$num_of_files} files in bytes : {$average_size}" . "\n";
	$min = min(array_column($size, 0));
	echo "Smallest file size in bytes : $min" . "\n";
	$max = max(array_column($size, 0));
	echo "Largest file size in bytes : $max" . "\n";
	echo "---------->" . "\n";
}

if ($thirdarg == "true") {
	echo "\"s\" flag given to search \"$search\" from files traversed." . "\n";
	echo "---------->" . "\n";
	echo "Searching \"$search\" in traversed files:" . "\n";

	foreach ($files as $searchfile) {
		if (file_exists($searchfile)) {
			$counter = 0;
			$lines = file($searchfile, FILE_IGNORE_NEW_LINES);
			//var_dump($lines);
			foreach ($lines as $line) {
				if (stristr(trim($line), $search) !== false) {
					//echo $line;
					$counter++;
					//echo "Path/finame: $searchfile"."\n";
				}
			}
		}

		if ($counter > 0) {
			echo "$counter instances of \"$search\" found in: Path/finame: $searchfile" . "\n";} elseif ($counter < 1) {
			echo "Not found in: Path/finame: $searchfile" . "\n";
		}
	}
	echo "---------->" . "\n";
}

echo "\n";
echo "**********************************************\n";
echo "***            RUN COMPLETE                ***\n";
echo "**********************************************\n";

?>
