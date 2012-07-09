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
<html >
<head>
<title>HTML Form</title>

<style type="text/css">

* { margin: 0; padding: 0; }

html { height: 100%; font-size: 62.5% }

body { height: 100%; background-color: #FFFFFF; font: 1.2em Verdana, Arial, Helvetica, sans-serif; }


/* ==================== Form style sheet ==================== */

form { margin: 25px 0 0 29px; width: 450px; padding-bottom: 30px; }

fieldset { margin: 0 0 22px 0; border: 1px solid #095D92; padding: 12px 17px; background-color: #DFF3FF; }
legend { font-size: 1.1em; background-color: #095D92; color: #FFFFFF; font-weight: bold; padding: 4px 8px; }

label.float { float: left; display: block; width: 100px; margin: 4px 0 0 0; clear: left; }
label { display: block; width: auto; margin: 0 0 10px 0; }
label.spam-protection { display: inline; width: auto; margin: 0; }

input.inp-text, textarea, input.choose, input.answer { border: 1px solid #909090; padding: 3px; }
input.inp-text { width: 300px; margin: 0 0 8px 0; }
textarea { width: 400px; height: 150px; margin: 0 0 12px 0; display: block; }

input.choose { margin: 0 2px 0 0; }
input.answer { width: 40px; margin: 0 0 0 10px; }
input.submit-button { font: 1.4em Georgia, "Times New Roman", Times, serif; letter-spacing: 1px; display: block; margin: 23px 0 0 0; }

form br { display: none; }

/* ==================== Form style sheet END ==================== */

</style>

<!--[if IE]>
<style type="text/css">

/* ==================== Form style sheet for IE ==================== */

fieldset { padding: 22px 17px 12px 17px; position: relative; margin: 12px 0 34px 0; }
legend { position: absolute; top: -12px; left: 10px; }
label.float { margin: 5px 0 0 0; }
label { margin: 0 0 5px 0; }
label.spam-protection { display: inline; width: auto; position: relative; top: -3px; }
input.choose { border: 0; margin: 0; }
input.submit-button { margin: -10px 0 0 0; }

/* ==================== Form style sheet for IE end ==================== */

</style>
<![endif]-->
<script LANGUAGE="JavaScript">

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

</head>


<body>

	<form name = "form1" action="ezelect.php" method="post">
		<!-- ============================== Fieldset 1 ============================== -->
		<fieldset>
			<legend>New Election Setup</legend>
				<label for="input-one" class="float"><strong>Election Title:</strong></label><br />
				<input class="inp-text" name="topic" id="input-one" type="text" size="30" /><br />

				<label for="input-two" class="float"><strong>Details:</strong></label><br />
				<input class="inp-text" name="detail"  id="input-two" type="text" size="30" />

				<label for="input-two" class="float"><strong>Type:</strong></label><br />
				<input class="inp-text" name="type"  id="input-two" type="text" size="30" />

				<label for="input-two" class="float"><strong>Email:</strong></label><br />
				<input class="inp-text" name="email"  id="input-two" type="text" size="30" />

				<label for="input-two" class="float"><strong>Start Date:</strong></label><br />
				<input class="inp-text" name="sdate"  id="input-two" type="text" size="30" />

				<label for="input-two" class="float"><strong>End Date:</strong></label><br />
				<input class="inp-text" name="edate"  id="input-two" type="text" size="30" />


		</fieldset>
		<!-- ============================== Fieldset 1 end ============================== -->


		<!-- ============================== Fieldset 2 ============================== -->
		<div>
			<legend>Select Candicate for Election:</legend>
			</br>
				<?php $friends = $facebook->api('/me/friends?fields=id,name,birthday', 'Get');	?>
				<?php
					foreach($friends['data'] as $friends)
					{ ?>


						<img src="https://graph.facebook.com/<?php echo $friends['id']; ?>/picture?type=square" title=" <?php echo $friends['name']; ?>">
						<input type="checkbox" name="checkv[]" align = " top " value= "<?php echo $friends['id'];?>" />
					<?php
					}
					?>
		</div>
		<!-- ============================== Fieldset 2 end ============================== -->

		<p><input class="submit-button" type="image" src="pic/send-button.gif" alt="SUBMIT" name="Submit2" value="SUBMIT" />
		<input type="hidden" name="uid" value="<?php echo $user_profile['id'];?>" /></p>
	</form>

</body>
</html>
