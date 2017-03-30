<?php

$headers = getallheaders();

$onyen = $headers['uid'];
$pid = $headers['pid'];
$firstName = $headers['givenName'];
$lastName = $headers['sn'];
$email = $headers['mail'];
$affiliation = $headers['affiliation'];

?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bluetooth Beacons</title>
  <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/static/bootstrap/css/bootstrap-theme.min.css">
  <script type="text/javascript" src="/node_modules/angular/angular.min.js"></script>
  <script type="text/javascript" src="/js/dashboard.controller.js"></script>
  <script src="https://www.w3schools.com/lib/w3data.js"></script>
  <script>w3IncludeHTML()</script>
</head>
<body>
  <div ng-app="Dashboard" ng-controller="DashboardController" ng-init="onyen='<?php echo $onyen; ?>'; pid='<?php echo $pid; ?>'; firstName = '<?php echo $firstName; ?>'; lastName = '<?php echo $lastName; ?>'; email = '<?php echo $email; ?>'; affiliation = '<?php echo $affiliation; ?>'; setAccess(); getAttendance();">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Bluetooth Beacon Attendance</a>
        </div>
        <ul class="nav navbar-nav" ng-if="isStudent">
          <li><a href="#">Student Menu</a></li>
        </ul>
        <ul class="nav navbar-nav" ng-if="isInstructor">
          <li><a href="#">Instructor Menu</a></li>
        </ul>
        <ul class="nav navbar-nav" ng-if="isAdministrator">
          <li><a href="#">Administrator Menu</a></li>
        </ul>
      </div>
    </nav>
    <ul class="nav nav-tabs" ng-init="activeTab='Home'">
      <li ng-class="{'active': activeTab=='Home'}"><a ng-click="activeTab='Home'">Home</a></li>
      <li ng-repeat="(key, tab) in tabs" ng-class="{'active': activeTab==key}"><a ng-click="activeTab=key">{{key}}</a></li>
    </ul>
    <div ng-show="activeTab=='Home'">
      <h2>Info:</h2>
      <ul>
        <li>{{onyen}}</li>
        <li>{{pid}}</li>
      </ul>
    </div>
    <div ng-repeat="(key, tab) in tabs" ng-show="activeTab==key">
      <h2>Attendance:</h2>
      <h5>{{tab.attendance}}</h5>
    </div>
  </div>
</body>
