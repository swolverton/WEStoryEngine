=== Calendarize it! for WordPress ===
Author: Alberto Lau (RightHere LLC)
Author URL: http://plugins.righthere.com/calendarize-it/
Tags: WordPress, Calendar, Event, Recurring Events, Arbitrary Recurring Events, Venues, Organizers, jQuery
Requires at least: 3.1
Tested up to: 3.5.2
Stable tag: 2.1.6 rev38527

== CHANGELOG ==
Version 2.1.6 rev38527 - July 26, 2013
* Update: Rollback point: added support for add-ons to customize custom post info types. Initially for Custom Buttons.

Version 2.1.5 rev38499 - July 24,2013
* Update: Add filter for add-ons to be able to add custom post info field rendering methods
* New Feature: Allow adding quick access button with filter

Version 2.1.4 rev38424 - July 22, 2013
* New Feature: Provided alternate accordion script for site themes that are breaking the Twitter Bootstrap accordion used in the Visual CSS Edtior
* Update: Reduced the space between tabs in the Calendar filter so that it is possible to fit more taxonomies.

Version 2.1.3 rev38415 - July 19, 2013
* New Feature: Added support for use of [btn_ical_feed] shortcode in custom fields. Will allow you to create a feed for a single event for Google Calendar or iCal.

Version 2.1.2 rev38110 - July 8, 2013
* Bug Fixed: Tooltip in Firefox was mispositioned

Version 2.1.1 rev37861 - July 1, 2013
* Bug Fixed: Event Sources filter by taxonomy is not working when used in a shortcode
* Bug Fixed: Filter by calendar only works when using the calendar filter button

Version 2.1.0 rev37701 - June 26, 2013
* Update: Behavior change, let the CSS Editor have control of the Event and Venue details boxes
* Update: Center Event and Venue boxes when not set to 100% width

Version 2.0.9 rev37669 - June 25, 2013
* New Feature: Added option (Troubleshooting) to load Javascripts in the footer
* Update: Compatibility fix for some themes where CSS is breaking event positioning in the calendar
* Update: Missing textdomains for Internationalization (translation) has been added

Version 2.0.8 rev37530 - June 21, 2013
* New Feature: Add troubleshooting option to load bootstrap in the footer in an attempt to prevent a jQuery-ui/boostrap conflict with buttons (this is for the CSS Editor)
* Bug Fixed: Visibility check script jQuery dependency.
* Bug Fixed: Line height decimals should not be removed.

Version 2.0.7 rev37479 - June 19, 2013
* Update: Add a default line height to boxes labels
* Bug Fixed: Remove several php warnings
* Update: In wp-admin load js only on rhc admin screens (fixing conflict with Revolution Slider)

Version 2.0.6 rev37191 - June 18, 2013
* Improvement: Added class for a 640px width browser in order to make the calendar navigation and header look nicer in themes where the calendar is inserted on a page with a sidebar.
* New Feature: Added a global option to enable/disable Google Map zoom with mouse wheel.

Version 2.0.5 rev37111 - June 6, 2013
* Bug Fixed: Event and Taxonomy pages not loading content or post info boxes on some themes and plugins that make use of wp_reset_query()
* Bug Fixed: Taxonomy pages should not show external feed (feed from External Event Sources add-on)
* Bug Fixed: Venue Detail Box not showing right venue detail on newly created events
* Update: When creating new posts set the post info box post id to the newly created event.

Version 2.0.4 rev37015 - June 5, 2013
* Bug Fixed: Prevent rhc template loader from taking over non-rhc taxonomy templates.

Version 2.0.3 rev36966 - June 4, 2013
* Update: Add style to compensate for some themes that are breaking the mobile styles on the calendar.
* Update: Compatibility fix, IE8 breaks when upcoming widgets are loaded.

Version 2.0.2 rev36833 - June 1, 2013
* Update: Modified the registration tab so that it uses its own capability. Modified implementation so that Options now require rhc_options instead of manage_options and rhc_license for registration (you need to deactivate and activate the plugin in order to insert he new capabilities)
* Bug Fixed: Problem with [calendarize feed=1] shortcode used with External Event Sources add-on fixed.

Version 2.0.1 rev36824 - May 30, 2013
* Update: Added support for translating values in custom fields, by adding a variable eg. _($instance['Event Details'],'rhc'). You will need to manually add the translation string (this can easily be done if you use our Easy Translation Manager).
* New Feature: Paid Add-ons and Free Add-ons in Downloads (require entering a valid License Key)

Version 2.0.0 rev36624 - May 23, 2013
* New Feature: Added Visual CSS Editor for advanced styling of Calendarize it!
* New Feature: Added Downloads section for installing add-ons and skins (templates)
* New Feature: Allow specifying the zoom value for the Google Map
* New Feature: Detail Venue Box added
* New Feature: Detail Event Box added
* New Feature: Added option to disable a shortcode based on meta_key, implemented this on the venue box and added guy to events to enable/disable venue box
* New Feature: Added .mo and .po files for Italian support
* New Feature: Added metaboxes in wp-admin for Top image on Event Details Page and Event Details box image.
* New Feature: Implemented Top image on Events Details Page and Event Details box (single event template)
* New Feature: Implemented a Visual Layout Selector for custom fields
* New Feature: Implemented new interface for adding custom fields to the Detail Event Box and the Detail Venue Box
* New Feature: Added button to save Default Templates for Detail Event Box and Detail Venue Box
* New Feature: Added button for resetting custom event fields and custom venue fields
* New Feature: Added Contextual Help for Calendarize it!
* New Feature: Added option to enable week numbers and replace the week number label
* New Feature: New navigation for mobile devices (iPhone and Smartphones)
* New Feature: Added Shortcodes [eventpage], [venuepage] and [organizerpage] for use with frameworks like Thesis (Important: The Shortcodes are NOT to be used inside a Post or Page content, as this might crash the website. They are exclusively to be used directly in the templates, or in the Thesis template editor). Install the free add-on "Calendarize it! Content Shortcodes" in order to use the three shortcodes. 
* Update: Moved calendar initialization to the head of the page
* Update: iCal dialog has been rewritten and added option to download .ics file.
* Update: Updated Options Panel to latest version 2.3.1
* Update: fullCalender updated to 1.6.1
* Bug Fixed: Changing the month in the calendar widget was affecting other calendarize instances on the same page
* Bug Fixed: Calendar Widget events spanning more than a day in the next months first days where also rendering in the main month.
* Bug Fixed: First day not considered on the Widget
* Bug Fixed: Compatibility fix. Avoid extending the JS array.prototype object, which in combination with some other plugins seems to overwrite Array methods.

Version 1.3.6 rev36001 - April 24, 2013
* New Feature: Added the taxonomy and terms parameters to the Upcoming Events Widget admin, so that custom taxonomies can be specified as filer in the widget.

Version 1.3.5 rev36001 - April 14 2013
* Bug Fixed: Problem with some parameters in the Upcoming Events Widget Shortcode has been fixed.

Version 1.3.4 rev35967 - April 12, 2013
* Update: Fixed some CSS on Venue and Event Details Page.
* Update: Fixed some CSS related to mobile device support.

Version 1.3.3 rev35961 - April 10, 2013
* New Feature: Allow using the taxonomy and terms argument in the shortcode for upcoming events.
* Update: CSS fixes on the event page and venue page.

Version 1.3.2 rev35791 - April 5, 2013
* Bug Fixed: On events with a long duration, the event was not showing if a repeat date is set, and the event does not start on the same month.

Version 1.3.1 rev35763 - April 4, 2013
* Bug Fixed: Version 2 (template settings) was replacing the Category Archive template.
* Bug Fixed: Use slug in Calendar, Venue or Organizer argument instead of ID.
* Update: Moving map on Venue page
* Update: Event Details Page CSS updated

Version 1.3.0 rev35657 - April 2, 2013
* Update: Fixed issue with responsiveness related to month and navigation
* Update: Fixed issue with Print CSS

Version 1.2.9 rev35442 - March 26, 2013
* Update: Fixed some CSS styling issues on the venue page

Version 1.2.8 rev35248 - March 23, 2013
* New Feature: Add placeholder for print styles and option to disable print styles
* New Feature: Print CSS
* Update: Cleaning up tooltip font size and spacing
* Update: Cleaning up event details page font size and spacing

Version 1.2.7 rev35017 - March 15, 2013
* Bug Fixed: Handle a situation where the event list links where not doing anything when target was not set. Now default is _self.

Version 1.2.6 rev33938 - February 11, 2013
* New Feature: Update Options Panel with Auto Update
* New Feature: Allow download of ics file
* New Feature: Enable shortcode for a single event feed
* New Feature: Enable choosing post types in the upcoming event widgets (this makes the add-on obsolete)
* New Feature: Added styling Modal A option to check more post types and option to choose widget templates
* New Feature: Add additional template widgets where the agenda like data box will not be shown if the same date as previous event date.
* New Feature: Added Modal B widget agents (repeat and no repeat)
* Ne Feature: Implement option to use ajax to fetch events, instead of server side loading of the event in the upcoming events widget
* Update: Adjust fluid content, and fixed agenda box width
* Bug Fixed: Removed debugging code
* Bug Fixed: Missing localization string and incorrect text domain
* Bug Fixed: Correctly disable overlay
* Bug Fixed: When both coordinates and address is set, prefer coordinates
* Bug Fixed: js error when adding a non recurring event
* Bug Fixed: Make the admin show the first day of the week, same as the fronted, and also the labels
* Update: Added new Spanish translation file
* Update: Increase gmap version number to force cache
* Update: Added argument to allow ical feed to display single event
* Update: Force new style.css
* Update: Add a class to differentiate widget calendar from main calendar
* Update: Add specificity to selector to try avoid theme overwriting calendar

Version 1.2.5 rev32988 - January 21, 2013
* Bug Fixed: The old events fix was completely changing the start data of events in the upcoming events.
* Bug Fixed: jQuery.live is depreciated, updated js libraries.

Version 1.2.4 rev31652 - December 26, 2012
* Update: Disable date formatting for Custom Field info
* Update: English .po and .mo files with new labels
* Bug Fixed: Interoperability fix: Let WordPress handle the menu position, as fixed positions have a risk of already be claimed by other plugins.
* Bug Fixed: Use the datetime of the end and start date rather than just the time. Then the programmer can choose what format to display
* New Feature: New function that will output a repeat date following the event list fullcalendar date format
* New Feature: Added the day and month names to the function so the output can be localized
* New Feature: New function for easier setup of the event template when manually setting it: get_repeat_start_date($post_id,$date_format)

Version 1.2.3 rev30654 - November 27, 2012
* Bug Fixed: Date not showing on the admin in Firefox (PC)
* Bug Fixed: Prevent php warning
* New Feature: Allow specifying alternate event source
* New Feature: Added rdate to iCalendar
* Update: Implement the prev and next label settings when generating the cal shortcode
* Update: Enable the field for changing the prev and next button text

Version 1.2.2 rev29705 - November 2, 2012
* New Feature: Allow setting a content wrap on Pages used as templates
* Update: Simplify templates, replace php functions with shortcodes, so that templates can optionally be fully setup at the template page
* New Feature: Added option to enable thumbnail support in case the theme don't
* New Feature: Added option to specify the page id to which the widget links to by default
* Update: Remove spaces from organizer template as they get converted to <p> and </br>
* Update: Separated the event list js code for easier maintenance
* Bug Fixed: If the address is empty do not display the address label in the tooltip
* Bug Fixed: If website field is empty, don't show the field
* Bug Fixed: Remove extra space when fields are empty
* Bug Fixed: Do not show map if all required fields for map are empty
* Bug Fixed: One event list, when address venue or organizer is empty, do not show the label
* Bug Fixed: Do not show description on event list if it is empty (double border lines)
* Bug Fixed: Multiple day events, incorrectly displayed on IE9 and old Firefox. Technical: IE is not capable of using date string yyyy-mm-dd on new Date(date string) odd.
* Bug Fixed: Added an option to ignore a WordPress recommendation, so that event does not return a 404 on sites with plugins or themes that also ignores this recommendation
* Bug Fixed: Load options before init to catch the new ignore standard troubleshooting setting

Version 1.2.1 rev29610 - October 27, 2012
* Bug Fixed: Google calendar treats dtend exclusively 
* Bug Fixed: Modify in_array function, on certain conditions events do not show on any browser but Firefox.
* Bug Fixed: Compatibility fix, added id to a div with postbox class, as it seams that cardamom theme js needs the id or else it crashes.
* Bug Fixed: First date should not contain date into in the URL
* Bug Fixed: Non recurring events that have repeat dates, do not repeat if the start date and end interval is not in the current view date range.
* New Feature: Option to disable link in calendar pop-up
* New Feature: Added option to turn on or off the debug menu


Version 1.2.0 rev29423 - October 19, 2012
* New Feature: Added support for Exceptions when creating recurring events
* New Feature: Added support for arbitrary recurring events
* New Feature: Added option to specify calendar URL to link the upcoming events widget
* Bug Fixed: Adjusted margin on "Calendar" and "Today" button
* Bug Fixed: Show correct date of repeat instance info fields on event page when clicking on a repeat event. 
* Bug Fixed: When choosing to filter by several taxonomies (it was only filtering by 1 taxonomy)
* Updated: Layout fixed for WordPress 3.5


Version 1.1.4 rev29164 - September 23, 2012
* New Feature: Provided an option to display all Calendarize It! Post Types in the main calendar
* Bug Fixed: Add Organizer image and HTML content

Version 1.1.3 rev29014 - August 31, 2012
* Bug Fixed: Make sure featured image is used for events
* Bug Fixed: $.curCSS is depreciated in jQuery 1.8, updated full calendar.js
* Improvement: Optional Tooltip title link disable or enable
* Bug Fixed: All Day events where showing time in start and end dates in popup
* Bug Fixed: Option to enable/disable ical button on the calendar widget

Version 1.1.2 rev28899 - August 18, 2012
* New Feature: Render short codes in before/after template HTML; added shortcode rhc_sidebar for adding sidebars to the template
* New Feature: Option to make taxonomies into fields, hyperlinks to the taxonomy page
* Update: Allow the parameter to be 'false' so that the specified template does not render any sidebar
* Update: Missing text domain on the words: Start, End and Address in the pop-up
* Update: Added optional rewrite procedures for sites with problems with permalinks, updated Options Panel, pushed plugin init to after_theme_setup for theme integration support. Added default organizers template, include code (but not enabled) for handling calendar inside a tab
* Bug Fixed: Multiple day events were only highlighted the first day in the calendar widget
* Bug Fixed: When the end date time is less than start time it was incorrectly calculating the number of days
* Bug Fixed: Upcoming Events Widget, when event is all day, time should not display
* Bug Fixed: On most themes, the image pushes the content on the event list
* Bug Fixed: Prevent CSS3 transition from modifying the event rendering behavior

Version 1.1.1 rev28549 - August 1, 2012
* Bug Fixed: Frontend breaking when showing Calendar and Upcoming Events Widget at the same time
* Update: jQuery updated to version 1.8.22
* New Feature: Option to disable built-in Taxonomies from Options

Version 1.1.0 rev28238 - July 27, 2012
* New Feature: Provide option to disable loading Calendarize It! templates
* New Feature: Hide dialog when pressing escape key
* New Feature: Support for iCal (OSX Calendar) and Google Calendar feed
* New Feature: Allow to set iCal parameters
* New Feature: Added better support for setting time and data format 
* New Feature: Added month and day names to the Options Panel
* New Feature: Enable default options in Calendar in widget
* New Feature: Implement day and month names in upcoming events widget from Shortcode options
* New Feature: Included Spanish .mo files
* Update: Modified widget for current rule event, changed date format to full calendar format to support only one date format
* Update: Added new language strings in .po and .mo files
* Bug Fixed: Provide a custom URL for the calendar link (implemented non-default event and calendar display slugs)
* Bug Fixed: Recurring events that repeat many times where being excluded from the upcoming events widget
* Bug Fixed: Recurring events only showing in Chrome. rule UNTIL, if time not set, should include that days event in recurring events
* Bug Fixed: Missing end date time, added formatting to info box inside the admin so it looks like the frontend
* Bug Fixed: Upcoming events not showing on Internet Explorer, Firefox and Safari
* Bug Fixed: Spacing on Upcoming Events widget
* Bug Fixed: Default time format with 2 digit minutes
* Bug Fixed: When any of the taxonomy slugs is left empty in the options panel, in WordPress 3.4.1 every pages becomes not found
* Bug Fixed: Missing textdomain for "Every year", "Custom Interval", "No access"


Version 1.0.2 rev27083 - July 7, 2012
* Bug Fixed: HTML entries in event title
* Bug Fixed: Set first day of the week was not working
* Bug Fixed: Typographical error "Wednsday" changed to "Wednesday" in drop down for choosing start day of the week
* New Feature: Added backend options to customizing month, week, day and event list time formats. As well as title, column, event time and agenda axis
* New Feature: Added sort by date in the event admin
* New Feature: Allow hookup of external jQuery UI themes (this allows you to easily add your own jQuery UI themes by using the http://jqueryui.com/themeroller/. It is important that you add a CSS Scope (.rhcalendar) when exporting the theme in order to limit the usage of the CSS to Calendarize it)
* New Feature: Allow hookup of external templates (this allow you to update the plugin without overwriting any customizations you have made to the templates)
* New Feature: Provide configuration options for agenda view
* Update: added latest strings to localization files

Version 1.0.1 rev26587 - June 30, 2012
* Bug Fixed: Incorrect localization function giving warning
* Bug Fixed: Start and End date subtitle where not being localized
* Update: Added filters to event list in wp-admin
* Update: Added load text domain for Calendarize
* Update: Added base files for translation (/languages)
* Update: Added argument to control the start and end formats in the event list

Version 1.0.0 rev26066 - June 21, 2012
* First release.


== DESCRIPTION ==
Calendarize It - a powerful Calendar and Event plugin for WordPress.

The main features are: 

- Easy Point and Click interface to add new events
- Preview when entering event in wp-admin (single event)
- Support for Recurring Events
- Show Individual Calendars per user in WordPress- Advanced filtering (Custom Taxonomies)- Sidebar Widget for Upcoming Events
- Sidebar Widget for Mini Calendar - Event List per day, per week, monthly
- Support for Custom Fields for Events- Creating and manage Venues, Organizers and Calendars- Support for Shortcodes - Support for Custom Post Types
- Detailed Event Page- Detailed Venue Page
- Google Map integration for Events and Venues
- Support for internationalization

If you want to enable other user roles besides the Administrator to create and manage Events you can add the following capabilities. You will need a Role and Capability Manager  like our White Label Branding for WordPress. Or any other plugin that lets you update the capabilities of a user role.

== INSTALLATION ==

1. Upload the 'calendarize-it' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the menu you will find Calendarize It. 

== FREQUENTLY ASKED QUESTIONS ==

If you have any questions or trouble using this plugin you are welcome to contact us through our profile on Codecanyon (http://codecanyon.net/user/RightHere)

Or visit our HelpDesk at http://support.righthere.com


== HOW CAN I PROVIDE ACCESS TO CALENDARIZE IT TO OTHER USERS THAN THE ADMINISTRATOR? ==

Use the following capabilities:

- manage_venue
- manage_calendar
- manage_organizer

- edit_event
- read_event
- read_private_events
- delete_event
- delete_others_events
- edit_events
- edit_others_events
- edit_published_events
- publish_events
- read_private_events

== HOW DO I INSERT CALENDARIZE IT IN A PAGE OR POST? ==

You can insert Calendars in any Page, Post or Custom Post Type you want.
Use the following Shortcodes to insert Calendars:

[calendarizeit]
This Shortcode will insert the Calendar and will display all events created by all users.

[calendarizeit author_name='username']
This Shortcode will insert the Calendar and only display events created by the 'username'. Replace 'username' with any user from WordPress

[calendarizeit author='ID,ID']
This Shortcode will insert the Calendar and display events created by multiple authors. Replace ID with the ID number of the author. You can find the ID number of an author by holding the cursor over "edit" in the Users List, and then view the bottom line. It will show something like /user-edit.php?user_id=2. In this case enter the number "2" as the ID.

[calendarizeit post_type="post"]
This Shortcode will insert the Calendar and display the post type that you enter. You will need to enable the Custom Post Type in the options panel.

[calendarizeit taxonomy='calendar' terms='concerts']
This Shortcode will insert the Calendar and based on the Custom Taxonomy that you have defined you can display e.g. a Concert Calendar. 

[calendarizeit venue='place']
This Shortcode will insert the Calendar and based on the 'place' (venue) it will display all events assigned to the specific venue.

[calendarizeit organizer='name']
This Shortcode will insert the Calendar and based on the 'name' (organizer) it will display all events assigned to the specific organizer.

[calendarizeit calendar='name']
This Shortcode will insert the Calendar and based on the 'name' (calendar) it will display all events assigned to the specific calendar.


== SOURCES - CREDITS & LICENSES ==

We have used the following open source projects, graphics, fonts, API's or other files as listed. Thanks to the author for the creative work they made.

1) FullCalendar jQuery plugin
   http://arshaw.com/fullcalendar/

DISCLAIMER: FullCalendar is great for displaying events, but it isn't a complete solution for event content-management. Beyond dragging an event to a different time/day, you cannot change an event's name or other associated data. It is up to you to add this functionality through FullCalendar's event hooks.

2) jQuery UI ThemeRoller
   http://jqueryui.com/themeroller/

