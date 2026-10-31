angular.module('login', []).controller('login-controller', function ($scope, $http, $window, $location, $timeout) {
   //#region togglePassword
   $scope.showPassword = false;
   $scope.togglePassword = function () {
      $scope.showPassword = !$scope.showPassword;
   };

   // Check if the URL contains the logout=success parameter
   if ($location.search().logout === 'success') {
      // Show SweetAlert after logout
      $timeout(function () {
         Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Successfully logged out!',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
               toast.addEventListener('mouseenter', Swal.stopTimer);
               toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
         });
      }, 300);
   }

   //#region loginSubmit
   $scope.isLoggingIn = false;

   $scope.loginSubmit = function (e) {
      e.preventDefault();

      if ($scope.isLoggingIn) return; // prevent double submit
      $scope.isLoggingIn = true;

      const showToast = (icon, title, timer) => {
         const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: timer,
            timerProgressBar: true,
            didOpen: toast => {
               toast.addEventListener('mouseenter', Swal.stopTimer);
               toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
         });
         Toast.fire({
            icon: icon,
            title: title,
         });
      };

      $http({
         method: 'post',
         url: 'login',
         data: { username: $scope.login_user, password: $scope.login_pass },
         headers: { 'Content-Type': 'application/json' },
      }).then(
         function successCallback(response) {
            if (response.data['info'] === 'Granted') {
               // No toast: keep the spinner on and redirect right away
               $window.location.href = 'main';
            } else {
               $scope.isLoggingIn = false;
               showToast('warning', response.data['message'], 3000);
            }
         },
         function errorCallback() {
            // Network/server error: re-enable the button
            $scope.isLoggingIn = false;
            showToast('error', 'Unable to connect. Please try again.', 3000);
         },
      );
   };
}); 
