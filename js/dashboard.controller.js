angular
.module("Dashboard", ["services"])
.controller("DashboardController", DashboardController);

DashboardController.$inject = ['$scope', '$http', 'AttendanceService'];

function DashboardController($scope, $http, AttendanceService) {

  function hello() {
    var dashboard = $scope;

    // TODO: change sessionStorage to actually retrieve variables
    // dashboard.onyen = sessionStorage.getItem('uid');
    // dashboard.pid = sessionStorage.getItem('pid');
    // dashboard.firstName = sessionStorage.getItem('givenName');
    // dashboard.lastName = sessionStorage.getItem('sn');
    // dashboard.email = sessionStorage.getItem('mail');
    dashboard.onyen = 'yechoorv';

    setAccess();

    function setAccess(){
      dashboard.isStudent = true;
      dashboard.isInstructor = false;
      dashboard.isAdministrator = false;
    }

    dashboard.records = AttendanceService.getAttendance(dashbaord.onyen);
    dashboard.tabs = [];
    for (record in dashboard.records) {
      var courseName = record[2];
      var tabNum = dashboard.tabs.indexOf(courseName);

      if(tabNum === -1) {
        var courseAttendance = 1;
        dashboard.tabs.concat({'courseName': courseName, 'courseAttendance': courseAttendance});
      } else {
        var course = dashboard.tabs[tabNum];
        course.courseAttendance+=1;
      }
    }
  }

}
