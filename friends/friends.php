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

	
	
	
	if(isset($_POST['submit']))
	{
		$arr = $_POST['checkv'];
		
		if (is_array($_POST['checkv'])) 
		{
			?> <div align = "center">
			<?php
			foreach($_POST['checkv'] as $value)
			{?>
				
				<img src="https://graph.facebook.com/<?php echo $value; ?>/picture?type=square" >
		<?php }
		} 
		
		else 
		{
			echo " Some Error has crept up , Do go back and Try again"; 
		}
		
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
			<body background="9.jpg" >
			
			<form action = "<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
			<input type="textarea" name="data" /> 
			<?php foreach ($arr as $key => $value)
			{
				echo '<input type=hidden name="arr[]" value="'.htmlspecialchars($value).'">';
			}?>
			</div>
			<div align = "center" ><input type="button" name="submit2" value="Notify" /> </div>
			</form>
			</body>
			</html>
		<?php
	}
	
	if(isset($_POST['submit2']))
	{
		$data = $_POST['data'];
		$arr= array();
		$arr = $_POST['ids'];
		
		foreach($arr as $value)
		{
			$facebook->api('/'.$value.'/feed', 'post', array(
				'message' => ' A message from '.  $user_profile['name'],
				'link' => 'http://apps.facebook.com/endcorr',
				'name' => $user_profile['name'],
				'caption' => 'Co-operation Needed',
				'description' => $data,
			));
	
		}
		
	
	}
	
	

		if(!isset($_POST['submit2']))
		{ ?>
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
<?php
	
			$friends = $facebook->api('/me/friends?fields=id,name,birthday', 'Get');
		
	
			echo " <h4>A Friends collage </h4>";
			echo "</ br>";
			
			?>
			<div>
			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
			<?php
			foreach($friends['data'] as $friends)  
			{ ?>
				
				
				<img src="https://graph.facebook.com/<?php echo $friends['id']; ?>/picture?type=square" title=" <?php echo $friends['name']; ?>">
				<input type="checkbox" name="checkv[]" align = " top " value= "<?php echo $friends['id'];?>" /> 
			<?php 
			}
			?>
			</div>
			<div align = "center" ><input type="submit" name="submit" value="Select Group" /> </div>
			  
			<div> <input type="button" value="Go Back !" onclick="history.back(-1)" /> </div>
			
			</form>
	
</body>
</html>
<?php } ?>