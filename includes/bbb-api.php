<?php
require_once "config.php";

class BigBlueButton
{

	private $_securitySalt;
	private $_bbbServerBaseUrl;

	function __construct()
	{
		$this->_securitySalt 		= CONFIG_SECURITY_SALT;
		$this->_bbbServerBaseUrl 	= CONFIG_SERVER_BASE_URL;
	}

	private function _processXmlResponse($url)
	{
		if (extension_loaded('curl')) {
			$ch = curl_init();
			$timeout = 10;
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);

			if ($data)
				return (new SimpleXMLElement($data));
			else
				return false;
		}
		return (simplexml_load_file($url));
	}

	private function _requiredParam($param)
	{
		if ((isset($param)) && ($param != '')) {
			return $param;
		} elseif (!isset($param)) {
			throw new Exception('Missing parameter.');
		} else {
			throw new Exception('' . $param . ' is required.');
		}
	}

	private function _optionalParam($param)
	{
		if ((isset($param)) && ($param != '')) {
			return $param;
		} else {
			$param = '';
			return $param;
		}
	}

	public function getCreateMeetingUrl($creationParams)
	{
		$this->_meetingId = $this->_requiredParam($creationParams['meetingId']);
		$this->_meetingName = $this->_requiredParam($creationParams['meetingName']);
		$creationUrl = $this->_bbbServerBaseUrl . "api/create?";
		$params =
			'name=' . urlencode($this->_meetingName) .
			'&meetingID=' . urlencode($this->_meetingId) .
			'&attendeePW=' . urlencode($creationParams['attendeePw']) .
			'&moderatorPW=' . urlencode($creationParams['moderatorPw']) .
			'&dialNumber=' . urlencode($creationParams['dialNumber']) .
			'&voiceBridge=' . urlencode($creationParams['voiceBridge']) .
			'&webVoice=' . urlencode($creationParams['webVoice']) .
			'&logoutURL=' . urlencode($creationParams['logoutUrl']) .
			'&maxParticipants=' . urlencode($creationParams['maxParticipants']) .
			'&record=' . urlencode($creationParams['record']) .
			'&duration=' . urlencode($creationParams['duration']) .
			$welcomeMessage = $creationParams['welcomeMsg'];
		if (trim($welcomeMessage))
			$params .= '&welcome=' . urlencode($welcomeMessage);
		return ($creationUrl . $params . '&checksum=' . sha1("create" . $params . $this->_securitySalt));
	}

	public function createMeetingWithXmlResponseArray($creationParams)
	{
		$xml = $this->_processXmlResponse($this->getCreateMeetingURL($creationParams));

		if ($xml) {
			if ($xml->meetingID)
				return array(
					'returncode' => $xml->returncode,
					'message' => $xml->message,
					'messageKey' => $xml->messageKey,
					'meetingId' => $xml->meetingID,
					'attendeePw' => $xml->attendeePW,
					'moderatorPw' => $xml->moderatorPW,
					'hasBeenForciblyEnded' => $xml->hasBeenForciblyEnded,
					'createTime' => $xml->createTime
				);
			else
				return array(
					'returncode' => $xml->returncode,
					'message' => $xml->message,
					'messageKey' => $xml->messageKey
				);
		} else {
			return null;
		}
	}

	public function getJoinMeetingURL($joinParams)
	{
		$this->_meetingId = $this->_requiredParam($joinParams['meetingId']);
		$this->_username = $this->_requiredParam($joinParams['username']);
		$this->_password = $this->_requiredParam($joinParams['password']);

		$joinUrl = $this->_bbbServerBaseUrl . "api/join?";

		$params =
			'meetingID=' . urlencode($this->_meetingId) .
			'&fullName=' . urlencode($this->_username) .
			'&password=' . urlencode($this->_password) .
			'&userID=' . urlencode($joinParams['userId']) .
			'&webVoiceConf=' . urlencode($joinParams['webVoiceConf']);

		if (((isset($joinParams['createTime'])) && ($joinParams['createTime'] != ''))) {
			$params .= '&createTime=' . urlencode($joinParams['createTime']);
		}

		return ($joinUrl . $params . '&checksum=' . sha1("join" . $params . $this->_securitySalt));
	}

	public function getEndMeetingURL($endParams)
	{
		$this->_meetingId = $this->_requiredParam($endParams['meetingId']);
		$this->_password = $this->_requiredParam($endParams['password']);
		$endUrl = $this->_bbbServerBaseUrl . "api/end?";
		$params =
			'meetingID=' . urlencode($this->_meetingId) .
			'&password=' . urlencode($this->_password);
		return ($endUrl . $params . '&checksum=' . sha1("end" . $params . $this->_securitySalt));
	}

	public function endMeetingWithXmlResponseArray($endParams)
	{
		$xml = $this->_processXmlResponse($this->getEndMeetingURL($endParams));
		if ($xml) {
			return array(
				'returncode' => $xml->returncode,
				'message' => $xml->message,
				'messageKey' => $xml->messageKey
			);
		} else {
			return null;
		}
	}

	public function getIsMeetingRunningUrl($meetingId)
	{
		$this->_meetingId = $this->_requiredParam($meetingId);
		$runningUrl = $this->_bbbServerBaseUrl . "api/isMeetingRunning?";
		$params =
			'meetingID=' . urlencode($this->_meetingId);
		return ($runningUrl . $params . '&checksum=' . sha1("isMeetingRunning" . $params . $this->_securitySalt));
	}

	public function isMeetingRunningWithXmlResponseArray($meetingId)
	{
		$xml = $this->_processXmlResponse($this->getIsMeetingRunningUrl($meetingId));
		if ($xml) {
			return array(
				'returncode' => $xml->returncode,
				'running' => $xml->running 	// -- Returns true/false.
			);
		} else {
			return null;
		}
	}

	public function getGetMeetingsUrl()
	{
		$getMeetingsUrl = $this->_bbbServerBaseUrl . "api/getMeetings?checksum=" . sha1("getMeetings" . $this->_securitySalt);
		return $getMeetingsUrl;
	}

	public function getMeetingsWithXmlResponseArray()
	{
		$xml = $this->_processXmlResponse($this->getGetMeetingsUrl());
		if ($xml) {
			if ($xml->returncode != 'SUCCESS') {
				$result = array(
					'returncode' => $xml->returncode
				);
				return $result;
			} elseif ($xml->messageKey == 'noMeetings') {
				$result = array(
					'returncode' => $xml->returncode,
					'messageKey' => $xml->messageKey,
					'message' => $xml->message
				);
				return $result;
			} else {
				$result = array(
					'returncode' => $xml->returncode,
					'messageKey' => $xml->messageKey,
					'message' => $xml->message
				);
				foreach ($xml->meetings->meeting as $m) {
					$result[] = array(
						'meetingId' => $m->meetingID,
						'meetingName' => $m->meetingName,
						'createTime' => $m->createTime,
						'attendeePw' => $m->attendeePW,
						'moderatorPw' => $m->moderatorPW,
						'hasBeenForciblyEnded' => $m->hasBeenForciblyEnded,
						'running' => $m->running
					);
				}
				return $result;
			}
		} else {
			return null;
		}
	}

	public function getMeetingInfoUrl($infoParams)
	{
		$this->_meetingId = $this->_requiredParam($infoParams['meetingId']);
		$this->_password = $this->_requiredParam($infoParams['password']);
		$infoUrl = $this->_bbbServerBaseUrl . "api/getMeetingInfo?";
		$params =
			'meetingID=' . urlencode($this->_meetingId) .
			'&password=' . urlencode($this->_password);
		return ($infoUrl . $params . '&checksum=' . sha1("getMeetingInfo" . $params . $this->_securitySalt));
	}

	public function getMeetingInfoWithXmlResponseArray($infoParams)
	{
		$xml = $this->_processXmlResponse($this->getMeetingInfoUrl($infoParams));
		if ($xml) {
			if (($xml->returncode != 'SUCCESS') || ($xml->messageKey == null)) {
				$result = array(
					'returncode' => $xml->returncode,
					'messageKey' => $xml->messageKey,
					'message' => $xml->message
				);
				return $result;
			} else {
				$result = array(
					'returncode' => $xml->returncode,
					'meetingName' => $xml->meetingName,
					'meetingId' => $xml->meetingID,
					'createTime' => $xml->createTime,
					'voiceBridge' => $xml->voiceBridge,
					'attendeePw' => $xml->attendeePW,
					'moderatorPw' => $xml->moderatorPW,
					'running' => $xml->running,
					'recording' => $xml->recording,
					'hasBeenForciblyEnded' => $xml->hasBeenForciblyEnded,
					'startTime' => $xml->startTime,
					'endTime' => $xml->endTime,
					'participantCount' => $xml->participantCount,
					'maxUsers' => $xml->maxUsers,
					'moderatorCount' => $xml->moderatorCount,
				);
				foreach ($xml->attendees->attendee as $a) {
					$result[] = array(
						'userId' => $a->userID,
						'fullName' => $a->fullName,
						'role' => $a->role
					);
				}
				return $result;
			}
		} else {
			return null;
		}
	}

	public function getRecordingsUrl($recordingParams)
	{
		$recordingsUrl = $this->_bbbServerBaseUrl . "api/getRecordings?";
		$params =
			'meetingID=' . urlencode($recordingParams['meetingId']);
		return ($recordingsUrl . $params . '&checksum=' . sha1("getRecordings" . $params . $this->_securitySalt));
	}

	public function getRecordingsWithXmlResponseArray($recordingParams)
	{
		$xml = $this->_processXmlResponse($this->getRecordingsUrl($recordingParams));
		if ($xml) {
			if (($xml->returncode != 'SUCCESS') || ($xml->messageKey == null)) {
				$result = array(
					'returncode' => $xml->returncode,
					'messageKey' => $xml->messageKey,
					'message' => $xml->message
				);
				return $result;
			} else {
				$result = array(
					'returncode' => $xml->returncode,
					'messageKey' => $xml->messageKey,
					'message' => $xml->message
				);

				foreach ($xml->recordings->recording as $r) {
					$result[] = array(
						'recordId' => $r->recordID,
						'meetingId' => $r->meetingID,
						'name' => $r->name,
						'published' => $r->published,
						'startTime' => $r->startTime,
						'endTime' => $r->endTime,
						'playbackFormatType' => $r->playback->format->type,
						'playbackFormatUrl' => $r->playback->format->url,
						'playbackFormatLength' => $r->playback->format->length,
						'metadataTitle' => $r->metadata->title,
						'metadataSubject' => $r->metadata->subject,
						'metadataDescription' => $r->metadata->description,
						'metadataCreator' => $r->metadata->creator,
						'metadataContributor' => $r->metadata->contributor,
						'metadataLanguage' => $r->metadata->language,
					);
				}
				return $result;
			}
		} else {
			return null;
		}
	}

	public function getPublishRecordingsUrl($recordingParams)
	{
		$recordingsUrl = $this->_bbbServerBaseUrl . "api/publishRecordings?";
		$params =
			'recordID=' . urlencode($recordingParams['recordId']) .
			'&publish=' . urlencode($recordingParams['publish']);
		return ($recordingsUrl . $params . '&checksum=' . sha1("publishRecordings" . $params . $this->_securitySalt));
	}

	public function publishRecordingsWithXmlResponseArray($recordingParams)
	{
		$xml = $this->_processXmlResponse($this->getPublishRecordingsUrl($recordingParams));
		if ($xml) {
			return array(
				'returncode' => $xml->returncode,
				'published' => $xml->published 	// -- Returns true/false.
			);
		} else {
			return null;
		}
	}

	public function getDeleteRecordingsUrl($recordingParams)
	{
		$recordingsUrl = $this->_bbbServerBaseUrl . "api/deleteRecordings?";
		$params =
			'recordID=' . urlencode($recordingParams['recordId']);
		return ($recordingsUrl . $params . '&checksum=' . sha1("deleteRecordings" . $params . $this->_securitySalt));
	}

	public function deleteRecordingsWithXmlResponseArray($recordingParams)
	{
		$xml = $this->_processXmlResponse($this->getDeleteRecordingsUrl($recordingParams));
		if ($xml) {
			return array(
				'returncode' => $xml->returncode,
				'deleted' => $xml->deleted 	// -- Returns true/false.
			);
		} else {
			return null;
		}
	}
} // END OF BIGBLUEBUTTON CLASS
