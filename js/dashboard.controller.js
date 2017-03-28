angular
.module("Dashboard", [])
.controller("DashboardController", DashboardController);

DashboardController.$inject = ['$scope', '$http'];

function DashboardController($scope, $http) {
  var dashboard = $scope;
  dashboard.hello = hello();

  function hello() {
    // TODO: change sessionStorage to actually retrieve variables
    // dashboard.onyen = sessionStorage.getItem('uid');
    // dashboard.pid = sessionStorage.getItem('pid');
    // dashboard.firstName = sessionStorage.getItem('givenName');
    // dashboard.lastName = sessionStorage.getItem('sn');
    // dashboard.email = sessionStorage.getItem('mail');
    dashboard.onyen = 'yechoorv';

    setAccess();
    setUserInfo();
    getAttendance(dashboard.onyen);

    function setAccess(){
      dashboard.isStudent = true;
      dashboard.isInstructor = false;
      dashboard.isAdministrator = false;
    }

    function setUserInfo() {
      var http = $http;
    }

    function getAttendance(data) {
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
    }

    function createTabs() {
      dashboard.tabs = {};
      angular.forEach(dashboard.records, function(record, key){
        var courseName = record.course;
        if(dashboard.tabs[courseName]){
          dashboard.tabs[courseName].attendance++;
        } else {
          dashboard.tabs[courseName] = {attendance: 1, records: dashboard.records};
        });
      }
    }

  }

}
