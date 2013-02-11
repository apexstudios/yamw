yamw
====

Yet Another MVC Website

A MVC web framework in production
---------------------------------

To be written

What?
=====

A to-be modular web framework with many tools and classes for the following:

 - MySql
   - Includes bad legacy ORM from years back
 - MongoDB
   - Well, more like shortcut classes - take care when using, since they're tailored towards my current application
 - Application routing
   - I have no idea how this works - do not attempt to explain me, it will go bad
 - Static Resource Management
   - Proud of it, although lacking... flexibility
   - Automatically minifies Js & Css, compiles `LESS` css, too
   - Hashed resource identifiers saved in files (see `src/constant_map.php`), so you can deploy multiple of these
 - AJAX + jQuery
   - The design is perfectly suited - the javascript, too
 - Bootstrap
   - Would work, see two or three points above
   - Static resource management would support this
   - Page generation with HTML classes etc, too
   - Had to be thrown out of the current application since it would have required a full design of the website layout
   - The commits for bootstrap aren't contained here, sorry
 - Templating language optional
 - BBCode too
   - Not proud of this
 - Markup engines of several kinds coming soon
   - Maybe located in YamwLib

Dependencies
============

 - [MongoDB PHP Driver](https://github.com/mongodb/mongo-php-driver/downloads)
 - Composer - Although I'm that bad and included the `vendor/` dir already in the repository
    - Modified Monolog to use my existing connection (just the interface to `MongoDBHandler::__construct`)
    - removed PHPUnit autoloader from Composer autoloader - it's for dev only (why do I have it included, anyway?)
    - added yamwlib dependency manually (`composer.json` entry should be correct)

Tools
=====

 - [Phabricator](http://phabricator.burningreality.de/): Note that you require a password - which I will only give to you if you desire to work on this or any projects related to this
   - Reason is that there are some other projects hosted on it, too, which aren't really supposed to be public
 - Github - here: here

How-To
======

This isn't really finished yet, so wait a moment until this is done.

I develop this for a website, whose name I may not tell yet, which is integrated (not good) and pretty cluttered up with legacy code from 2-4 years back (even worse). I try my best to document this code though, as well as port it over to PHP-OO and a modular framework, so you can try your hands on this.
