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
$tbl_name="petition_question"; // Table name 


$con = mysql_connect("$host", "$username", "$password");

if (!$con)
	{
		die("cannot connect : " . mysql_error() ); 
	
	}
mysql_select_db("$db_name", $con) or die("cannot select DB");

	

	


$id=$_GET["id"];
$uid = $_GET["uid"];

$sql=" SELECT * FROM $tbl_name WHERE id=$id";

$result=mysql_query($sql);
$rows=mysql_fetch_array($result);
?>

<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bordercolor="1" bgcolor="#FFFFFF">
<tr>
<td bgcolor="#F8F7F1"><strong> Topic : </strong> <em> <?php echo $rows['topic']; ?> </em></td>
</tr>

<tr>
<td bgcolor="#F8F7F1"> <strong> Details : </strong> <em><?php echo $rows['detail']; ?></em></td>
</tr>

<tr>
<td bgcolor="#F8F7F1"> <strong> To: </strong> <em><?php echo $rows['name2']; ?></em></td>
</tr>


<tr>
<td bgcolor="#F8F7F1"><strong>By :</strong> <?php echo $rows['name']; ?> <strong> Email : </strong><?php echo $rows['email2'];?></td>
</tr>

<tr>
<td bgcolor="#F8F7F1"><strong>Date/time : </strong><?php echo $rows['datetime']; ?></td>
</tr>
</table></td>
</tr>
</table>
<BR>
<?php 
	// query uid
	// check the uid
	// if else on uid
	if (!isset($user_profile))
	{
		?><div align = "center"> </br> <h3>Please Login with Facebook ID in the Above Link</h3> </div>
	<?php
	}
	else
	{
	if ($rows['uid'] == $user_profile['id'])
	{
		?>
			<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
			<tr>
			<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">

			<tr>
			<td width="18%" bgcolor="#F8F7F1"><strong>Preview Petition</strong></td>
			<td width="5%" bgcolor="#F8F7F1">:</td>
			<td width="77%" bgcolor="#F8F7F1"><a href = "preview.php?uid=<?php echo $uid;?>" > Preview and Send</></td>
			</tr>
			<tr>
			<td bgcolor="#F8F7F1"><strong>Share with Friends</strong></td>
			<td bgcolor="#F8F7F1">:</td>
			<td bgcolor="#F8F7F1"><a href="share.php?topic=<?php echo $rows['topic'];?>"> Share </></td>
			</tr>
			<tr>
			<tr>
			<td bgcolor="#F8F7F1"><strong>Signatures</strong></td>
			<td bgcolor="#F8F7F1">:</td>
			<td bgcolor="#F8F7F1"><a href="sign.php?id=<?php echo $rows['id'];?>"> Signatures</></td>
			</tr>
			</table></td>
			</tr>
			</table><br>

			
			
	
	
	<?php }
	
	else
	{
	
		
	
?>
<?php

		$tbl_name2="petition_answer"; // Switch to table "petiton_answer"
		$sql2="SELECT * FROM $tbl_name2 WHERE question_id=$id";
		$result2=mysql_query($sql2);
		while($rows=mysql_fetch_array($result2)){
?>

<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">

<tr>
<td width="18%" bgcolor="#F8F7F1"><strong>Name</strong></td>
<td width="5%" bgcolor="#F8F7F1">:</td>
<td width="77%" bgcolor="#F8F7F1"><?php echo $rows['a_name']; ?></td>
</tr>
<tr>
<td bgcolor="#F8F7F1"><strong>Email</strong></td>
<td bgcolor="#F8F7F1">:</td>
<td bgcolor="#F8F7F1"><?php echo $rows['a_email']; ?></td>
</tr>
<tr>
<td bgcolor="#F8F7F1"><strong>Reason of Sign</strong></td>
<td bgcolor="#F8F7F1">:</td>
<td bgcolor="#F8F7F1"><?php echo $rows['a_answer']; ?></td>
</tr>
<tr>
<td bgcolor="#F8F7F1"><strong>Date/Time</strong></td>
<td bgcolor="#F8F7F1">:</td>
<td bgcolor="#F8F7F1"><?php echo $rows['a_datetime']; ?></td>
</tr>
</table></td>
</tr>
</table><br>
 
<?php
}

$sql3="SELECT view FROM $tbl_name WHERE id=$id";
$result3=mysql_query($sql3);
$rows=mysql_fetch_array($result3);
$view=$rows['view'];
 
// if have no counter value set counter = 1
if(empty($view)){
$view=1;
$sql4="INSERT INTO $tbl_name(view) VALUES($view) WHERE id=$id";
$result4=mysql_query($sql4);
}
 
// count more value
$addview=$view+1;
$sql5="update $tbl_name set view=$addview WHERE id=$id";
$result5=mysql_query($sql5);
mysql_close();
?>


<?php

$tbl_name5="petition_question"; // Table name 


$con = mysql_connect("$host", "$username", "$password");

if (!$con)
	{
		die("cannot connect : " . mysql_error() ); 
	
	}
mysql_select_db("$db_name", $con) or die("cannot select DB");
$sql5=" SELECT * FROM $tbl_name WHERE id=$id";

$result5=mysql_query($sql5);
$rows5=mysql_fetch_array($result5);

?>

<BR>
<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form name="form1" method="post" action="add_answer.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td width="18%"><strong>Name</strong></td>
<td width="3%">:</td>
<td width="79%"><input name="a_name" type="text" id="a_name" size="45"></td>
</tr>
<tr>
<td><strong>Email</strong></td>
<td>:</td>
<td><input name="a_email" type="text" id="a_email" size="45"></td>
</tr>
<tr>
<td valign="top"><strong>Reason to Sign</strong></td>
<td valign="top">:</td>
<td><textarea name="a_answer" cols="45" rows="3" id="a_answer"></textarea></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="hidden" name="id" value="<?php echo $id; ?>"></td>

<td><input type="submit" name="Submit" value="Sign"> <input type="reset" name="Submit2" value="Reset">
<a href = "main_forum.php" >Homepage</a>
</tr>
<tr> <td><input type="hidden" name="topic" value="<?php echo $rows5['topic']; ?>"></td>
<td><input type="hidden" name="detail" value="<?php echo $rows5['detail'];?>"></td></tr>
<input type="hidden" name="id" value="<?php echo $id; ?>"></td>
</table>
</td>
</form>

</tr>
</table>
<?php } 
}
?>