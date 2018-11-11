<?php

/**
 * Provide a template for displaying full details of an event item
 *
 * This file is used to markup the display card of the event item's full
 * details.
 *
 * @link       https://group42.ca
 * @since      0.1.2
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/public/templates
 */

$day = date('j', $event['Start Date']);
$month_year = date('M Y', $event['Start Date']);
$event_name = (empty($event['URL'])) ? $event['Event Title'] : "<a href=\"{$event['URL']}\" target=\"_blank\">{$event['Event Title']}</a>";
$details_url = (empty($event['URL'])) ? '' : "<a href=\"{$event['URL']}\" target=\"_blank\">{$event['URL']}</a>";
?>
<article class="gss-events-item-card">

  <div class="gss-events-date-box">
    <div class="gss-events-date-box-inner">
      <span class="gss-events-day"><?php print $day ?></span>
      <span class="gss-events-month-year"><?php print $month_year ?></span>
    </div>
  </div>

  <div class="gss-events-item-content">
    <h2 class="gss-events-item-title"><?php print $event_name ?></h2>

    <div><strong>Date:</strong> <?php print $event['Display Date'] ?></div>
    <p><strong>Location:</strong> <?php print $event['Location'] ?></p>

    <p><?php print $event['Description'] ?></p>

    <?php if ($details_url): ?>
      <p><strong>Details:</strong> <?php print $details_url ?></p>
    <?php endif; ?>
  </div>

</article>
