<?php
/**
 * Provide a template for displaying an event item in short form
 *
 * This file is used to markup the short form, or teaser, of an event item. It
 * provides output suitable for a listing.
 *
 * @link       https://group42.ca
 * @since      0.1.2
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/public/templates
 */
?>
<p>
  <div><a href="<?php print $event['URL'] ?>" target="_blank"><strong><?php print $event['Event Title'] ?></a></strong></div>
  <div><strong><?php print $event['Display Date'] ?></strong></div>
  <div><?php print $event['Location'] ?></div>
</p>
