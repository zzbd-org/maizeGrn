<?php
include 'config.php';

define("QUERY1", 'SELECT %s,%s FROM %s WHERE %s ORDER BY %s ASC, scr DESC');
define("QUERY2", '(SELECT a.%s,a.%s,COALESCE(b.aname,""),c.chr,c.lpos, c.rpos,c.description,d.ida,d.sde,d.sum,d.cde
FROM %s AS a
LEFT JOIN v3abbr AS b ON a.%s=b.v3
LEFT JOIN v3info AS c ON a.%s=c.v3
LEFT JOIN v3homo AS d ON a.%s=d.v3
WHERE a.%s="%s" ORDER by a.scr DESC%s)');
define("QUERY3", 'SELECT %s,%s,ca FROM idconv WHERE %s ORDER BY %s');
define("QUERY4", '(SELECT a.%s,a.%s,a.ca,COALESCE(b.description,"-")
FROM idconv AS a
LEFT JOIN v4func AS b ON a.v4=b.v4
WHERE a.%s ORDER by a.%s)');
define("QUERY_TITLE", "%s\t%s\tSymbol\tChr.\t5'position\t3'position\tdescription\tA. thaliana\tprotein\tsummary\tdescribe");
define("CONV_TITLE", "%s ID\t%s ID\ttype%s");

global $query;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Error connecting to database: ".mysqli_error());
$geneint = array();
$org = array();
$organ = array("leaf", "root", "SAM", "seed");
isset($_POST["s_key"]) or die("null keywords");
$keys = preg_split('/[\s,]+/', $_POST["s_key"], -1, PREG_SPLIT_NO_EMPTY);
if (isset($_POST["s_tar"]) && $_POST["s_tar"] == 1) {
	$typ = array("tar", "reg", "Targets", "Regulators");
} else {
	$typ = array("reg", "tar", "Regulators", "Targets");
}

foreach ($organ as $o) {
	if (isset($_POST[$o])) { $org[] = $o; }
}

if (isset($_POST["s_v4"])) {
	//convert id
	$dataArray = array();
	$type = ($_POST["s_v4"] == 1) ? array("v4","v3"):array("v3","v4");
	$q_con = sprintf(isset($_POST["s_f4"]) ? QUERY4:QUERY3, $type[0], $type[1], $type[0].' in ("'.implode('","', $keys).'")', $type[0]);
	if($query = mysqli_query($con, $q_con)) {
	   $geneint = mysqli_fetch_all($query, MYSQLI_NUM);
	   array_unshift($geneint, sprintf(CONV_TITLE, $type[0], $type[1],isset($_POST["s_f4"]) ? "\tdescription":""));
	}
} elseif ($_POST["s_flag"] == 3) {
	//generate download link
	$geneint[] = 0;
	$currHash = hash('crc32', implode($typ[0], $keys).$typ[0].implode($typ[1], $org));
	$currPath = "tmp/".$currHash."/";
	if(!is_dir($currPath)) {mkdir($currPath, 0755, true);}
	if ($f = fopen($currPath."maize_tissue_grn.tsv", "w")) {
		fwrite($f, sprintf(QUERY_TITLE, $typ[2], $typ[3])."\n");
		foreach ($org as $o) {
			$dataArray = array();
			$sql = array();
			foreach ($keys as $k) { $sql[] = sprintf(QUERY2, $typ[0], $typ[1], $o, $typ[1], $typ[1], $typ[1],  $typ[0], $k, ""); }
			$q_con = implode(' UNION ALL ', $sql);
			if($query = mysqli_query($con, $q_con)){
				while($row = mysqli_fetch_array($query, MYSQLI_NUM)){
					$dataArray[] = implode("\t", $row);
				}
				if ($num = fwrite($f, $o.":\n".implode("\n", $dataArray)."\n")) {
					$geneint[0] += $num;
					$geneint[] = $currPath."maize_tissue_grn.tsv";
				}
			}
		}
		$geneint[0] = conSize($geneint[0]);
		fclose($f);
	}
} else if ($_POST["s_flag"] == 1) {
	//fetch expert
	foreach ($org as $o) {
		$dataArray = array();
		$sql = array();
		$geneint["head"] = explode("\t", sprintf(QUERY_TITLE, $typ[2], $typ[3]));
		foreach ($keys as $k) { $sql[] = sprintf(QUERY2, $typ[0], $typ[1], $o, $typ[1], $typ[1], $typ[1],  $typ[0], $k, ' LIMIT '.$_POST["s_num"]); }
		$q_con = implode(' UNION ALL ', $sql);
		if($query = mysqli_query($con, $q_con)) { $dataArray = mysqli_fetch_all($query, MYSQLI_NUM); }
		$geneint[$o] = $dataArray;
	}
} else {
	//id only
	foreach ($org as $o) {
		$dataArray = array();
		$sql = $typ[0].' in ("'.implode('","', $keys).'")';
		$q_con = sprintf(QUERY1, $typ[0], $typ[1], $o, $sql, $typ[0]);
		//error_log($q_con);
		if($query = mysqli_query($con, $q_con)){
			$regTemp = "";
			while($row = mysqli_fetch_array($query, MYSQLI_NUM)){
				if ($row[0] <> $regTemp){ $regTemp = $row[0]; }
				$dataArray[$row[0]][] = $row[1];
			}
		}
		$geneint[$o] = $dataArray;
	}
}
if ($query) { mysqli_free_result($query); }
echo json_encode($geneint);

function conSize($s){
	$base = log($s) / log(1024);
	$suffix = array("", "KB", "MB", "GB", "TB");
	$f_base = floor($base);
	return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}
?>
