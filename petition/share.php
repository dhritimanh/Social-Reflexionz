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

	
	
	
if ($user) {
	
	if(isset($_POST['submit']))
	{
		$topic=$_GET["topic"];
		$arr = $_POST['checkv'];
		
		if (is_array($arr)) 
		{
			//print_r($arr);
			
			
		
			foreach($arr as $value)
			{
				if(isset($value))
				{
					$facebook->api('/' .$value.'/feed', 'post', array(
						'message' => 'A petition is Created',
						'link' => 'http://apps.facebook.com/endcorr/',
						'name' => $topic,
						'caption' => 'I need your Signature',
						'description' => 'Stand Against Corruption',
						)
						);
				  
				}
				echo " Information sent" ;
			}
		 }
	  }
	
	
	}
	else
	{

      //No user, print a link for the user to login
      $login_url = $facebook->getLoginUrl();
      echo 'Please <a href="' . $login_url . '">login.</a>';
	}
	
	
	
	
?>
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">

function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}

</script>
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

	<?php 
		$friends = $facebook->api('/me/friends?fields=id,name,birthday', 'Get');
		
	
			echo " <h4>Share With Your Friends</h4>";
			echo "</ br>";
			
			?>
			<div>
			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post" name = "myform">
			<?php
			foreach($friends['data'] as $friends)  
			{ ?>
				
				
				<img src="https://graph.facebook.com/<?php echo $friends['id']; ?>/picture?type=square" title=" <?php echo $friends['name']; ?>">
				<input type="checkbox" name="checkv[]"  value= "<?php echo $friends['id'];?>" /> 
			<?php 
			}
			?>
			</div>
			<div align = "center" ><input type="submit" name="submit" value="Inform them about Your petition" /> </div>
			  
			<div> <input type="button" value="Go Back !" onclick="history.back(-1)" /> </div>
			<div><input type="button" name="CheckAll" value="Check All" onClick="checkAll(document.myform['checkv[]'])">
			<input type="button" name="UnCheckAll" value="Uncheck All" onClick="uncheckAll(document.myform['checkv[]'])">
			</div>
			</form>
		
		
			
	
	
	
</body>
</html>