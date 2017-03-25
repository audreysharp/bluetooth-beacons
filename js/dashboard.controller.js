angular
.module("Dashboard", [])
.controller("DashboardController", DashboardController);

DashboardController.$inject = ['$scope','$http'];

function DashboardController($scope, $http) {
  var dashboard = $scope;
  dashboard.onyen = sessionStorage.getItem('uid');
  dashboard.pid = sessionStorage.getItem('pid');
  dashboard.firstName = sessionStorage.getItem('givenName');
  dashboard.lastName = sessionStorage.getItem('sn');
  dashboard.email = sessionStorage.getItem('mail');

  setAccess();

  function setAccess(){
    dashboard.isStudent = true;
    dashboard.isInstructor = false;
    dashboard.isAdministrator = false;
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
      dashboard.records = response['result'];
    }

    function errorCallback(response) {
      alert("fail");
    }
  }

}
