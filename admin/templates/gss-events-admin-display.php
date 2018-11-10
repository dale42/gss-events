<?php

/**
 * Provide a admin area view for the plugin
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

<?php if ($message): ?>
  <div class="gss-events-message">
    <?php print $message ?>
  </div>
<?php endif; ?>


<form method="POST">
  <label for="gss_url">Google Spreadsheet URL</label>

  <input type="text" name="gss_url" id="gss_url" value="<?php echo $value; ?>">

  <?php wp_nonce_field( 'gss_events_config_nonce_action', 'config_hash' ); ?>

  <input type="submit" value="Save" class="button button-primary button-large">
</form>

