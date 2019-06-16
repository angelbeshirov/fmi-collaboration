<?php
// prevent the server from timing out
set_time_limit(0);

// include the web sockets server script (the server is started at the far bottom of this file)
require "class.PHPWebSocket.php";
require "action.php";
require "database_manager.php";

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {

	global $Server;
	$ip = long2ip($Server->wsClients[$clientID][6]);

	// check if message length is 0
	// if ($messageLength == 0) {
	// 	$Server->wsClose($clientID);
	// 	return;
	// }

	// message must be in the format
	// {
	// 		type: <Init/Update Users/Insert/Delete>
	// 		value: depending on the type (some validation might be added in future)
	// }
	$messageObj = json_decode($message, true);

	switch ($messageObj["type"]) {
		case Type::INITIALIZE_TYPE:
			$fileID = $messageObj["file_id"];
			$Server->log("Received initialize message for fileID " . $fileID);
			$Server->wsClients[$clientID][12] = $fileID;

			if (isset($Server->wsClientFileCount[$fileID]) && $Server->wsClientFileCount[$fileID] >= 1) {
				$Server->wsClientFileCount[$fileID]++;
			}
			else {
				$Server->wsClientFileCount[$fileID] = 1;
			}

			$Server->log("Set client count for " . $fileID . " to " . $Server->wsClientFileCount[$fileID]);

			foreach($Server->wsClients as $id => $client) {
				if($client[12] != 0 && $client[12] === $fileID) {
					$Server->wsSend($id, json_encode(new UpdateUsers($Server->wsClientFileCount[$fileID])));
				}
			}
			break;
		case Type::DELETE_TYPE:
			$fileID = $messageObj["file_id"];
			$from = $messageObj["from"];
			$to = $messageObj["to"];

			foreach($Server->wsClients as $id => $client) {
				if(isset($client[12]) && $client[12] === $fileID && $id != $clientID) {
					$Server->wsSend($id, json_encode(new Delete($from, $to)));
				}
			}
			break;
		case Type::INSERT_TYPE:
			$fileID = $messageObj["file_id"];
			$position = $messageObj["position"];
			$data = $messageObj["data"];

			foreach($Server->wsClients as $id => $client) {
				if(isset($client[12]) && $client[12] === $fileID && $id != $clientID) {
					$Server->wsSend($id, json_encode(new Insert($position, $data)));
				}
			}
			break;
	}
}

// when a client connects
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip($Server->wsClients[$clientID][6]);

	$Server->log("$ip ($clientID) has connected.");
	$Server->log("Current number of connected users is:" . $Server->wsClientCount);
	$Server->log("Sending init request");

	$Server->wsSend($clientID, json_encode(new Init()));

	// Send to everyone the new count of users
	// foreach($Server->wsClients as $id => $client) {
	// 	$Server->wsSend($id, json_encode(new UpdateUsers($Server->wsClientCount)));
	// }
		// if ($id != $clientID)
			// $Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip($Server->wsClients[$clientID][6]);

	$Server->log("$ip ($clientID) has disconnected.");

	$Server->log("Current number of connected users is:" . $Server->wsClientCount);

	$fileID = $Server->wsClients[$clientID][12];

	$Server->log("User for file :" . $fileID . " has disconnected");

	if ($Server->wsClientFileCount[$fileID] > 1) {
		$Server->wsClientFileCount[$fileID]--;
	}
	else {
		unset($Server->wsClientFileCount[$fileID]);
	}

	if(isset($Server->wsClientFileCount[$fileID])) {
		$Server->log("Current number of connected users for file:" . $fileID . " is " . $Server->wsClientFileCount[$fileID]);
	} else {
		$Server->log("All clients for file:" . $fileID . " disconnected");
	}
	

	// Send to everyone the new count of users for this file
	foreach($Server->wsClients as $id => $client) {
		if(isset($client[12]) && $client[12] === $fileID && $id != $clientID) {
			$Server->wsSend($id, json_encode(new UpdateUsers($Server->wsClientFileCount[$fileID])));
		}
	}

	//Send a user left notice to everyone in the room
	// foreach ( $Server->wsClients as $id => $client )
		// $Server->wsSend($id, "Visitor $clientID ($ip) has left the room.");
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
$Server->wsStartServer('127.0.0.1', 9300);

?>