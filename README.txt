Phillip Pavlich
001414960
pavlicpm

I have a file called datamodel.sql that contains all 4 of my create table statements. Please make sure to run it in the order that it was built
as there are foreign key dependencies accross tables.

PHP back-end files:

addBooking.php -> This file obtains data from a form, connects with the database and uploads a booking for a given parking spot under the current
userid. If the user is not logged in, then they get redirected to the login page.

addParking.php -> This file obtains data from a form, connects with the database and uploads a parking spot under the current userid. If the user
 is not logged in, then they get redirected to the login page. Validation is done on submitted parking spots.

addRating.php -> This file obtains data from a form, connects with the database and uploads a rating for a given parking spot with the current
userid being the person who left the review. If the user is not logged in, then they get redirected to the login page. A user can only leave a 
review of a parking spot if they have previously made a booking on the parking spot.

changeBookingStatus.php -> From the bookings.php screen, the user is able to update both their bookings and bookings that other users made on their
parking spots. A user can cancel their bookings by clicking on the red X for their booking. This changes the status of the booking to cancelled. A 
user can either approve or reject a booking made by another user on their parking spot by clicking on the red X or the green checkmark. This 
changes the status of the booking to either rejected or approved.

getSearchResults.php -> This file obtains the search data from the form, connects to the database and performs a search on what parking spots match 
this criteria. All matching results are outputted and displayed on the map.

login.php -> This file begins a login session. A user must login prior to having access to changing any data. The user is able to perform searches 
but they cannot do any bookings. A session is started with a user id and this is passed accross all php pages.

logout.php -> This destroys all session data. After logging out with the red logout button, the user must relogin to the system.

registerUser.php -> This file is used to validate and add a registered user to the database. This creates a user with all user details allowing them
to login to the system.

retrieveBookingData.php -> This file acts as a get method to obtain all data required for the booking page. It grabs all bookings made by the user and all
bookings that another user made on this user's parking spots.

retrieveParkingData.php -> This file acts as a get method to obtain all data required for a given parking page. All details and reviews surrounding
one selected parking spot are gathered and saved as session variables to be dynamically displayed.

retrieveProfileData.php -> This file acts as a get method to obtain all profile data that would need to be displayed on the profile page. This includes all user
details as well as all addresses of parking spots that belong to that user.

updateProfile.php -> This file acts as an update method. If the user modifies their profile details, this file performs validation and updates the data.

updateSpots.php -> This file acts as an update method. If the user modifies their parking spot details, this file performs validation and updates the 
data for the selected parking spot.


Javascript files:

updatePrice.js -> This file is used on the makeBooking.html page to dynamically populate the cost as the user enters the dates. The cost
defaults to 0 and is only populated once both dates have been entered. Cost= #days (todate-fromdate) * price

NavBar.js -> This file is used on every html page. This allows for the drop down menu to have transitions when the screen size is small
enough.

addSingleMap.js -> This file is used on the parking.html page. When the user is looking at a specific parking record, they will now be able
to view a live embedded map with a marker for the given parking spot. There is also an info window that pops up if the marker is clicked.

addMaps.js -> This file uses the criteria in the search.html form to come up with all results and display them on the results.html page.
If the user selected to share their location, a marker with the user's location will also be displayed on the live embedded map in the 
results page. If they do not share their location, then there will be no marker displayed for the user's location. All markers have 
info windows that pop up when they are clicked.

geoLocation.js -> This file uses a google api to prompt the user for their current location. If the user rejects, then you will see an 
error message in the form under the location section on the page "search.html". If the user shares their location, then the latitude and 
the longitude values will be displayed in the form on the search page and also transferred to the results map.

geocodeFindLocation.js -> This file is used on the submission.html page. The user can press the button called "verify location". If they do,
the script uses the values given in the input boxes for address, city, province and country in combination with a google api to display
the longitude and latitude values for the address provided. 

signUpValidation.js -> This file does form validation on the register.html form. It performs checks based on length of given responses,
email format and phone number format. An alert will pop up telling the user if the details and account info was accepted.

HTML/PHP front-end Pages:

(Home page, Open this page to begin!!!)index.html -> This is the main screen. This allows you to login by entering the username 
and password and then pressing the login button. This will redirect you to the bookings page.
By default with no backend, it will automatically sign you in. If the user presses the "Sign Up"
page, they will be directed to the "register.html" page to create an account. 

search.html -> This is the search screen. The user is able to enter filters such as minimum and maximum
price, farthest distance, and minimum rating. Then the user presses search. With no backend, this
defaults the user to a sample "results.html" page.

results.php -> This pages shows the user a list of addresses that match the user's search criteria. Currently,
these options are hardcoded. There is also a map that shows all parking locations. The user is able to press the view
button beside the results to see more details about a parking spot (parking.php). Session variables are used to dynamically populate 
this page with all results obtained from the database that match the search criteria.

parking.php -> This page shows all details about a given parking spot. The user is able to learn more details and 
either return to the previous search or book the parking spot by pressing the buttons. The booking button will
redirect the user to the "bookings.html" page. Once Javascript can be utilized, there will be a pop-up allowing the 
user to request certain dates when booking a parking spot. Session variables have been used to dynamically populate
parking spot details and ratings based on which parking spot was selected by the user

submission.html -> This page allows the user to submit all information about a new parking spot to a form. This allows 
a user to add spots for reservation by other drivers. Pressing the "Add parking spot" button will redirect the user to 
the bookings page.

bookings.php -> This page has two sections. The top section allows a user to view the reservations that they made on other
parking spots. With the red "X" button, they will have the functionality to cancel a reservation when the back-end is implemented.
The bottom section shows the user reservations that other drivers have made on their property with a corresponding status.
Once the request is received, the user can accept or decline the reservation. Upon declining, the request would be removed. The 2nd
line shows an example of an approved request. Session variables are used to dynamically populate user bookings and requests on the user's
parking spots.

profile.php -> This page allows the user to update their account information. At the bottom the user can also select one of their 
properties from a dropdown menu and alter the properties or details surrounding it. Session variables are used to dynamically populate
profile details and current parking locations.

register.html -> This page allows the user to create a new account. Once they have pressed "Sign up", they will be redirected to the 
home page to login.

makeBooking.html -> This page is used as a form to allow the user to actually make a booking on a selected parking spot. A from date
and a to date are both required from the user. A cost is dynamically populated on the screen for the user to view prior to making a decision.
The cost only appears once both dates have been entered.

submitReview.html -> This page is used as a form to allow the user to actually submit a review based on a booking that was made
on a parking spot. A score must be provided and can range as an integer from 1 to 10. A comment can also be added to describe the rating that
the user provided. This rating is then stored in the database and is attached to the parking spot.

Each page:
Each page has a header, footer and nav bar. The header contains the title of the website and a logo. The footer of the page contains
copyright details and a link to the author that originally designed the logo. The nav bar allows the user to navigate the html pages.
Although, when the screen resolution is too small, it turns into a drop down menu on the right hand side. Once Javascript can be used,
that drop down will turn into an on click event to toggle the menu.

CSS pages:
pageFormat.css -> Has css rules for the headers, footers, and nav bars that are present on every page.

formView.css -> Has css rules for all other elements found throughout these pages.

Media Queries were used to move around elements on the pages based on the height and width of the page to ensure a consistent and understandable
view.


