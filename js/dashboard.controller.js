angular
.module("Dashboard", [])
.controller("DashboardController", DashboardController)

DashboardController.$inject = ['$scope', '$http'];

function DashboardController($scope, $http) {
  $scope.url = '/secure/home.php';
  var dashboard = $scope;

  // var baseURL = 'http://localhost/bluetooth-beacons/';
  var baseURL = '';

  dashboard.setAccess = function () {
    var STUDENT_AFFILIATION = "student@unc.edu";
    var INSTRUCTOR_AFFILIATION = "faculty@unc.edu";
    var STAFF_AFFILIATION = "staff@unc.edu";
    var affiliations = dashboard.affiliation.split(";");
    affiliations.forEach(function(value, key) {
      if(angular.equals(STUDENT_AFFILIATION, value)) {
        dashboard.isStudent = true;
      }
      if(angular.equals(INSTRUCTOR_AFFILIATION, value)) {
        dashboard.isInstructor = true;
      }
      if(angular.equals(STAFF_AFFILIATION, value)) {
        dashboard.isStaff = true;
      }
    });
    if(dashboard.isStaff && dashboard.isFaculty) {
      dashboard.isAdministrator = true;
    }
    if(dashboard.isAdministrator) {
      dashboard.setMode(true,false,false);
    } else if (dashboard.isInstructor) {
      dashboard.setMode(false,true,false);
    } else if (dashboard.isStudent) {
      dashboard.setMode(false,false,true);
    }
  }

  dashboard.setMode = function (administratorMode, instructorMode, studentMode) {
    dashboard.administratorMode = administratorMode;
    dashboard.instructorMode = instructorMode;
    dashboard.studentMode = studentMode;
    dashboard.getCourses();
  }

  dashboard.getCourses = function () {
    var mUrl;
    var postData = {onyen: dashboard.onyen};
    if(dashboard.studentMode) {
      mUrl = '/backend/getStudentAttendance.php';
    } else if(dashboard.instructorMode) {
      mUrl = '/backend/getInstructorAttendance.php';
    } else if(dashboard.administratorMode) {
      mUrl = '/backend/getAdministratorAttendance.php';
    }
    $http({
      method: 'POST',
      url: baseURL + mUrl,
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      transformRequest: function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
      },
      data: postData
    }).then(successCallback, errorCallback);

    function successCallback(response) {
      dashboard.records = response.data.result;
      createTabs();
    }

    function errorCallback(response) {
      alert("fail");
      dashboard.records = [];
      createTabs();
    }
  }

  dashboard.addCourse = function () {
    dashboard.fields.creator = dashboard.onyen;
    $http({
      method: 'POST',
      url: baseURL + '/backend/createCourse.php',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      transformRequest: function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
      },
      data: dashboard.fields
    }).then(successCallback, errorCallback);

    function successCallback(response) {
      dashboard.getCourses();
    }

    function errorCallback(response) {
      alert("fail");
    }
  }

  dashboard.loadRoster = function(record, key) {
    $http({
      method: 'POST',
      url: baseURL + '/backend/getRoster.php',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      transformRequest: function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
      },
      data: {department : record.records[0].department, number : record.records[0].number, section : record.records[0].section}
    }).then(successCallback, errorCallback);

    function successCallback(response) {
      if(dashboard.roster == null) {
        dashboard.roster = {};
      }
      dashboard.roster[key] = response.data.result;
      setAttendanceStatus(record, key);
    }

    function errorCallback(response) {
      alert("fail");
    }
  }

  dashboard.exportRoster = function (fileName) {
    if(dashboard.attendance != null && Object.keys(dashboard.attendance).length > 0) {
      var csvContent = "data:text/csv;charset=utf-8,";

      angular.forEach(dashboard.attendance, function(value, key) {
        csvContent += key + "," + value;
        csvContent += "\n";
      });


      var encodedUri = encodeURI(csvContent);
      var link = document.createElement("a");
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", fileName);
      document.body.appendChild(link);

      link.click();
    }

  }

  dashboard.uploadRoster = function (record, key) {
    var f = document.getElementById('rosterFile').files[0],
    r = new FileReader();
    r.onloadend = function(e){
      var data = e.target.result;
      if(data.charAt(data.length-1) == "\n") {
        data = data.substring(0,data.length-1);
      }
      dashboard.roster[key] = data.split("\n");

      setAttendanceStatus(record, key);
    }
    r.readAsBinaryString(f);

  }

  function setAttendanceStatus(record, key) {
    // Get all attendance from students
    $http({
      method: 'POST',
      url: baseURL + '/backend/getRosterAttendance.php',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      transformRequest: function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
      },
      data: {department : record.records[0].department, number : record.records[0].number, section : record.records[0].section, roster : dashboard.roster[key]}
    }).then(successCallback, errorCallback);

    function successCallback(response) {

      dashboard.attendance = {};
      dashboard.roster[key].forEach(function(value, key) {
        var onyen = value.trim();
        if(!dashboard.attendance[onyen]){
          dashboard.attendance[onyen] = [];
        }
        response.data.result.forEach(function(value2, key2) {
          var onyen2 = value2.onyen.trim();
          if(angular.equals(onyen, onyen2)) {
            dashboard.attendance[onyen].push(value2.timestamp);
            return;
          }
        });
      });
    }

    function errorCallback(response) {
      alert("fail");
    }
  }

  dashboard.checkTimestampColumn = function(onyen, timestampHeader) {
    if(dashboard.attendance != null) {
      var index = 0;
      var attendanceTimestamps = dashboard.attendance[onyen];
      if(attendanceTimestamps != null) {
        for(var index = 0; index < attendanceTimestamps.length; index++) {
          var aTime = attendanceTimestamps[index];
          var checkInDate = new Date(aTime);
          var checkInWindowStart = new Date(timestampHeader);
          // for 10 minute window
          var checkInWindowEnd = new Date(checkInWindowStart.getTime() + 10*60000);
          if(checkInWindowStart <= checkInDate && checkInDate <= checkInWindowEnd) {
            return true;
          }
        }
      }
      return false;
    }

  }

  function createTabs() {
    dashboard.tabs = {};
    if(dashboard.records != null) {
      dashboard.records.forEach(function(value, key){
        var courseName = value.department + value.number + "-" + value.section;
        if(dashboard.tabs[courseName]){
          dashboard.tabs[courseName].attendance++;
          dashboard.tabs[courseName].records.push(value);
        } else {
          dashboard.tabs[courseName] = {attendance: 1, records: [value]};
        }
      });
    }
  }
}