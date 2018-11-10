# Google Spreadsheet Events

*Google Spreadsheet Events* is a WordPress plugin that retrieves information from a Google Spreadsheet and displays it on a WordPress site via a widget or shortcodes.

## Description

*Google Spreadsheet Events* fetches information from a Google Spreadsheet, formats it, and displays it on your WordPress website.

* The Google Spreadsheet must be shared to the web
* The spreadsheet will be public if someone knows the URL. If this isn't acceptable this plugin is not a good choice.
* Old events are not displayed
* Events are sorted by date when displayed, they do not have to be in the correct order in the spreadsheet.
* Events are displayed using a shortcode and in a widget 

## Google Spreadsheet

### Layout

The Google Spreadsheet must have the following columns:  

Label | Description
------|------------
Hold | If a valid is present in this column the event is not displayed. This allows for selective disabling of entries while they're being entered.
Start&nbsp;Date | The date the event is listed and sorted by. It must be formatted such that PHP can parse it.
Display&nbsp;Date | The date displayed with the event. It is displayed as a string, so can be a date range or have event times.<br>e.g. Nov 18, 2018, 6pm - 10pm, or Nov 18 - 25, 2018, 9-5 each day 
Event Title | The name of the event.
Description | A description of the event. Can be multiple paragraphs.
Location | The event location. Should be a single line.
URL | A URL for event information.

It can have other columns, and the columns can be in any order.

### Sharing to the web

*To Do: Add sharing information*



## Installation

1. Upload this plugin to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress



## Configuration

1. Get the Spreadsheet sharing URL for the event spreadsheet
1. Go to the plugin adminstratration page
1. Enter the spreadsheet URL and save

## Displaying Events

The following shortcodes are available for displaying events:
