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


**Resolving the Issue**
If the [get_time] shortcode fails to display the correct time, it's likely due to a firewall restriction on your server.  NTP (Network Time Protocol) requires port 123 to be open for communication with time servers.

You have several options to resolve this:

***Direct Server Access (Root/Sudo)***

If you have root or sudo access to your server, you can open port 123 directly using the following commands:

Generic Linux (IPtables):

	Bash
	iptables -A INPUT -p udp --dport 123 -j ACCEPT
	iptables -A OUTPUT -p udp --sport 123 -j ACCEPT
	# Use code with caution.

Ubuntu Linux (UFW):

	Bash
	sudo ufw allow ntp 
	# Use code with caution.

***Contact Your Hosting Provider***

If you do not manage your server directly, contact your hosting provider and request that they open port 123 for your domain.  Please note that some hosting providers may have restrictions on opening specific ports.

***Use Server Time***

If direct server access or opening port 123 is not feasible, you can utilize the server's time with a time zone adjustment.  Refer to the list of time zones (https://en.wikipedia.org/wiki/List_of_tz_database_time_zones) to find the appropriate time zone code for your region.  You'll need to modify the shortcode function to use the server's time and apply the necessary time zone offset.  The updated shortcode will be [server_time].   

***Error Indication***

If port 123 is closed, you'll likely see an error message similar to this:  [Insert Screenshot of Error Message Here - If Available]

You can also check your server's error log for more detailed information about the issue.



**Not Working**

The main reason the code will fail to work if your server has a firewall and port 123 is closed which is required to be open to be able to communicate with any NTP time server. To fix this issue the server port needs to be open. Enabling display errors in the PHP will show the error but alternatively you will find the error in the error log. The error will look like this:

Port Closed Error 1:

	Warning: fsockopen(): Unable to connect to time1.google.com:123 (Connection timed out) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50
	Warning: fsockopen(): Unable to connect to time2.google.com:123 (Connection timed out) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50
	Warning: fsockopen(): Unable to connect to time3.google.com:123 (Connection timed out) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50
	Warning: fsockopen(): Unable to connect to time4.google.com:123 (Connection timed out) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50

Port Closed Error 2:

	Warning: fsockopen(): Unable to connect to time1.google.com:123 (Network is unreachable) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50
	Warning: fsockopen(): Unable to connect to time2.google.com:123 (Network is unreachable) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50
	Warning: fsockopen(): Unable to connect to time3.google.com:123 (Network is unreachable) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50
	Warning: fsockopen(): Unable to connect to time4.google.com:123 (Network is unreachable) in /home/username/example.co.uk/wp-content/themes/ext/functions.php on line 50


You have 3 methods to choose from below:

**You have ROOT/SUDO access**

Generic Linux: IPtables use 

	iptables -A INPUT -p udp --dport 123 -j ACCEPT
	iptables -A OUTPUT -p udp --sport 123 -j ACCEPT

Ubuntu Linux: UFW

	sudo ufw allow ntp 

**Create a Ticket**

If you do not operate your own server then you will need your webhost to open port 123 on your domain. Simply create a ticket, mentioning your domain name and ask for the port 123 to be opened for you to use NTP time servers. Please note that not all webhosts will open ports, some do, some don't. If yours refuses to do so then use the method below.

**Server Time**

If you do not have root/sudo access, and the website host will not open the port 123 then sadly your out of luck using the most accurate get time method. You will need to use server time and off-set if required using your country code using the zones at [https://en.wikipedia.org/wiki/List_of_tz_database_time_zones](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones). Below is the new code and take note the shortcode is different. 

	function server_time_shortcode($atts = [], $content = null) {
	  // Sanitize the content (if any)
	  $content = wp_kses_post($content); 
	
	  // Set the default timezone to London
	  date_default_timezone_set('Europe/London');
	
	  // Get the current time in the UK
	  $ukTime = date('d/m/Y H:i:s');
	
	  // Return the time in an HTML paragraph with sanitized content
	  return "<p>The current time in the UK is: " . $ukTime . "</p>" . $content;
	}
	
	// Register the shortcode
	add_shortcode('server_time', 'server_time_shortcode'); 

Use short code [server_time]


