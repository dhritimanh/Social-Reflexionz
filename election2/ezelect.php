<html>
<head>
<?php

require 'reqd/facebook.php';

// Create our Application instance 
$config = array(
  'appId'  => '339280032820737',
  'secret' => '0a85c15f77155863cfc678c794940796',
);

$facebook = new Facebook($config);
// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me','GET');
  } catch (FacebookApiException $e) {
    $login_url = $facebook->getLoginUrl(); 
    //echo 'Please <a href="' . $login_url . '">login.</a>';
    error_log($e->getType());
    error_log($e->getMessage());
    $user = null;
  }
}
else 
{
		$loginUrl = $facebook->getLoginUrl(array(
       'scope' => 'publish_actions,publish_stream,read_stream,offline_access,photo_upload',
       'redirect_uri' => 'http://apps.facebook.com/sociref/',
     ));

     print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
   }
      // No user, print a link for the user to login
     // $login_url = $facebook->getLoginUrl();
      //echo 'Please <a href="' . $login_url . '">login.</a>';



/***************************************************/


	if (isset($_POST['sdate']))
	{
		$sdate = $_POST['sdate'];
	}
	
	if (isset($_POST['name']))
	{
		$sdate = $_POST['name'];
	}
	if (isset($_POST['edate']))
	{
		$sdate = $_POST['edate'];
	}
	if (isset($_POST['uid']))
	{
		$uid = $_POST['uid'];
	}
	if (isset($_POST['checkv']))
	{	
		$arr = $_POST['checkv'];
		$ids = array();
		$ids = $arr;
		$names = array();
		foreach($arr as $value)
		{
			$facebookUrl = "http://graph.facebook.com/".$value; 
			$str = file_get_contents($facebookUrl); 
			$result = json_decode($str); 
		
			$names[$value] = $result->name; 
	
		}
	}

		//print_r($names);
		//print_r($ids);

	echo "</br>";

/* Customize the election by editing the following */
	if (isset($_POST['topic']))
	{
		$electionname = $_POST['topic'];
	}
/* type of the election: multi (elect one of a number of candidates), single (support/not-support) */
	if (isset($_POST['type']))
	{
		$type = $_POST['type'];
	}
/* unique id's of candidates */

/* map from id's to names */
	

//echo $names[$ids[0]];
//$names = array("smith"=>"Smith, John", "baker"=>"Baker, Alice");


/* the dir to put all vote ticket files */
	$datadir = 'votes/';
//$datadir = '/home/b/bc/bcssa/data/2005election';


/* url base of all pages */
//$urlbase = "http://demo.org/election/";
	$urlbase = "https://quiet-beach-9206.herokuapp.com/election2/";


/* from and reply-to address of the emails sent */
	$fromemail = $_POST['email'];

	

/* email pattern to allow. do not set if all emails should be allowed */
// e.g. allowing only @demo.org emails
	$emailpattern = '/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@gmail\.com$/';
	$emailpatterndesc = 'Only email addresses ending with @gmail.com are allowed.';
// e.g. allowing only @demo.org or @*.demo.org
//$emailpattern = '/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@([a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.)?demo\.org$/';
//$emailpatterndesc = 'Only email addresses ending with @demo.org or @*.demo.org are allowed.';

/* No more changes needed beyond this line         */
/***************************************************/



$host="182.72.63.18"; // Host name 
$username="fc_team_96"; // Mysql username 
$password="Dpa6wUqKJ8NPEsLc"; // Mysql password 
$db_name="fc_team_96"; // Database name 

$tbl_name="election_question"; // Table name 

$con = mysql_connect("$host", "$username", "$password");

if (!$con)
	{
		die("cannot connect : " . mysql_error() ); 
	
	}
mysql_select_db("$db_name", $con) or die("cannot select DB");

$sql="INSERT INTO $tbl_name(topic, detail, name, email, sdate, edate, uid)VALUES('$topic', '$detail', '$name', '$email', '$sdate', '$edate','$uid')";
$result=mysql_query($sql);

if($result){ ?>
	<h3> Successfully Created </h3>
	 <a href=my.php>View your election</a>

	<?php
	$facebook->api('/me/feed', 'post', array(
						'message' => ' An Election has been Created by' . $name,
						'link' => 'http://apps.facebook.com/endcorr/',
						'name' => $topic,
						'caption' => 'Join Me in this Movement',
						'description' => $detail,
						)
						);
}
else {
echo "ERROR";
}

$tbl_name3="election_question";
$sql3="SELECT id FROM $tbl_name3 where detail = $detail";
$result3=mysql_query($sql3);
while($rows3=mysql_fetch_array($result3) )
{


	$tbl_name2="election_candidates"; 
	foreach ($ids as $cid)
	{	
		$sql2="INSERT INTO $tbl_name2(eid , cid) VALUES ('$rows3','$cid')";

		$result2=mysql_query($sql2);
	}
	if($result2)
	{ ?>
		<h3> Successfully Created </h3>
		
	}
	else
	{
		echo "error";
	}

	
}	

mysql_close();
?>