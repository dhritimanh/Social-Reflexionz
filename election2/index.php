<?php 
	require 'reqd/facebook.php';
	ini_set('display_errors', 'on');
    error_reporting(E_ALL);
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


?>
<html>
<head>
<title>Facebook Online Election</title>
</head>
<body background="9.jpg">
<h2><Center>Welcome To the Social Reflexionz Election Control Panel</center></h2>


      <h3><?php echo $user_profile['name'];?> , What Do You Want to Do ? </h3>
      
		<div> 
			<a href="create.php"><img src="pic/1.jpg" title = " Election Creation"/></a>  
		<a href="my.php"><img src="pic/2.gif" title = "Vote on an election" /></a> 
	  







</body>
</html>