# NTP Get Time Shortcode for WordPress

This WordPress shortcode provides a convenient way to display the current time, fetched directly from Google's highly accurate NTP time servers.  No more relying on your server's potentially wonky clock – give your users the time, the *right* time! (Pun intended, of course.)

**Implementation**

1.  **Edit your `functions.php` file:** Add the code snippet to your theme's `functions.php` file or a custom plugin.

2.  **Display the time:**  You have several options for displaying the time on your website:

    *   **Shortcode:** Simply use the `[get_time]` shortcode within the content of any page or post.
    *   **Theme Integration:**  Use `do_shortcode('[get_time]');` or `echo do_shortcode('[get_time]');` in your `header.php` or `footer.php` file, depending on your theme's structure.

**Customization**

*   **Time Zone:** To display the time in a different time zone, refer to the list of available time zones on Wikipedia ([https://en.wikipedia.org/wiki/List_of_tz_database_time_zones](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones)) and update the `date_default_timezone_set()` function in the code.
*   **Time Format:**  To customize the time format, refer to the PHP documentation for date formatting ([https://www.php.net/manual/en/datetime.format.php](https://www.php.net/manual/en/datetime.format.php)) and adjust the `date()` function accordingly.

**Code**

Add the code snippet to your theme's `functions.php` file or a custom plugin:

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

To use the function simply use any of the methods below:

1. Within the contents of a page or post use the short code: [get_time]
2. Methods to to use in footer.php or header.php use do_shortcode('uk_time'); or <?= do_shortcode('uk_time'); ?> or ' .do_shortcode('uk_time') .' Depending on your code
3. If you wish to change the time zone you can find the time zones here: https://en.wikipedia.org/wiki/List_of_tz_database_time_zones and update the functions.php
4. If you wish to change the time stamp output edit the functions and use this as a guide to match your country default: https://www.php.net/manual/en/datetime.format.php
