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
    for(var affiliation in dashboard.affiliation) {
      if(angular.equals(STUDENT_AFFILIATION, affiliation)) {
        dashboard.isStudent = true;
      }
      if(angular.equals(INSTRUCTOR_AFFILIATION, affiliation)) {
        dashboard.isInstructor = true;
      }
      if(angular.equals(STAFF_AFFILIATION, affiliation)) {
        dashboard.isStaff = true;
      }
    }
    if(dashboard.isStaff && dashbaord.isFaculty) {
      dashboard.isAdministrator = true;
    }
  }

  dashboard.getAttendance = function () {
    $http({
      method: 'POST',
      url: '/backend/getAttendance.php',
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

  dashbaord.setTab = function(key) {
    dashboard.activeTab = key;
    
  }

  function createTabs() {
    dashboard.tabs = {};
    dashboard.records.forEach(function(value, key){
      var courseName = value.course;
      if(dashboard.tabs[courseName]){
        dashboard.tabs[courseName].attendance++;
      } else {
        dashboard.tabs[courseName] = {attendance: 1, records: dashboard.records};
      }
    });
  }
}
