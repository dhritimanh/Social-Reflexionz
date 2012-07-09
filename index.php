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
<!doctype html>
<html>
  <head>
    <title>Social Reflexionz</title>
    <style>
      body {
			background-image : pic/9.jpg; 
			background-repeat : repeat; 
			background-position : 50% 50%; 
			background-attachment : fixed; 
			color : #000000; 
			margin : 0; 
			}
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
<body background="pic/9.jpg">
    <h3>WELCOME , TO <strong>SOCIAL REFLEXIONZ </strong> </br> <h3>
	 A Mirror to the Society and a Voice against Corruption 

    

   
      <h3> Welcome, </h3></br> <?php echo $user_profile['name'];?> 
      <div align = "center" ><img src="https://graph.facebook.com/<?php echo $user; ?>/picture" title = "<?php echo $user_profile['name'];?>">
	  </div>
	  </br>
      <div align = " center "><a href="friends/friends.php"><img src="pic/1.png" title = " Friends"/></a>  
		<a href="petition/main_forum.php"><img src="pic/2.png" title = " Petition" /></a> 
		<a href="corr/main_forum.php"><img src="pic/3.png" title = " Stop corruption" /></a> </div>
	  <div align = "center"> <a href="govt/main_forum.php"><img src="pic/4.png" title = " Govt. Offices" /></a> 
	  
	  <a href="election2/index.php"><img src="pic/6.png" title = " Election Starts"/>
	  </div>
    </br>

	<div>
		<fieldset>
		<p> The application lets the user discuss and debate the issues related to corruption, cases of indifference and then take actions against these issues
		by the use of petitions and online elections. While petitions will directly interact with concerned officials, elections can be an indirect directive
		against these issues.
		</p>
		</fieldset>
	</div>

	
</body>
</html>  
	