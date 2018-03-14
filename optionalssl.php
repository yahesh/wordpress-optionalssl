<?php
  /*
    Plugin Name: OptionalSSL
    Plugin URI: http://yahe.sh/
    Description: If the page is called via SSL all internal links are rewritten.
    Version: 0.1c1
    Author: Yahe
    Author URI: http://yahe.sh/

    this code is based on http://w-shadow.com/blog/2010/05/20/how-to-filter-the-whole-page-in-wordpress/
  */

  /* STOP EDITING HERE IF YOU DO NOT KNOW WHAT YOU ARE DOING */

  $optionalssl_after  = "after";
  $optionalssl_before = "before";

  $optionalssl_replacers = array(array($optionalssl_before => htmlspecialchars("http://".$_SERVER["HTTP_HOST"]),
                                       $optionalssl_after  => htmlspecialchars("https://".$_SERVER["HTTP_HOST"])));

  function optionalssl_startbuffering(){
    // do not filter admin pages
    if (!is_admin()){
      // we start buffering here but do not
      // stop it ourself - this is done
      // automatically in "wp_ob_end_flush_all()" in
      // file "/wp-includes/functions.php"
      ob_start("optionalssl_filterpage");
    }
  }

  function optionalssl_replaceslashes($html) {
    return str_ireplace("/", "%2F", $html);
  }

  function optionalssl_filterpage($html) {
    global $optionalssl_after;
    global $optionalssl_before;
    global $optionalssl_replacers;
 
    foreach ($optionalssl_replacers as $item) {
      $html = str_ireplace($item[$optionalssl_before], $item[$optionalssl_after], $html);
      $html = str_ireplace(optionalssl_replaceslashes($item[$optionalssl_before]), optionalssl_replaceslashes($item[$optionalssl_after]), $html);
    }

    return $html;
  }

  if (is_ssl()) {
    add_action("wp", "optionalssl_startbuffering");
  }
?>
