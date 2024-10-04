# NTP Get Time Shortcode for WordPress

This will get the worlds time from Google's NTP time server and then output the time using a return/echo on the page. 

Edit your functions.php and add the following code:

	function get_time_shortcode() {
	  // Google's public NTP servers
	  $ntpServers = [
	    'time1.google.com',
	    'time2.google.com',
	    'time3.google.com',
	    'time4.google.com'
	  ];

	  // Try each server until we get a successful response
	  foreach ($ntpServers as $ntpServer) {
	    $socket = @fsockopen($ntpServer, 123, $errNo, $errStr, 1);
	    if ($socket) {
	      fputs($socket, "\x1b" . str_repeat("\0", 47));
	      $response = fread($socket, 48);
	      fclose($socket);

	      // Extract the timestamp from the response
	      $timestamp = unpack('N12', $response);
	      $timestamp = $timestamp[9] - 2208988800; // Convert NTP timestamp to Unix timestamp

	      // Set the timezone to London
	      date_default_timezone_set('Europe/London');

	      // Format the time in UK standard
	      $currentTime = date('d/m/Y H:i:s', $timestamp);
	      return "<p>The current time in the UK is: " . $currentTime . "</p>";
	    }
	  }

	  // If no server responds, return an error message
	  return "<p>Error: Could not retrieve time from Google NTP server.</p>";
	}

To use the function simply use any of the methods below:

1. Within the contents of a page or post use the short code: [get_time]
2. Methods to to use in footer.php or header.php use do_shortcode('uk_time'); or <?= do_shortcode('uk_time'); ?> or ' .do_shortcode('uk_time') .' Depending on your code
3. If you wish to change the time zone you can find the time zones here: https://en.wikipedia.org/wiki/List_of_tz_database_time_zones and update the functions.php
4. If you wish to change the time stamp output edit the functions and use this as a guide to match your country default: https://www.php.net/manual/en/datetime.format.php
