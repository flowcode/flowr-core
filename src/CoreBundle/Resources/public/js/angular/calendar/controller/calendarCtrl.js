angular.module('flowerCalendar').controller("CalendarController", function ($scope, $compile, uiCalendarConfig, $http, $modal) {

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var modalInstance;

    var isCreating = false;

    /* controls */
    $scope.titleEditing = false;
    $scope.addressEditing = false;
    $scope.startDateEditing = false;
    $scope.endDateEditing = false;
    $scope.descriptionDateEditing = false;

    $scope.changeTo = 'Hungarian';

    /* entities */
    $scope.events = [];
    $scope.projects = [];
    $scope.accounts = [];
    $scope.opportunities = [];
    $scope.users = [];
    $scope.eventStatuses = [];

    $scope.myEvents = {
        url: rootPath + 'p/api/planner/events/me'
    };

    $scope.allEvents = {
        url: rootPath + 'p/api/planner/events',
        color: '#939393'
    };

    function initData() {

        $http.get(rootPath + 'p/api/users/available').then(function (data) {
            $scope.users = data.data;
        });

        $http.get(rootPath + 'p/api/clients/accounts').then(function (data) {
            $scope.accounts = data.data;
        });

    }

    function showEvent(event) {
        isCreating = false;
        var promise = $http.get(rootPath + 'p/api/planner/events/' + event.id);
        promise.then(
            function (response) {
                $scope.eventEditing = response.data;
                modalInstance = $modal.open({
                    templateUrl: 'showEvent.html',
                    size: "lg",
                    scope: $scope
                });
                modalInstance.result.finally(function () {
                    disableEditing();
                });
            });
    };

    $scope.saveOrUpdate = function () {
        var promise = $http.post(rootPath + 'p/api/planner/events', $scope.eventEditing);
        promise.then(function (reponse) {
            modalInstance.close();
            if (isCreating) {
                $scope.events.push(reponse.data.entity);
            }
        });
    };

    $scope.alertMessage = function (msg) {
        alert(msg);
    };

    /* alert on eventClick */
    $scope.alertOnEventClick = function (date, jsEvent, view) {
        showEvent(date);
    };

    /* alert on Drop */
    $scope.alertOnDrop = function (event, delta, revertFunc, jsEvent, ui, view) {
        $scope.alertMessage('Event Droped to make dayDelta ' + delta);
    };

    /* alert on Resize */
    $scope.alertOnResize = function (event, delta, revertFunc, jsEvent, ui, view) {
        $scope.alertMessage('Event Resized to make dayDelta ' + delta);
    };

    /* add and removes an event source of choice */
    $scope.addRemoveEventSource = function (sources, source) {
        var canAdd = 0;
        angular.forEach(sources, function (value, key) {
            if (sources[key] === source) {
                sources.splice(key, 1);
                canAdd = 1;
            }
        });
        if (canAdd === 0) {
            sources.push(source);
        }
    };

    /* remove event */
    $scope.remove = function (index) {
        $scope.events.splice(index, 1);
    };

    /* Change View */
    $scope.changeView = function (view, calendar) {
        uiCalendarConfig.calendars[calendar].fullCalendar('changeView', view);
    };

    /* Change View */
    $scope.renderCalender = function (calendar) {
        if (uiCalendarConfig.calendars[calendar]) {
            uiCalendarConfig.calendars[calendar].fullCalendar('render');
        }
    };

    function disableEditing() {
        $scope.titleEditing = false;
        $scope.addressEditing = false;
        $scope.startDateEditing = false;
        $scope.endDateEditing = false;
        $scope.descriptionDateEditing = false;
    }

    function newEvent(date) {
        isCreating = true;
        $scope.eventEditing = {
            title: "",
            description: "",
            address: "",
            start_date: date.toString(),
            end_date: angular.copy(date).toString(),
            reminders: [
                {
                    type: 1,
                    type_name: "Email",
                    unity: 1,
                    unity_name: "Minutos",
                    amount: 15
                }
            ]
        };
        $scope.titleEditing = true;
        $scope.addressEditing = true;
        $scope.startDateEditing = true;
        $scope.endDateEditing = true;
        $scope.descriptionDateEditing = true;
        modalInstance = $modal.open({
            templateUrl: 'showEvent.html',
            scope: $scope
        });
        modalInstance.result.finally(function () {
            disableEditing();
        });
    }

    /* config object */
    $scope.uiConfig = {
        calendar: {
            height: 450,
            editable: true,
            header: {
                left: 'title',
                center: '',
                right: 'today prev,next'
            },
            dayClick: function (date, jsEvent, view) {
                newEvent(date);
            },
            eventClick: $scope.alertOnEventClick,
            eventDrop: $scope.alertOnDrop,
            eventResize: $scope.alertOnResize,
            editable: false, //por ahora hacemos que no sea editable
            eventRender: $scope.eventRender
        }
    };


    /* event sources array*/
    $scope.eventSources = [$scope.events, $scope.myEvents, $scope.allEvents];


    initData();

});
