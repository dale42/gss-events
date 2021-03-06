<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://group42.ca
 * @since      0.1.0
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/admin/templates
 */
?>

<h1>Google Spreadsheet Events Configuration</h1>

<?php if ($message_area): ?>
  <div class="gss-events-message">
    <?php print $message_area ?>
  </div>
<?php endif; ?>


<form id="gss-events-config" method="POST">

  <label for="gss_url">Google Spreadsheet URL:</label>

  <input type="text" name="gss_url" id="gss-events-gss-url" value="<?php print $gss_url; ?>">

  <?php wp_nonce_field( 'gss_events_config_nonce_action', 'config_hash' ); ?>

  <input type="submit" value="Save" class="button button-primary button-large">

</form>

<?php if ($sample_content): ?>

  <div class="gss-event-sample-content-area">

    <h2>Retrieved Information</h2>

    <div class="gss-event-display-box">
      <?php print $sample_content; ?>
    </div>

  </div>

<?php endif; ?>
