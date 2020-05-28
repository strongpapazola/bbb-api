<?php

require_once('../includes/bbb-api.php');


$bbb = new BigBlueButton();

$joinParams = array(
	'meetingId' => '1234', 				// REQUIRED - We have to know which meeting to join.
	'username' => 'Test Moderator',		// REQUIRED - The user display name that will show in the BBB meeting.
	'password' => 'mp',					// REQUIRED - Must match either attendee or moderator pass for meeting.
	'createTime' => '',					// OPTIONAL - string
	'userId' => '',						// OPTIONAL - string
	'webVoiceConf' => ''				// OPTIONAL - string
);

// Get the URL to join meeting:
$itsAllGood = true;
try {
	$result = $bbb->getJoinMeetingURL($joinParams);
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
	//Output results to see what we're getting:
	//print_r($result);
	echo '{"url":"'.$result.'"}';
}
