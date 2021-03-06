<?php

// scripts
\OCP\Util::addScript('music', 'vendor/angular/angular.min');
\OCP\Util::addScript('music', 'vendor/angular-gettext/dist/angular-gettext.min');
\OCP\Util::addScript('music', 'vendor/angular-route/angular-route.min');
\OCP\Util::addScript('music', 'vendor/angular-scroll/angular-scroll.min');
\OCP\Util::addScript('music', 'vendor/aurora/aurora-bundle.min');
\OCP\Util::addScript('music', 'vendor/dragdrop/draganddrop.min');
\OCP\Util::addScript('music', 'vendor/javascript-detect-element-resize/jquery.resize');
\OCP\Util::addScript('music', 'vendor/js-cookie/src/js.cookie');
\OCP\Util::addScript('music', 'vendor/restangular/dist/restangular.min');
\OCP\Util::addScript('music', 'public/app');

// stylesheets
\OCP\Util::addStyle('settings', 'settings');
\OCP\Util::addStyle('music', 'public/app');

?>


<div id="app" ng-app="Music" ng-cloak ng-init="started = false; lang = '<?php p($_['lang']) ?>'">

	<script type="text/ng-template" id="albumsview.html">
		<?php print_unescaped($this->inc('partials/albumsview')) ?>
	</script>
	<script type="text/ng-template" id="alltracksview.html">
		<?php print_unescaped($this->inc('partials/alltracksview')) ?>
	</script>
	<script type="text/ng-template" id="foldersview.html">
		<?php print_unescaped($this->inc('partials/foldersview')) ?>
	</script>
	<script type="text/ng-template" id="genresview.html">
		<?php print_unescaped($this->inc('partials/genresview')) ?>
	</script>
	<script type="text/ng-template" id="playlistview.html">
		<?php print_unescaped($this->inc('partials/playlistview')) ?>
	</script>
	<script type="text/ng-template" id="settingsview.html">
		<?php print_unescaped($this->inc('partials/settingsview')) ?>
	</script>
	<script type="text/ng-template" id="alphabetnavigation.html">
		<?php print_unescaped($this->inc('partials/alphabetnavigation')) ?>
	</script>

	<div ng-controller="MainController">
		<?php print_unescaped($this->inc('partials/navigation')) ?>

		<div id="app-content">

			<?php print_unescaped($this->inc('partials/controls')) ?>

			<?php print_unescaped($this->inc('partials/sidebar')) ?>

			<div id="app-view" ng-view resize-notifier
				ng-class="{started: started, 'icon-loading': loadIndicatorVisible()}">
			</div>

			<div id="emptycontent" class="emptycontent" ng-show="noMusicAvailable && currentView!='#/settings'">
				<div class="icon-audio svg"></div>
				<div>
					<h2 translate>No music found</h2>
					<p translate>Upload music in the files app to listen to it here</p>
				</div>
			</div>

			<div id="toScan" class="emptycontent clickable" ng-show="toScan && currentView!='#/settings'" ng-click="startScanning()">
				<div class="icon-audio svg"></div>
				<div>
					<h2 translate>New music available</h2>
					<p translate>Click here to start the scan</p>
				</div>
			</div>

			<div id="scanning" class="emptycontent" ng-show="scanning && currentView!='#/settings'">
				<div class="icon-loading svg"></div>
				<div>
					<h2 translate>Scanning music …</h2>
					<p translate>{{ scanningScanned }} of {{ scanningTotal }}</p>
				</div>
			</div>

			<div id="searchContainer" ng-controller="SearchController">
				<div id="searchResultsOmitted" class="emptycontent" ng-show="searchResultsOmitted">
					<div class="icon-search svg"></div>
					<div>
						<h2 translate>Some search results are omitted</h2>
						<p translate>Try to refine the search</p>
					</div>
				</div>
				<div id="noSearchResults" class="emptycontent" ng-show="noSearchResults">
					<div class="icon-search svg"></div>
					<div>
						<h2 translate>
							No search results in this view for <strong>{{ queryString }}</strong>
						</h2>
					</div>
				</div>
			</div>

			<img id="updateData" ng-show="updateAvailable && currentView!='#/settings'"
				 class="svg clickable" src="<?php p(OCP\Template::image_path('music', 'reload.svg')) ?>"  ng-click="update()"
				 alt  ="{{ 'New music available. Click here to reload the music library.' | translate }}"
				 title="{{ 'New music available. Click here to reload the music library.' | translate }}" >

		</div>

		<!-- The following exists just in order to make the core unhide the #searchbox element -->
		<div id="searchresults" data-appfilter="music"></div>
	</div>

</div>
