## Synopsis

This is a very simple PHP-based process for getting Box user statistics via API, and optionally saving the data to a database.

## Motivation

Even though there are more complex ways to build an app around getting reports (I know, I have another app that does this and more), I felt there was a need for those people that are doing this manually (time-consuming, not consistent). There was a thread on the Box-Community talking about how many people were manually logging into the Box Admin Console every day/week, running a report, downloading the report, adding it to a master excel file, rinse and repeat. I get tired just writing about that. So, this little script is simple enough to install and run, and hopefully it will save some people time (especially those that have a PHP/MySQL server at their disposal, but no API developers to build something more complex and fully featured). Sorry, that was a long paragraph of information. Now, onto the good stuff.

## Installation, Configuration & Initial Set up
Don't worry, there's a lot of bullet points below, but that's only because I wanted to make sure it is very easy to understand. There's not many actual steps (you'll realize that once you get to the end).

#### Phase 1: Prep
1. We're going to assume that you'll install this for testing and run it from http://localhost/box-reports/index.php
2. Create a database for this file to put data into (maybe call it "box-reports")
3. Import the install.sql file into that database. You should end up with 3 tables.
4. Visit the Box Developer Console and set up a new app to use <a target="_blank" href="http://developers.box.com" title="Box Developer Console">developers.box.com</a>)
5. Call the app anything you want. How about "Box Reports - Simple App" or something like that?
6. In the "scope" checkbox section, check the boxes for "Manage an enterprise" and "Manage an enterprise's managed users"
7. In the Redirect URI field, put "http://localhost/box-reports/index.php?page=exchange-code-for-token"
8. Save your app, but keep the page open, because you'll need the ClientId and Client Secret in a few seconds
9. Duplicate the index-default.php file, and rename one of them as "index.php" This is so you can make changes in index.php, but still get an updated copy from this repository (if we ever push out updates).
10. In your favorite text editor or IDE, like Sublime Text or Netbeans, open index.php, and update the database information, and other variables at the top of the file.

#### Phase 2: First time token retrieval
1. The first time you want to run this, you'll need to get a token, and that means clicking some things in a browser.
2. Browse to http://localhost/box-reports/index.php
3. Click on the menu item above for "Get One-Time Token"
3. Then click the button provided, which will send you to Box for Authorization.
4. It will then ask you to login (use a Box Admin or Co-Admin account)
5. After that flow is complete, you'll be redirected to a page with all of your box users (which will also be copied into your database)

#### Phase 3: Manual, or Automatic.... choose wisely (not really)
1. You can use the menu items above to use this tool manually, OR
2. Set up a cron-job that runs every day/week to visit http://localhost/box-reports/index.php?page=getAllUsersLive&updateDatabaseToo=1
3. As long as you perform a live users lookup in this tool once every 60 days, you won't have to do the "one time token" step again.

## Contributors

Nick Young (University of North Carolina at Greensboro, n_young@uncg.edu), and....

Anyone who makes a pull request that doesn't break things :-)

## Usage / Support / Requests

Use at your own risk (though there's nothing crazy complex in there... you can see everything in the single file).

If there's a problem with the code, I'll do my best to fix it ASAP. I'll take feature requests too :-)

## Upgrade Instructions

Whenever there is a new version to use, you can simply download/clone the new version from github over the top of your existing installation.

Then you'll just transfer your own app's details from the top of your altered index.php file, to a new duplicate of the default.index.php file.

Follow that? No? Ok, here goes:

1. Download a new copy of this repository
2. This will overwrite the existing files, but not overwrite your index.php (the one with your app's details in it)
3. Rename your index.php to index.php.old
4. Duplicate index-default.php, and rename one of the copies to index.php
5. Paste the necessary configuration information from index.php.old into the new index.php (clientId, clientSecret, database info etc)
6. That's it. Visit your http://localhost/box-reports/index.php (or equivalent) and you now have an updated version of this super simple app.


## 2016-10-11 Update Instructions
This update includes the ability to update users' status, which requires a new field in the database.

If this is your first installation, simply use the install.sql file.

If you are upgrading, then just add a BIGINT(20) field named "box_user_id" to the user_stats table.