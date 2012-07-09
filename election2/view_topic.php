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

$id = $_GET['id'];




$host="182.72.63.18"; // Host name 
$username="fc_team_96"; // Mysql username 
$password="Dpa6wUqKJ8NPEsLc"; // Mysql password 
$db_name="fc_team_96"; // Database name 




$tbl_name2="election_candidates"; 
	$sql2="SELECT * FROM $tbl_name2 where eid = $id";
	$result2=mysql_query($sql2);
	while($rows2=mysql_fetch_array($result2))
	{
		print_r($rows);
	}
?>

<title><?php echo $electionname; ?></title>
</head>
<body>
<h2><Center>Welcome to <?php echo $electionname; ?></center></h2>
<p>&nbsp;</p>



 <h3>Voting Form</h3>
    
    <p>Please fill in the following form. <b>All fields are required.</b>
    
    <form action="vote.php" method="post">
    
    <table border=1>
    <tr>
    <td>Your valid email address. <?php echo $emailpatterndesc; ?></b></td>
    <td><input type=text name=email></td>
    </tr>
    
<?php
	while($rows2=mysql_fetch_array($result2))
	{

		if ($type == "single") {
			$name=$names[$ids[0]];
?>
    <tr>
    <td>Select your vote to candidate <b><?php print $name ?></b></td>
    <td><input type=radio name=cand value="support">Support</input><br>
        <input type=radio name=cand value="notsupport">Do NOT Support</input><br>
    </td>
<?php
    } else {
?>
    <tr>
    <td>Select <b>ONE</b> of the candidates you hereby vote for:</td>
    <td>
<?php
	for ($i = 0; $i < count($ids); $i++) {
		$id=$ids[$i];
		$name=$names[$id];
		print "<input type= radio name= cand value=".$id.">$name</input><br>\n";
	}
    }
	}
?>

</td>
</tr>

</table>
<input type = "hidden" name = "type" value = "<?php echo $type;?>"> 
<?php foreach ($ids as $key => $value)
{
	echo '<input type=hidden name="ids[]" value="'.htmlspecialchars($value).'">';
}?>
<input type="hidden" name= "act" value= <?php echo "vote"; ?>>
<input type="submit" name= "submit" value= "submit ">
<input type="button" value="Cancel" name= " cancel" onclick="history.back()">
<input type="hidden" name= "datadir" value= <?php echo $datadir; ?>>
<input type="hidden" name= "urlbase" value= <?php echo $urlbase; ?>>
<input type="hidden" name= "fromemail" value= <?php echo $fromemail; ?>>
<input type="hidden" name= "electionname" value= <?php echo $electionname; ?>>
<input type="hidden" name= "emailpattern" value= <?php echo $emailpattern; ?>>
<input type="hidden" name= "emailpatterndesc" value= <?php echo $emailpatterndesc; ?>>
</form>


</body>
</html>
?>