<?php 
require_once 'voxMenu.php';

// How often to poll, in microseconds (1,000,000 µs equals 1 s)
define('MESSAGE_POLL_MICROSECONDS', 500000);
 
// How long to keep the Long Poll open, in seconds
define('MESSAGE_TIMEOUT_SECONDS', 30);
 
// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
 
// Hold on to any session data you might need now, since we need to close the session before entering the sleep loop
$user_id = 'xyz1234'; //$_SESSION['id'];
 
// Automatically die after timeout (plus buffer)
set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);
 
// Counter to manually keep track of time elapsed (seems crude to rely solely on PHP's timeout)
$counter = MESSAGE_TIMEOUT_SECONDS;
 
$session_id = 'xyz12345';
 
// Poll for messages and hang if nothing is found, until the timeout is exhausted
while($counter > 0)
{
    // Check for new data (not illustrated)
    if($data =  getVoxCommand($session_id))
    {
        // Break out of while loop if new data is populated
        break;
    }
    else
    {
        // Otherwise, sleep for the specified time, after which the loop runs again
        usleep(MESSAGE_POLL_MICROSECONDS);
 
        // Decrement seconds from counter (the interval was set in µs, see above)
        $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
    }
}
 
// If we've made it this far, we've either timed out or have some data to deliver to the client
if(isset($data))
{
    // Send data to client; you may want to precede it by a mime type definition header, eg. in the case of JSON or XML
    echo $data;
}


?>