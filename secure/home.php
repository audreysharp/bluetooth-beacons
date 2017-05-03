<?php
$headers = getallheaders();
$onyen = $headers['uid'];
$pid = $headers['pid'];
$firstName = $headers['givenName'];
$lastName = $headers['sn'];
$email = $headers['mail'];
$affiliation = $headers['affiliation'];
// $onyen = 'yechoorv';
// $pid = '720396961';
// $firstName = 'Vamsi';
// $lastName = 'Yechoor';
// $email = 'yechoorv@live.unc.edu';
// $affiliation = 'student@unc.edu;member@unc.edu;alum@unc.edu';
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bluetooth Beacons</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/static/bootstrap/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="/jQuery/jquery.dataTables.min.css">
  <script type="text/javascript" src="/node_modules/angular/angular.min.js"></script>
  <script type="text/javascript" src="/jQuery/jquery-3.2.1.js"></script>
  <script type="text/javascript" src="/jQuery/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="/jQuery/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="/js/dashboard.controller.js"></script>
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
        <div class="container tab-content">
          <div class="tab-pane active" id="home" name="home">
            <h2>User Information</h2>
            <ul>
              <li><b>ONYEN</b>: {{onyen}}</li>
              <li><b>PID</b>: {{pid}}</li>
              <li><b>Name</b>: {{firstName}} {{lastName}}</li>
              <li><b>Email</b>: {{email}}</li>
              <li><b>Affiliations</b>: {{affiliation}}</li>
            </ul>
          </div>
          <div ng-repeat="(key, value) in tabs" id="{{key}}" name="{{key}}" class="tab-pane">
            <div ng-if="studentMode">
              <h2>{{key}} Attendance</h2>
              <div class="container">
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
            </div>

            <div ng-if="instructorMode" ng-init="loadRoster(value, key)">
              <label class="control-label">Upload Roster</label>
              <input id="rosterFile" type="file" class="file">
              <button class="btn btn-default" ng-click="uploadRoster(value, key)" style="padding-top:5px">Upload</button>&nbsp;
              <button class="btn btn-default" ng-click="exportRoster(key)" style="padding-top:5px">Export Attendance to CSV</button>
              <br><br>
              <ul id="courseTabs" class="nav nav-tabs">
                <li class="active"><a href="#today{{key}}" data-toggle="tab">Today's Attendance</a></li>
                <li><a href="#overall{{key}}" data-toggle="tab">Overall Attendance</a></li>
              </ul>
              <div class="container tab-content">
                <div class="tab-pane active" id="today{{key}}" name="today{{key}}">
                  <table class="table table-hover table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Onyen</th>
                        <th>Attended</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr ng-repeat="(rkey, rvalue) in roster[key]">
                        <td>{{rvalue}}</td>
                        <td><span ng-class="{'glyphicon glyphicon-ok-circle green': checkTimestampColumn(rvalue.trim(), value.records[value.records.length-1].timestamp), 'glyphicon glyphicon-remove-circle red': !checkTimestampColumn(rvalue.trim(), value.records[value.records.length-1].timestamp)}"></span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="overall{{key}}" name="overall{{key}}">
                  <table class="table table-hover table-striped table-bordered" ng-init="timestampColumns = []">
                    <tr>
                      <td></td>
                      <th ng-repeat="x in value.records" ng-init="timestampColumns.push(x.timestamp)" scope="col">{{x.timestamp}}</th>
                    </tr>
                    <tr ng-repeat="(rkey, rvalue) in roster[key]">
                      <th scope="row">{{rvalue}}</th>
                      <td ng-repeat="timestampHeader in timestampColumns"><span ng-class="{'glyphicon glyphicon-ok-circle green': checkTimestampColumn(rvalue.trim(), timestampHeader), 'glyphicon glyphicon-remove-circle red': !checkTimestampColumn(rvalue.trim(), timestampHeader)}"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div ng-if="administratorMode" ng-init="loadRoster(value, key)">
              <table class="table table-hover table-striped table-bordered" ng-init="timestampColumns = []" ng-if="value.records.length > 0">
                <tr>
                  <td></td>
                  <th ng-repeat="x in value.records" ng-init="timestampColumns.push(x.timestamp)" scope="col">{{x.timestamp}}</th>
                </tr>
                <tr ng-repeat="(rkey, rvalue) in roster[key]">
                  <th scope="row">{{rvalue}}</th>
                  <td ng-repeat="timestampHeader in timestampColumns"><span ng-class="{'glyphicon glyphicon-ok-circle green': checkTimestampColumn(rvalue.trim(), timestampHeader), 'glyphicon glyphicon-remove-circle red': !checkTimestampColumn(rvalue.trim(), timestampHeader)}"></span></td>
                </tr>
              </table>
            </div>

          </div>
          <div id="add" name="add" class="tab-pane">
            <form name="courseForm">
              <div class="form-group">
                <label for="courseDept">4 Letter Department Abbreviation</label>
                <input type="text" class="form-control" id="courseDept" ng-model="fields.department" placeholder="ex: COMP"></input>
              </div>
              <div class="form-group">
                <label for="courseNum">Course Number</label>
                <input type="text" class="form-control" id="courseDept" ng-model="fields.number" placeholder="ex: 523"></input>
              </div>
              <div class="form-group">
                <label for="courseSection">Course Section</label>
                <input type="text" class="form-control" id="courseDept" ng-model="fields.section" placeholder="ex: 001"></input>
              </div>
              <div class="form-group">
                <label for="beaconID">Beacon UUID</label>
                <input type="text" class="form-control" id="beaconID" ng-model="fields.beaconID" placeholder="Required"></input>
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
