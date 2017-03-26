angular
.module("Dashboard", [])
.service("AttendanceService", AttendanceService);

AttendanceService.$inject = ['$http'];

function AttendanceService($http) {
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
      return response.data.result;
    }

    function errorCallback(response) {
      alert("fail");
    }
  }

}
