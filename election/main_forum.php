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

$sql = "SELECT * FROM $tbl_name ORDER BY id DESC";


$result=mysql_query($sql);
?>
<html>
 <head>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
<body>
<div align = "center">
<?php
if ($user){
      echo "<strong>Welcome , </strong> <em>". $user_profile['name'] . "</em></br>" ;
	 
	  if(isset($user_profile))
	  {
	  ?>
	  </br>
      <img src="https://graph.facebook.com/<?php echo $user_profile['id'];?>/picture?type=square" title=" <?php echo $user_profile['name']; ?>">
	<?php
		}
	  } 
	  
	  if(isset($user_profile))
	  {
?>
</div>
</br>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<td width="6%" align="center" bgcolor="#E6E6E6"><strong>#</strong></td>
<td width="53%" align="center" bgcolor="#E6E6E6"><strong>Election</strong></td>
<td width="15%" align="center" bgcolor="#E6E6E6"><strong>Views</strong></td>
<td width="13%" align="center" bgcolor="#E6E6E6"><strong>Start Date</strong></td>
<td width="13%" align="center" bgcolor="#E6E6E6"><strong>End Date</strong></td>
</tr>

<?php
 

while($rows=mysql_fetch_array($result))
{
	?>
<tr>
<td bgcolor="#FFFFFF"><?php echo $rows['id']; ?></td>
<td bgcolor="#FFFFFF"><a href="view_topic.php?id=<?php echo $rows['id']; ?>&uid=<?php echo $user_profile['id'];?>"><?php echo $rows['topic']; ?></a><BR></td>
<td align="center" bgcolor="#FFFFFF"><?php echo $rows['view']; ?></td>
<td align="center" bgcolor="#FFFFFF"><?php echo $rows['sdate']; ?></td>
<td align="center" bgcolor="#FFFFFF"><?php echo $rows['edate']; ?></td>
</tr>

<?php
 
}
mysql_close($con);
?>
</table>

<div align = "center">
	</br>
	<a href="create_topic.php"><img src="pic/1.png" title = "create"/></a> 
	
</div>
</body>
</html>
<?php } ?>