<?php
		$datadir = $_POST['datadir'];
		$urlbase = $_POST['urlbase'];
		$fromemail = $_POST['fromemail'];
		$electionname = $_POST['electionname'];
		$emailpattern = $_POST['emailpattern'];
		$emailpatterndesc = $_POST['emailpatterndesc'];
		
		$ids = array();
		$ids = $_POST['ids'];
		
		foreach($ids as $value)
		{
			$facebookUrl = "http://graph.facebook.com/".$value; 
			$str = file_get_contents($facebookUrl); 
			$result = json_decode($str); 
		
			$names[$value] = $result->name; 
	
		}
	
	
	
	$type = $_POST["type"];
	
	$act = $_POST["act"];
	$email = strtolower($_POST["email"]);
	//print_r ($names);
	

function loadvotes($fp) {
	$c_ticket=$c_id="_";
	if ($line=fscanf($fp, "%s\t%s\n"))
		list($c_ticket,$c_id)=$line;
	while ($line=fscanf($fp, "%s\t%s\n")) {
		list($ticket,$id)=$line;
		$votes[$ticket]=$id;
	}
	return array($c_ticket, $c_id, $votes);
}

function savevotes($fp,$c_ticket,$c_id,$votes) {
	ftruncate($fp,0);
	fseek($fp,0);
	$s = $c_ticket."\t".$c_id."\n";
	fwrite($fp, $s);
	foreach ($votes as $ticket => $id) {
		$s = $ticket."\t".$id."\n";
		fwrite($fp, $s);
	}
}

// generate a random ticket name
function newticket() {
	$r = "";
	for ($i = 0; $i < 20; $i++)
		$r .= mt_rand(0,9);
	return $r;
}

//print "act:$act<br>email: $email<br>";

if ((isset($_POST['submit'])) && $act=="vote") {
	$id = $_POST['cand'];
	//print_r($id);
	$error = "";
	if ($type == 'single') {
	  // for 'single', it is either 'support' or 'notsupport'
	  if ($id != 'support' && $id != 'notsupport')
	  {
	    $error .= "You have to select either Support for Do NOT Support<br>";
	  }
	  //$name = $names[$ids[0]];
	  $name = $names[0];
	} else {
	  $name = $names[$id];
	  if (!isset($id) || !isset($name)) {
	    $error .= "You didn't select a candidate.<br>\n";
	  }
	}

	if (!isset($email) || (isset($emailpattern) && !preg_match($emailpattern, $email))) {
		$error .= "You didn't provide a valid email address. ".$emailpatterndesc."<br>\n";
	}
	
	// some common email aliases
	/*
	$uclinkpattern = '/^[^@]+@uclink.berkeley.edu$/';
	if (preg_match($uclinkpattern, $email)) {
		$error .= "For Uclink email addresses, please use name@berkeley.edu instead of name@uclink.berkeley.edu.<br>";
	}
	*/
	
	if ($error) {
		print "Errors are found. Please press the 'back' button of your browser and correct them.<br><font style='color:red'>";
		print "$error";
		print "</font>";
		exit;
	}
	
	// modify the users file
	// file format:
	// 1st line: <committed_vote> <committed_ticket>
	//           or "_ _" if not committed yet
	// each following line is: <vote> <ticket_id>
	// the 'vote' is the ID of the voted for candidate for 'multi' elections
	// or 'support' or 'notsupport' for 'single' elections
	$datafile = $datadir.$email;
	if (file_exists($datafile)) {
		$fp = fopen($datafile, "r+");
		flock ($fp, LOCK_EX) or die("Cannot lock data file. Please try again");
		// read in file content
		list($c_ticket,$c_id,$votes)=loadvotes($fp);
		if ($c_ticket!='_') {
			die("You have already confirmed your vote. Cannot vote again.");
		}
	} else {
		// a race here - the file could be created after we check whether it existed
		$fp = fopen($datafile, "w+");
		flock ($fp, LOCK_EX) or die("Cannot lock data file. Please try again");
		$votes=array();
		$c_ticket=$c_id='_';
	}

	// generate a new ticket
	$ticket = newticket();
	$votes[$ticket] = $id;
	
	savevotes($fp,$c_ticket,$c_id,$votes);
	
	flock($fp, LOCK_UN);
	fclose($fp);
	
	// Send email to the user
	$e = urlencode($email);
	$confirmurl = $urlbase."elect.php?act=confirm&email=$e&ticket=$ticket";
	if ($type == 'single') {
	  $votemessage = "You intended to vote ". ($id == 'support' ? "FOR" : "AGAINST"). 
	    " candidate: \n\n";
	} else
	{
	  $votemessage = "You intended to vote for candidate: \n\n";
	 }
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$fromemail.'\n'."\r\n";
	$headers .=  'Reply-To: '.$fromemail.'\n'."\r\n";
	$done = mail($email, "Instructions to complete your ".$electionname." vote.",
					"Dear member,\n\n".
					"This email contains instructions for you to complete your vote in ".$electionname.".\n".
					$votemessage.
					"      $name\n\n".
					"To confirm that this is correct, please click the following link:\n\n".
					"      $confirmurl\n\n".
					"(If the address is not clickable in your email client, copy&paste it into your\n".
					"browser's address box)\n\n".
					"Your vote is *NOT* complete until you click this link.\n\n".
					"Thank you very much.\n\n".
					"Yours,\n".
					$electionname."\n",
					$headers
					);
	if (!$done) {
		echo ("Cannot send email to $email. Please try again.");
	} else {
		echo "Your vote has been recorded <em>tentatively</em>. A confirmation email has been ".
		"sent to $email with instructions for completing your vote. You can close your browser window now.\n";
	}

//
// confirm a vote
//
} else if ((isset($_POST['submit']))&& $act=="confirm") {
	$ticket = $_REQUEST['ticket'];
	if (!email || !$ticket) {
		die("Incomplete data!");
	}

	$datafile = $datadir.$email;
	if (file_exists($datafile)) {
		$fp = fopen($datafile, "r+");
		flock ($fp, LOCK_EX) or die("Cannot lock data file. Please try again");
		list($c_ticket,$c_id,$votes) = loadvotes($fp);
		$id = $votes[$ticket];
		if ($type == 'single') {
		  $name = $names[$ids[0]];
		} else {
		  $name = $names[$id];
		}
		if (!$id || !$name) {
			print ("Cannot find this ticket. Your vote is not confirmed. Please vote again and contact administrator if problem persists.");
		} else if ($c_ticket != "_") {
			print ("You have already confirmed your vote. No changes are made. Read the original confirmation email for details.");
		} else {
			$c_ticket = $ticket;
			$c_id = $id;
			savevotes($fp,$c_ticket,$c_id,$votes);
			if ($type == 'single') {
			  $votemsg = $id == "support" ? "<b>FOR</b>" : "<b>AGAINST</b>";
			} else
			  $votemsg = "for";
			print "<b>Congratulations</b>! Your vote $votemsg candidate $name has been confirmed. Your voting process is finished.".
					" You may close your browser window now.";
			}		
		flock ($fp, LOCK_UN);
		fclose($fp);
	} else {
		print ("Cannot open data file!");
	}

}
?>