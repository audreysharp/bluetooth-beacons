# Bluetooth Beacons for Attendance

[Live Demo](shibboleth-yechoorv.cloudapps.unc.edu)  
[Project Website](http://bluetoothbeacon.web.unc.edu/)  
[Android Companion Application](https://github.com/audreysharp/android-bluetooth-beacons)

This project is meant to provide an accurate attendance tracking mechanism for not only professors but for students as well. Professors can use this application to streamline attendance taking and increase actual learning time spent in the classroom. It can also help them identify specific students who are struggling. For administrators, it can be used to determine the effectiveness of attending classes by comparing student attendance to grades. Finally, students will be provided with a way to track their absences and be more aware of their attendance.

For this to work, we will be implementing a two-factor approach: UNC-CH Single Sign On and proximity of a student’s registered device to Bluetooth Low Energy (BLE) beacons that use Google’s Eddystone profile. By connecting it to Sakai, this will provide an interface for all parties to track attendance rates and seamlessly export the results. Our goal for this project is to make sure that the student signs in at the beginning of class and stays signed in until the end of class when the student signs out. We want to create an application that prevents students from signing other students in. In order to do this, we want to create a small, monitorable zone where students can quickly check in and out by using our application.

The [live demo](shibboleth-yechoorv.cloudapps.unc.edu) of this application is hosted on Carolina CloudApps.

## Features

#### Students  
* No additional sign-up - log in with your ONYEN
* Use your bluetooth-enabled smartphone to easily check in to your classes
* Sign into the web dashboard to view your class attendance

#### Instructors  
* Use your bluetooth-enabled smartphone to open check-in and check-out windows
* Upload course rosters
* Export attendance data to a .csv file

#### Administrators  
* Associate bluetooth beacons with building rooms
* Run attendance reports for all classes
* Add individual users and define roles for users