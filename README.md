Hurremans-Framework
===================

My small personal framework which I've improved over the years to fit the needs of my various projects.
I decided to put it up on GitHub to share and get feedback. Included is a simple todo-list which requries signup and login.

Directory overview
-----------------------------------
+ /assets - Contains css/js/image assets.
+ /controls - Contains all controls/routes.
+ /lib - Contains all "framework" classes.
+ /models - Contains models.
+ /views - Contains, well, views!
+ default.php - Imports dependencies and runs the Front Controller.

Framework Classes located under /lib
------------------------------------
+ Controller.php - Base controller class.
+ FrontController.php - Front Controller.
+ helpers.php - Some different helper functions that come in handy.
+ Locator.php - Service locator, currently not in use, pending rewrite.
+ Model.php - Base model class.
+ MySQL.php - MySQL helper class.
+ Template.php - Template/view engine...
