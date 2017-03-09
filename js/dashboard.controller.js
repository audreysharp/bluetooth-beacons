angular
.module("Dashboard", [])
.controller("DashboardController", DashboardController);

DashboardController.$inject = ['$scope','$http'];

function DashboardController($scope, $http) {
  var dashboard = $scope;
  $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
  var onyen = 'yechoorv';
  dashboard.records = [];
  setAccess();

  function setAccess(){
    dashboard.isStudent = true;
    dashboard.isInstructor = false;
    dashboard.isAdministrator = false;
  }

  data = {"onyen":onyen};
  dashboard.getAttendance = function (data) {
    $http({
      method: 'POST',
      data: data,
      url: 'https://shibboleth-yechoorv.cloudapps.unc.edu/backend/getAttendance.php'
    }).then(function successCallback(response) {
      // this callback will be called asynchronously
      // when the response is available
      dashboard.records = response['result'];
    }, function errorCallback(response) {
      // called asynchronously if an error occurs
      // or server returns response with an error status.
      alert("fail");
    });
  }
}
