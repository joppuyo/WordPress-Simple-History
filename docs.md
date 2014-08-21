---
layout: page
title: Docs (in progress)
---

## Tagline

Simple History  
_A nice looking user activity feed for WordPress_  
[screenshot]  

## What is Simple History

It's a free plugin for WordPress. After you have installed this
plugin you get an activity feed in WordPress where you can
see what the users on your website is doing: who is adding new posts, who edited a post, who uploads images, who adds plugins, and so on. It's a really nice way to keep track of what's going on inside the admin area of your WordPress installation.

## Top features

* Made for users
  * With it's good looking GUI this plugin is not only useful for developers or admins.
  * Also, each log entry is tailered to give you the most needed information, no more, no less
* Great for debugging and for security audits
* Logged things have contexts
* A simple [PSR-3 logger interface](http://www.php-fig.org/psr/psr-3/) compatible logger and activity feed for WordPress.
* Implements [the log levels from The Syslog Protocol](http://tools.ietf.org/html/rfc5424#page-11)
* Keep track of what's going on inside the admin area of your WordPress installation.
* Activity feed is avilable for both administrators and regular users, but only admins get to see all the details.
* Different users see what matters to them the most
	* administrators see all, editors see less, and so on
* developers can log their own things
* full support for translation/different languages
* easy to extend
* easy to extend with plugins
* groups closely related log rows together to not repeat similar log entries over and over


## Features

- Different users/roles see different things (Users see what's relevant to them)

## Add your own things to the log

```php
<?php

// Most basic example: just add some information to the log
SimpleLogger()->info("This is a message sent to the log");

// Log entries can be of different severity
SimpleLogger()->info("User admin edited page 'About our company'");
SimpleLogger()->warning("User 'Jessie' deleted user 'Kim'");
SimpleLogger()->debug("Ok, cron job is running!");

// Log entries can have {placeholders} and context
// When the log is displayed all placeholders will
// have their values replaced by the key in the context
SimpleLogger()->notice(
	"User {username} edited page {pagename}",
	array(
		"username" => "jessie",
		"pagename" => "My test page",
	)
);

// You can add more data to context array than there are placeholders.
// This data can be used later on to show detailed info about a log entry.
// It may also be very useful for debugging purposes.
SimpleLogger()->notice("Edited product {pagename}", array(
	"pagename" => "We are hiring!",
	"_postType" => "product",
	"_userID" => 1,
	"_userLogin" => "jessie",
	"_userEmail" => "jessie@example.com",
	"_occasionsID" => "username:1,postID:24885,action:edited"
));

// By default a log entry is considered added by the currently logged in
// WordPress user. You can override this using the _initiator context key.
SimpleLogger()->info(
	"WordPress updated itself from version {from_version} to {to_version}",
	array(
		"from_version" => "3.8",
		"to_version" => "3.8.1",
		"_initiator" => SimpleLoggerLogInitiators::WORDPRESS
		// "_initiator" => SimpleLoggerLogInitiators::WP_USER
		// "_initiator" => SimpleLoggerLogInitiators::WEB_USER
		// "_initiator" => SimpleLoggerLogInitiators::OTHER
	)
);


```


### Using Log Initiators

A log initiator tells the logger who was responsible for the log event.

To set a log initiator add key `_initiator` to the contexts array with a constant from SimpleLoggerLogInitiators:

```php
$context = array(
	"_initiator" => SimpleLoggerLogInitiators::WP_USER
)
```

Available initiator constants:

* `WORDPRESS`  
Log events initiated by Wordpress, for example from a Cron job
* `WP_USER`  
Log events initated by logged in WordPress users
* `WEB_USER`  
Log events initiated by a non-logged in web users
* `OTHER`  
Log events was inititated by something other than above


## Loglevels

* 0 Emergency: system is unusable
* 1 Alert: action must be taken immediately
* 2 Critical: critical conditions
* 3 Error: error conditions
* 4 Warning: warning conditions
* 5 Notice: normal but significant condition
* 6 Informational: informational messages
* 7 Debug: debug-level messages


## Creating your own logger
