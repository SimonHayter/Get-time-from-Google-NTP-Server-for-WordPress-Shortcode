function uk_time_shortcode() {
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

add_shortcode('uk_time', 'uk_time_shortcode');
