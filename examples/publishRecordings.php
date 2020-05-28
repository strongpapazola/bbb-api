<?php

require_once('../includes/bbb-api.php');


$bbb = new BigBlueButton();

$recordingParams = array(
	'recordId' => '8cb2237d0679ca88db6464eac60da96345513964-1333379469215', 			
	'publish' => 'true',		// REQUIRED - To publish or not to publish.

);

$itsAllGood = true;
try {$result = $bbb->publishRecordingsWithXmlResponseArray($recordingParams);}
	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
		$itsAllGood = false;
	}

if ($itsAllGood == true) {
	echo json_encode($result);
}
