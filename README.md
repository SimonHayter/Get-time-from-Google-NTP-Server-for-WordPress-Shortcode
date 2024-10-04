# NTP Get Time Shortcode for WordPress

This will get the worlds time from Google's NTP time server and then output the time using a return/echo on the page. 

Edit your functions.php and add the code found in the main file.

To use the function simply use any of the methods below:

1. Within the contents of a page or post use the short code: [get_time]
2. Methods to to use in footer.php or header.php use do_shortcode('uk_time'); or <?= do_shortcode('uk_time'); ?> or ' .do_shortcode('uk_time') .' Depending on your code
3. If you wish to change the time zone you can find the time zones here: https://en.wikipedia.org/wiki/List_of_tz_database_time_zones and update the functions.php
4. If you wish to change the time stamp output edit the functions and use this as a guide to match your country default: https://www.php.net/manual/en/datetime.format.php
