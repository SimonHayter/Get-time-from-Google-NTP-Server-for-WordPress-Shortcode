function get_time_shortcode() {
	$ntpServers = ['time1.google.com', 'time2.google.com', 'time3.google.com', 'time4.google.com'];

	foreach ($ntpServers as $ntpServer) {
		
		// Use error handling instead of suppression
		if ($socket = fsockopen($ntpServer, 123, $errNo, $errStr, 1)) { 
			fputs($socket, "\x1b" . str_repeat("\0", 47));
			$response = fread($socket, 48);
			fclose($socket);

			// Validate the response data before using it
			if (strlen($response) === 48) { 
				date_default_timezone_set('Europe/London');
				$currentTime = date('d/m/Y H:i:s', unpack('N12', $response)[9] - 2208988800);
				return "<p>The current time in the UK is: " . $currentTime . "</p>";

			// Else will trigger a log event	
			} else {
				// Log the error or handle it appropriately
				error_log("Invalid response from NTP server: $ntpServer"); 
			}
		} else {
		// Log the connection error
			error_log("Error connecting to NTP server: $ntpServer - $errStr ($errNo)"); 
		}
	}
	return "<p>Error: Could not retrieve time.</p>"; 
}
add_shortcode('get_time', 'get_time_shortcode');
