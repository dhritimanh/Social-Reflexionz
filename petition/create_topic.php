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

	
	
	
	
	
?>


<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>

<form id="form1" name="form1" method="post" action="add_topic.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Create New Petition</strong> </td>
</tr>
<tr>
<td width="14%"><strong>Petition Topic</strong></td>
<td width="2%">:</td>
<td width="84%"><input name="topic" type="text" id="topic" size="50" /></td>
</tr>
<tr>
<td valign="top"><strong>Recipent name</strong></td>
<td valign="top">:</td>
<td><input name="name2" type = "text" id="name2" size= "50"></input></td>
</tr>
<td><strong>Recipent Email</strong></td>
<td>:</td>
<td><input name="email" type="text" id="email" size="50" /></td>
</tr>
<tr>
<td valign="top"><strong>Importance/Details</strong></td>
<td valign="top">:</td>
<td><textarea name="detail" cols="50" rows="3" id="detail"></textarea></td>
</tr>
<tr>

<tr>
<td><strong>Your Name</strong></td>
<td>:</td>
<td><input name="name" type="text" id="name" size="50" /></td>
</tr>
<tr>
<td><strong>Your Email</strong></td>
<td>:</td>
<td><input name="email2" type="text" id="email2" size="50" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>
</tr>
</table>
</td>
</tr>
</table>

<div align = "center"><input type="submit" name="Submit" value="Submit" /> <input type="reset" name="Submit2" value="Reset" />
<?php if (isset($user_profile)) { ?>
<input type="hidden" name="uid" value="<?php echo $user_profile['id'];?>" /> <?php } else { echo '<a href="<?php echo $loginUrl; ?>">Login with Facebook</a>'; }?>

</form>
</div>
