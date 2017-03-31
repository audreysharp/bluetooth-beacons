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
    <div class="container">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
        <li ng-repeat="(key, tab) in tabs"><a ng-href="#{{key}}" data-toggle="tab">{{key}}</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="home">
          <h2>Info:</h2>
          <ul>
            <li>{{onyen}}</li>
            <li>{{pid}}</li>
            <li>{{firstName}} {{lastName}}</li>
            <li>{{email}}</li>
            <li>{{affiliation}}</li>
          </ul>
        </div>
        <div ng-repeat="(key, tab) in tabs" ng-attr-id="{{key}}"  class="tab-pane">
          <h2>{{key}} Attendance:</h2>
          <h5>{{tab.attendance}}</h5>
          <h5>{{tab.records}}</h5>
        </div>
      </div>
    </div>
  </div>
</body>
