angular
.module("Dashboard", [])
.controller("DashboardController", DashboardController);

DashboardController.$inject = ['$scope', '$http'];

function DashboardController($scope, $http) {
  $scope.url = '/secure/home.php';
  var dashboard = $scope;
  // dashboard.onyen = 'yechoorv';
  setAccess();
  setUserInfo();

  function setAccess(){
    dashboard.isStudent = true;
    dashboard.isInstructor = false;
    dashboard.isAdministrator = false;
  }

  function setUserInfo() {
    var http = $http;
  }

  dashboard.getAttendance = function() {
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
      data: {onyen: data}
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
  };

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
