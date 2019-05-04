/**
 * ownCloud - Music app
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Pauli Järvinen <pauli.jarvinen@gmail.com>
 * @copyright Pauli Järvinen 2019
 */


angular.module('Music').controller('FoldersViewController', [
	'$rootScope', '$scope', 'playlistService', 'libraryService', 'Restangular', '$timeout',
	function ($rootScope, $scope, playlistService, libraryService, Restangular, $timeout) {

		$scope.tracks = null;
		$rootScope.currentView = window.location.hash;

		// $rootScope listeneres must be unsubscribed manually when the control is destroyed
		var unsubFuncs = [];

		function subscribe(event, handler) {
			unsubFuncs.push( $rootScope.$on(event, handler) );
		}

		function play(folder, startIndex /*optional*/) {
			var id = 'folder-' + folder.id;
			playlistService.setPlaylist(id, folder.tracks, startIndex);
			playlistService.publish('play');
		}

		$scope.onFolderTitleClick = function(folder) {
			play(folder);
		};

		$scope.onTrackClick = function(trackId) {
			// play/pause if currently playing folder item clicked
			if ($scope.$parent.currentTrack && $scope.$parent.currentTrack.id === trackId) {
				playlistService.publish('togglePlayback');
			}
			// on any other list item, start playing the folder from this item
			else {
				var folder = libraryService.findFolderOfTrack(trackId);
				var index = _.findIndex(folder.tracks, function(i) {return i.track.id == trackId;});
				play(folder, index);
			}
		};

		/**
		 * Gets track data to be dislayed in the tracklist directive
		 */
		$scope.getTrackData = function(listItem, index, scope) {
			var track = listItem.track;
			return {
				title: track.artistName + ' - ' + track.title,
				tooltip: '',
				number: index + 1,
				id: track.id
			};
		};

		$scope.getDraggable = function(type, draggedElement) {
			var draggable = {};
			draggable[type] = draggedElement;
			return draggable;
		};

		$scope.getTrackDraggable = function(trackId) {
			return $scope.getDraggable('track', libraryService.getTrack(trackId));
		};

		$scope.$on('$destroy', function () {
			_.each(unsubFuncs, function(func) { func(); });
		});

		// Init happens either immediately (after making the loading animation visible)
		// or once collection has been loaded
		$timeout(initView);

		subscribe('artistsLoaded', function () {
			$timeout(initView);
		});

		function initView() {
			if (libraryService.collectionLoaded()) {
				Restangular.one('folders').get().then(function (folders) {
					libraryService.setFolders(folders);
					$scope.folders = libraryService.getAllFolders();

					$timeout(function() {
						$rootScope.loading = false;
					});
				});
			}
		}

		subscribe('deactivateView', function() {
			$rootScope.$emit('viewDeactivated');
		});
	}
]);
