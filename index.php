<?
function str_makerand ($minlength, $maxlength, $usespecial, $usenumbers) {
	$charset = "abcdefghijklmnopqrstuvwxyz";
	if ($usenumbers) $charset .= "0123456789";
	if ($usespecial) $charset .= "~@#$%^*()_+-={}|][";
	if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength);
	else $length = mt_rand ($minlength, $maxlength);
	for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
	return $key;
}
?>
<html>
<head>
  <title>Chinese Restaurant Name Generator</title>
</head>
<body bgcolor="#FFFFFF">
<h2>Generate Your Chinese Restaurant Name</h2>
<form action="<?= $_SERVER['PHP_SELF']; ?>" METHOD="POST" name="usename">
	<b>Enter your Name: </b><br>
	<input type="text" name="realname" size=25> &nbsp; &nbsp;
	<input type="submit" value="Submit">
</form>
<form action="<?= $_SERVER['PHP_SELF']; ?>" METHOD="POST" name="makeitup">
	<input type="submit" name="makeup" value="Just Make Something Up!">
</form>
<p><hr size=1 noshade></p>
<?
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($_POST['makeup']) {
		$realname = str_makerand(5,20,1,1);
	} else {
		$realname = $_POST['realname']; 
		$realname = strtolower($realname);
	}

	// generate seed number from name submitted
	$len = strlen($realname);
	$seed = 0; $s = 0;

	for ($e=1; $e<=$len; $e++) 
    {
	  $chr = substr($realname,$s,$e);
	  $seed = $seed + ord($chr)*$e;

	  $s=$e;
	}

	// read in the files into arrays
	$prefix_array = file("prefix.txt");
	$noun_array = file("noun.txt");

	// set random seed
	srand($seed);

	// get the random numbers for each name first/last or adj/noun
	$prefixrnd = rand(0,sizeof($prefix_array)-1);
	$noun1rnd = rand(0,sizeof($noun_array)-1);
	$noun2rnd = rand(0,sizeof($noun_array)-1);
	$noun3rnd = rand(0,sizeof($noun_array)-1);
	
	// create name from random numbers
	// chance of a prefix
	if (rand(0,1) == 0) {
		$restname = trim($prefix_array[$prefixrnd])." ";
	}

	$restname .= trim($noun_array[$noun1rnd])." ".trim($noun_array[$noun2rnd]);
	
	// chance of a 3 word name
	if (rand(0,1) == 0) { 
		$restname .= " ".trim($noun_array[$noun3rnd]);
	}

	$restname = trim($restname);
	
	// chance of suffix
	if (rand(0,1) == 0) {
		switch (rand(0,1)) {
			case 0:
				$restname .= " Buffet";
				break;
			case 1:
				$restname .= " Restaurant";
				break;
		}
	}
}
?>
Your Chinese Restaurant Name is:<br><br>
<a href="http://google.com/#q=<?= $restname ?>"><span style="background-color:#F00;color:#FF0;border:1px solid black;padding:5px 5px;font-size:1.5em;"><?= $restname ?></span></a>
</body>
</html>
