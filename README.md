# cinemaListing

Jobsity Wordpress Challenge
Alex Bores Rivera
boresrivera@gmail.com

Set up
As per requested, the deliverables for this project consist of a Wordpress theme and a recovery file intended to be used with the “All-in-One WP Migration” plugin. 

To set up the project: 
first create a blank wordpress installation.
Install the “All-in-One WP Migration” plugin. You can find it in the following link: https://wordpress.org/plugins/all-in-one-wp-migration/. and active it.
Now the plugin should be seen In the wordpress admin bar, select the import option.
Drag and drop the “importFile.wpress” file included in the deliverables.
Accept and wait for the plugin to load the project, it should be noted that the previous user would be replaced including the admin, login again with the following credentials: user name = admin, password = admin


you can find the source code of this project in: https://github.com/alexbores/cinemaListing


If the automatic set up fails the project would have to be configure manually:
Load the theme in the admin panel.
Create 4 pages with the following titles and link them with their corresponding template:
“movies”
“actors”
“account”
The permalinks should be set to “Post Name”.
In appearance options create a menu for the “Mobile” space with the home, actors and movies, for navigation in the webpage.


Project Overview:
The project uses the (https://developers.themoviedb.org/3 ) API to render lists of actors and movies which are divided in pages, note that a “page” in a list is not equivalent to a “page” of the API, this is to load more movies or actors per view. It includes filters and the ability to create an account to make a wishlist of the movies. Each movie and actor has its own page to display specific information.

All the mandatory features have been added.

The theme is divided into some files:
Assets: here are stored all the media resources and fonts.
Config: the hooks and configurations for wordpress are stored here in php files that are required in the functions.php.
Components: Reusable sections of code are stored here, the components could be extensive and include their own styles and scripts or can be snippets of code.
Helpers: Utilities for php and ajax files are stored here.
Scripts: Utilities for js are stored here.
Templates: here are the files that would be connected to the wordpress pages as templates, the templates can call components.

Bonus:
The wishlist function and the ability to register as a guest has been implemented, using hooks and wp functions the login and register have been move to a custom page, if the user is logged in the account page will show the movie wishlist, the selected movie is added using Ajax if the user is valid, if not, a message would be prompted inviting the user to register.






