//Phillip Pavlich
//001414960

//This is the code that I used to create the 4 sql tables that I used for the back-end of this assignment
//I did not use auto_increment -- I coded in unique ids for my code 

CREATE TABLE user(
userid int NOT NULL,
fname varchar(20),
lname varchar(20),
email varchar(40),
phone varchar(20),
password char(64),
salt char(20),
PRIMARY KEY (userid)

);

CREATE TABLE parkingspot(
parkingid int NOT NULL,
address varchar(50),
city varchar(20),
province varchar(20),
postalcode varchar(10),
country varchar(20),
latitude double,
longitude double,
dateavailable date,
price double,
availableovernight tinyint(1),
videofile varchar(40),
imagefile varchar(40),
description varchar(500),
ownerid int NOT NULL,
PRIMARY KEY (parkingid),
FOREIGN KEY (ownerid) REFERENCES user(userid)

);

CREATE TABLE booking(
bookingid int NOT NULL,
ownerid int NOT NULL,
renterid int NOT NULL,
parkingid int NOT NULL,
fromdate date,
todate date,
status varchar(20),
cost double,
PRIMARY KEY (bookingid),
FOREIGN KEY (ownerid) REFERENCES user(userid),
FOREIGN KEY (renterid) REFERENCES user(userid),
FOREIGN KEY (parkingid) REFERENCES parkingspot(parkingid)

);

CREATE TABLE rating(
ratingid int NOT NULL,
parkingid int NOT NULL,
renterid int NOT NULL,
score double,
comment varchar(500),
PRIMARY KEY (ratingid),
FOREIGN KEY (parkingid) REFERENCES parkingspot(parkingid),
FOREIGN KEY (renterid) REFERENCES user(userid)

);