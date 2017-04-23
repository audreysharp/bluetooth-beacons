<?php
// $headers = getallheaders();
$onyen = 'vadlaman';
$pid = '720399960';
$firstName = 'Ram';
$lastName = 'Vadlamani';
$email = 'vadlaman@live.unc.edu';
$affiliation = 'student@unc.edu;member@unc.edu;alum@unc.edu';
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bluetooth Beacons</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="../static/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../static/bootstrap/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="../jQuery/jquery.dataTables.min.css">
  <script type="text/javascript" src="../node_modules/angular/angular.min.js"></script>
  <script type="text/javascript" src="../jQuery/jquery-3.2.1.js"></script>
  <script type="text/javascript" src="../jQuery/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../jQuery/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/dashboard.controller.js"></script>
  <script type="text/javascript" src="attendanceTable.js"></script>
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

              <table id="studentAttendance" class="table table-hover table-striped table-bordered tablesorter">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Check-in Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="x in value.records">
                    <td>{{x.timestamp}}</td>
                    <td><span ng-class="{'glyphicon glyphicon-ok-circle green': value.attendance > 0, 'glyphicon glyphicon-remove-circle red': value.attendance <= 0}"></span></td>
                  </tr>
                </tbody>
              </table>  
            </div>
            <div ng-if="instructorMode" ng-init="loadRoster(value)">
              <label class="control-label">Select File</label>
              <input id="rosterFile" type="file" class="file">
              <button ng-click="uploadRoster(value)">Submit</button>
              <button ng-click="exportRoster(key)">Export to CSV</button>
              <ul id="courseTabs" class="nav nav-tabs">
                <li class="active"><a href="#today" data-toggle="tab">Today's Attendance</a></li>
                <li><a href="#overall" data-toggle="tab">Overall Attendance</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="today" name="today">
                  <table class="table table-hover table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Onyen</th>
                        <th>Attended</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr ng-repeat="(rkey, rvalue) in roster">
                        <td>{{rvalue}}</td>
                        <td><span ng-class="{'glyphicon glyphicon-ok-circle green': attendance[rvalue.trim()] > 0, 'glyphicon glyphicon-remove-circle red': attendance[rvalue.trim()] <= 0}"></span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="overall" name="overall">
                  <table class="table table-hover table-striped table-bordered">
                    <tr>
                      <td></td>
                      <th ng-repeat="x in value.records" scope="col">{{x.timestamp}}</th>
                    </tr>
                    <tr ng-repeat="(rkey, rvalue) in roster">
                      <th scope="row">{{rvalue}}</th>
                      <td ng-repeat="x in value.records" ><span ng-class="{'glyphicon glyphicon-ok-circle green': x.onyen==rvalue.trim(), 'glyphicon glyphicon-remove-circle red': attendance[rvalue.trim()] <= 0}"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
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
              <div class="form-group">
                <label for="beaconID">Beacon UUID:</label>
                <input type="text" class="form-control" id="beaconID" ng-model="fields.beaconID" placeholder="required"></input>
              </div>
              <button type="submit" class="btn btn-default" ng-click="addCourse()">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="../static/bootstrap/js/bootstrap.min.js"></script>
</body>
