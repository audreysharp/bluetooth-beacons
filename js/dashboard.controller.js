angular
.module("Dashboard", [])
.controller("DashboardController", DashboardController);

DashboardController.$inject = ['$scope', '$http'];

function DashboardController($scope, $http) {
  $scope.url = '/secure/home.php';
  var dashboard = $scope;

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
  }

  dashboard.getCourses = function () {
    //TODO: REMOVE
    dashboard.setMode(true,false,false);
    var mUrl;
    if(dashboard.studentMode) {
      mUrl = '/backend/getAttendance.php';
    } else if(dashboard.instructorMode) {
      mUrl = '';
    } else if(dashboard.administratorMode) {
      mUrl = '/backend/getCoursesByAdmin.php';
    }
    $http({
      method: 'POST',
      url: mUrl,
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      transformRequest: function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
      },
      data: {onyen: dashboard.onyen}
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
    if(dashboard.courseForm.$valid) {
      dashboard.fields.creator = dashboard.onyen;
      $http({
        method: 'POST',
        url: '/backend/createCourse.php',
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
        alert("success");
        dashboard.getCourses();
      }

      function errorCallback(response) {
        alert("fail");
      }
    }
  }

  function createTabs() {
    dashboard.tabs = {};
    dashboard.records.forEach(function(value, key){
      var courseName = value.department + value.number + "-" + value.section;
      if(dashboard.tabs[courseName]){
        dashboard.tabs[courseName].attendance++;
      } else {
        dashboard.tabs[courseName] = {attendance: 1, records: dashboard.records};
      }
    });
  }
}
