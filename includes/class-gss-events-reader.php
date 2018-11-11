<?php

/**
 * GSS Events Reader
 *
 * This class defines all code the readers the Google Spreadsheet
 *
 * @since      0.1.0
 * @package    Gss_Events
 * @subpackage Gss_Events/includes
 * @author     Dale McGladdery <dale.mcgladdery@gmail.com>
 */
class Gss_Events_Reader {

  const RESULTS_TRANSIENT_ID = 'gss_events_reader_spreadsheet_raw';

  /**
   * The ID of this plugin.
   *
   * @since    0.1.0
   * @access   private
   * @var      string    $gss_url    The URL of the Google Spreadsheet.
   */
  private $gss_url;

  /**
   * The required spreadsheet header labels.
   *
   * @since    0.1.0
   * @access   private
   * @var      array    $header_labels    The required spreadsheet header labels.
   */
  private $header_labels;

  /**
   * The spreadsheet preamble.
   *
   * @since    0.1.0
   * @access   private
   * @var      array    $gss_preamble      The preamble rows before the header.
   */
  private $gss_preamble;

  /**
   * The spreadsheet preamble.
   *
   * @since    0.1.0
   * @access   private
   * @var      array    $gss_header_row    The header row.
   */
  private $gss_header_row;

  /**
   * The spreadsheet preamble.
   *
   * @since    0.1.0
   * @access   private
   * @var      array    $gss_content_rows  The preamble rows before the header.
   */
  private $gss_content_rows;

  /**
   * Initialize the class and set its properties.
   *
   * @since    0.1.0
   * @param      string    $spreadsheet_url     The spreadsheet URL.
   */
  public function __construct( $spreadsheet_url ) {

    if ( filter_var($spreadsheet_url, FILTER_VALIDATE_URL) ) {
      $this->gss_url = filter_var($spreadsheet_url, FILTER_SANITIZE_URL);
    }
    else {
      throw new Exception('Invalid URL');
    }

    // Initialize header labels.
    $this->header_labels = [
      'Hold', 'Start Date', 'Display Date', 'Event Title', 'Description', 'Location', 'URL'
    ];

  }

  /**
   * Fetch the raw spreadsheet.
   *
   * @since    0.1.0
   * @access   private
   * @return   string    A string containing the contents of the spreadsheet file.
   */
  private function fetch_raw_google_spreadsheet($no_cache = FALSE) {

    $response_body = get_transient( self::RESULTS_TRANSIENT_ID );

    if ($no_cache || $response_body === FALSE) {
      $response = wp_remote_get( $this->gss_url );
      $response_code = wp_remote_retrieve_response_code( $response );
      if ($response_code != 200) {
        throw new Exception("Could not fetch data, response code $response_code");
      }
      $response_body = wp_remote_retrieve_body($response);
      $expiration = 3600; // 1 hour
      set_transient(self::RESULTS_TRANSIENT_ID, $response_body, $expiration);
    }

    return $response_body;

  }

  /**
   * Convert a string of Google spreadsheet CSV data to rows with fields.
   *
   * @since    0.1.0
   * @access   private
   * @param    string         $csv_string      A string of CSV data
   */
  private function parse_csv_string($csv_string) {
    $content_rows = explode("\r\n", $csv_string);

    // Expected format is a title and number of explanation lines, followed by
    // the header.
    $label_count = count($this->header_labels);
    $header_row_number = NULL;
    $header_row_map = [];
    foreach ($content_rows as $index => $row) {
      $fields = str_getcsv($row);
      $common_values = array_intersect($this->header_labels, $fields);
      if (count($common_values) == $label_count) {
        // This should be the header row.
        $header_row_map = $fields;
        $header_row_number = $index;
        break;
      }
    }

    if (is_null($header_row_number)) {
      throw new Exception('Could not find header in spreadsheet');
    }

    $this->gss_preamble     = array_slice($content_rows, 0, $header_row_number);
    $this->gss_header_row   = $header_row_map;
    $this->gss_content_rows = array_slice($content_rows, $header_row_number + 1);
  }

  /**
   * Convert content rows into processed events.
   *
   * @since    0.1.0
   * @access   private
   */
  private function process_content_rows() {

    // Process the fields, throw out anything older than today and sort.
    $event_list = [];
    $today = strtotime('today');
    foreach ($this->gss_content_rows as $row_index => $row) {
      $fields = str_getcsv($row);
      $event = [];
      foreach ($fields as $index => $field_value) {
        $label = $this->gss_header_row[ $index ];
        $event[$label] = sanitize_textarea_field($field_value);
      }
      if (!empty($event['Hold'])) {
        continue;
      }
      $event['Description'] = nl2br($event['Description']);
      $start_time = strtotime($event['Start Date']);
      if ($start_time !== FALSE && $start_time >= $today) {
        $event['Start Date'] = $start_time;
        $sortable_unique_key = "{$start_time}_{$row_index}";
        $event_list[$sortable_unique_key] = $event;
      }
    }
    ksort($event_list);

    return $event_list;

  }

  /**
   * Get the list of required header labels.
   *
   * @since     0.1.0
   * @return    array    A list of header labels.
   */
  public function get_header_labels() {

    return $this->header_labels;

  }

  /**
   * Fetch the events list from the source spreadsheet.
   *
   * @since     0.1.0
   * @return    array    A list of events
   */
  public function fetch_events() {

    $response_body = $this->fetch_raw_google_spreadsheet();

    $this->parse_csv_string($response_body);

    return $this->process_content_rows();

  }

  /**
   * Return a representation of the raw spreadsheet.
   *
   * @since     0.1.0
   * @return    array    A list of events
   */
  public function fetch_preview_data() {

    $results = [
      'raw'       => [],
      'preamble'  => [],
      'header'    => [],
      'content'   => [],
    ];

    $csv_spreadsheet_data = $this->fetch_raw_google_spreadsheet(TRUE);

    try {
      $this->parse_csv_string($csv_spreadsheet_data);
    }
    catch (Exception $e) {
      $raw_rows = explode("\r\n", $csv_spreadsheet_data);
      $results['raw'] = array_slice($raw_rows, 0, 5);
    }

    $results['preamble'] = $this->gss_preamble;
    $results['header']   = $this->gss_header_row;
    $results['content']  = $this->gss_content_rows;

    return $results;

  }

}
