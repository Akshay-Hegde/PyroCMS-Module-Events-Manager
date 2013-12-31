# PryoCMS Module - Events Manager

## Overview

This module was built because several of our clients needed a simple events manager. For this first release I am including features that accommodate all my current projects/clients in one module. I will continue to make updates and improve this module as needed.

### Requirements

* PyroCMS version 2.2.3

### Installation

* Download Events Manager
* Upload the module at "Add-ons > Modules"
* Install
* Add to navigation
* Enjoy, no route work necessary (unless you want to clean up the urls)

### Features

* __Events__
 * Add, edit and delete events
 * Events start with basic fields. These fields are title, start, end, description, location and category.
 * Events can be displayed as a list or calendar view.
 * In the calendar mode, events can be listed on the days or the day can link to the list of events on that day (change in Settings).
* __Categories__
 * Add, edit and delete categories
 * Category fields are title, description, color and image.
 * List and calendar views can be filtered by category
* __Custom Fields__
 * Custom fields can easily be added to your events.
 * These fields are added to the event form and can be re-ordered and edited as needed.
* __Registrations__
 * This version only offers a very rudimentary event registration system. In order to enable the registration option on your events, you will need to select the "yes" in the "Settings" section of the admin panel.
 * If registration is enabled for an event, the visitor can simply submit their name and email address.
 * A registration limit can be set.
* __Export__
 * Select a range and then export your events.
 * Currently the events are only exported as a .csv file.
* __Settings__
 * Select whether the default view "/events_manager" should be the calendar or list
 * On the calendar decide whether to show a list of events on the days or just have the day link to a page that displays the event.
 * Enable/Disable registrations
 * This is cool for designers: set your custom calendar or list layout
*__Goodies__
 * Plugin with 2 methods
 * Widget for displaying upcoming events

### Designers

#### Views

This module is built so the layouts can be easily overloaded without having to alter the core views. In your theme, create a folder called "events_manager" inside your "views" folder. Then copy the "front" folder from the module's view folder to your newly created folders.

#### Plugin

The plugin has 2 simple methods. One to help display a timespan and the other allows you to display a list of events. I'll have more documentation in the future but the basic usage is {{ events_manager:events limit="5" show_past="no" where="`private`='yes'" }} ... add your markup and vars {{ /events_manager:events }}.

### Support

Feel free to submit a pull request, create an issue or email us if you find a bug or would like to request a feature.