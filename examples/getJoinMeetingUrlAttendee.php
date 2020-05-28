<?php

require_once('../includes/bbb-api.php');


$bbb = new BigBlueButton();

$joinParams = array(
	'meetingId' => 'cc683f8019daf754a711a115b0cee10c52119ec1', 			// REQUIRED - We have to know which meeting to join.
	'username' => 'krypt0n',	// REQUIRED - The user display name that will show in the BBB meeting.
	'password' => 'JuUVXaJxbIYf',				// REQUIRED - Must match either attendee or moderator pass for meeting.
	'createTime' => '',				// OPTIONAL - string waktu
	'userId' => '',					// OPTIONAL - string user id
);

$itsAllGood = true;
try {
	$result = $bbb->getJoinMeetingURL($joinParams);
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
//	print_r($result);
	echo '{"url":"'.$result.'"}';
}
