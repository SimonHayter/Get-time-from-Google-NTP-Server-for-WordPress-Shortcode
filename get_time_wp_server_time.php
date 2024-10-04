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
