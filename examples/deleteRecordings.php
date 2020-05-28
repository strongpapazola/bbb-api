<?php

require_once('../includes/bbb-api.php');


$bbb = new BigBlueButton();

$recordingParams = array(
	// REQUIRED - We have to know which recording:
	'recordId' => '8cb2237d0679ca88db6464eac60da96345513964-1333379469215',

);

// Delete the meeting:
$itsAllGood = true;
try {
	$result = $bbb->deleteRecordingsWithXmlResponseArray($recordingParams);
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
	//Output results to see what we're getting:
	echo json_encode($result);
}
