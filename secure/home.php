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
  <div ng-app="Dashboard" ng-controller="DashboardController" ng-init="onyen='<?php echo $onyen; ?>'; pid='<?php echo $pid; ?>'; firstName = '<?php echo $firstName; ?>'; lastName = '<?php echo $lastName; ?>'; email = '<?php echo $email; ?>'; affiliation = '<?php echo $affiliation; ?>'; setAccess();">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Bluetooth Beacon Attendance</a>
        </div>
        <ul class="nav navbar-nav" ng-if="isStudent">
          <li><a href ng-click="setMode(true,false,false)">Administrator Menu</a></li>
        </ul>
        <ul class="nav navbar-nav" ng-if="isStudent">
          <li><a href ng-click="setMode(false,true,false)">Instructor Menu</a></li>
        </ul>
        <ul class="nav navbar-nav" ng-if="isStudent">
          <li><a href ng-click="setMode(false,false,true)">Student Menu</a></li>
        </ul>
      </div>
    </nav>
    <section>
      <div ng-init="getCourses()">
        <ul id="courseTabs" class="nav nav-tabs">
          <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
          <li ng-repeat="(key, value) in tabs"><a ng-href="#{{key}}" data-toggle="tab">{{key}}</a></li>
          <li ng-if="administratorMode"><a ng-href="#add" data-toggle="tab">+</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="home" name="home">
            <h2>Info:</h2>
            <ul>
              <li>{{onyen}}</li>
              <li>{{pid}}</li>
              <li>{{firstName}} {{lastName}}</li>
              <li>{{email}}</li>
              <li>{{affiliation}}</li>
            </ul>
          </div>
          <div ng-repeat="(key, value) in tabs" id="{{key}}" name="{{key}}" class="tab-pane">
            <div ng-if="studentMode">
              <h2>{{key}} Attendance:</h2>
              <h5>{{value.attendance}}</h5>
              <h5>{{value.records}}</h5>
            </div>
            <div ng-if="instructorMode">
              <label class="control-label">Select File</label>
              <input id="rosterFile" type="file" class="file">
              <button ng-click="uploadRoster()">Submit</button>
            </div>
            <div ng-if="administratorMode">
            </div>

          </div>
          <div id="add" name="add" class="tab-pane">
            <form name="courseForm">
              <div class="form-group">
                <label for="courseDept">4 Letter Department Abbreviation:</label>
                <input type="text" class="form-control" id="courseDept" ng-model="fields.department" placeholder="ex: COMP"></input>
              </div>
              <div class="form-group">
                <label for="courseNum">Course Number:</label>
                <input type="text" class="form-control" id="courseDept" ng-model="fields.number" placeholder="ex: 523"></input>
              </div>
              <div class="form-group">
                <label for="courseSection">Course Section:</label>
                <input type="text" class="form-control" id="courseDept" ng-model="fields.section" placeholder="ex: 001"></input>
              </div>
              <button type="submit" class="btn btn-default" ng-click="addCourse()">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="/static/bootstrap/js/bootstrap.min.js"></script>
</body>
