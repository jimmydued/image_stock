'use strict';
/**
 * @ngdoc function
 * @name imageCrmApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the imageCrmApp
 */
angular.module('imageCrmApp')
  .controller('MainCtrl', ["$scope","$rootScope", function($scope,$rootScope) {
		$scope.userDetails = $rootScope.globals.currentUser;
		console.log($scope.userDetails);
   	
  }]);
