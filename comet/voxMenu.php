<?php 
require_once 'AWSSDKforPHP/sdk.class.php';

function getVoxCommand($session_id) {
	$table_name = 'vox-sessions';
	$dynamodb = new AmazonDynamoDB();

	// Get an item
	$response = $dynamodb->get_item(array(
		'TableName' => $table_name,
		'Key' => array(
			'HashKeyElement' => array(AmazonDynamoDB::TYPE_STRING => $session_id)
		),
		'AttributesToGet' => 'command'
	));
	 
	// Check for success...
	if ($response->isOK()) {
		return $response->body->Item->command->S;
	} else {
		return false;
	}
}


function putVoxCommand($session_id,$command) {
	$table_name = 'vox-sessions';
	$dynamodb = new AmazonDynamoDB();
	
	// Set up batch requests
	$queue = new CFBatchRequest();
	$queue->use_credentials($dynamodb->credentials);
	 
	// Add items to the batch
	$dynamodb->batch($queue)->put_item(array(
		'TableName' => $table_name,
		'Item' => array(
			'session id'    => array( AmazonDynamoDB::TYPE_STRING => $session_id  ), // Primary (Hash) Key
			'command'       => array( AmazonDynamoDB::TYPE_STRING => $command)
		)
	));	
	
	$responses = $dynamodb->batch($queue)->send();

	if ($responses->areOK()) {	
		return true;
	} else {
		return false;
	}
	
}


?>