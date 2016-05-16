angular.module('flowerKanban').controller("TaskListController", function ($scope, $routeParams, $timeout, $http, $modal, $interval, $pusher, $window) {
    $scope.loading = true;
    $scope.tasks = [];
    $scope.statuses = [];

    $scope.multiselect = false;

    $scope.availableUsers = [];

    $scope.originalTodo = null;
    $scope.timelog = {
        "hours": "0",
        "description": "",
        "task": ""
    };
    $scope.nameEditing = false;
    $scope.descriptionEditing = false;
    $scope.estimatedEditing = false;
    $scope.timelogEditing = null;
    $scope.editedTodo = null;
    $scope.taskEditExtended = null;
    $scope.taskNew = {
        "name": "",
        "description": "",
        "estimated": "",
        "type": "task",
        "assignee": ""
    };
    var modalInstance;
    var stopSync = false;

    $scope.sortableOptions = {
        placeholder: "card",
        connectWith: ".tasklist",
        'ui-floating': false,
        stop: function (e, ui) {
            stopSync = false;
            $scope.updatePosition();
        },
        start: function (e, ui) {
            stopSync = true;
            $('.tasklist').sortable({
                sort: function (event, ui) {
                    var $target = $(event.target);
                    if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
                        var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) );
                        ui.helper.css({'top': (top) + 'px'});
                    }
                }
            });
        }
    };


    $scope.updatePosition = function () {
        var positions = Array();
        var countStatus = Object.keys($scope.tasks).length;
        for (var st = 0; st < countStatus; st++) {
            //cada status (st)
            var currentStatus = $scope.tasks[st];
            var status = {name: currentStatus.entity.name, id: currentStatus.entity.id};
            status.tasks = Array();
            var count = currentStatus.tasks.length;
            for (var index = 0; index < count; index) {
                var itemId = currentStatus.tasks[index].id;
                var currItem = {Id: itemId, Position: index};
                status.tasks.push(currItem);
                index++;
            }
            ;
            positions.push(status);
        }
        ;
        $scope.updateTask(filterId, positions);
    };

    $scope.updateTask = function (id, positions) {
        $http.post(rootPath + 'p/api/kanban/updateTask/' + id, {
            "positions": positions
        });
    };

    $scope.findAll = function () {
        var promise = $http.get(rootPath + 'p/api/kanban/tasks/list/filter/' + filterId);
        promise.then(
            function (response) {
                $scope.tasks = response.data;
                $scope.loading = false;
            });
        var promise = $http.get(rootPath + 'p/api/boards/taskstatus/getall');
        promise.then(
            function (response) {
                $scope.allStatus = response.data;
            });

    };

    $scope.findAvailableUsers = function () {
        var promise = $http.get(rootPath + 'p/api/users/available');
        promise.then(
            function (response) {
                $scope.availableUsers = response.data;
            });
    };

    $scope.findTaskStatuses = function () {
        var promise = $http.get(rootPath + 'p/api/kanban/tasks/statuses/filter/' + filterId);
        promise.then(
            function (response) {
                $scope.statuses = response.data;
            });
    };

    $scope.init = function () {
        $scope.findAll();
    };

    $scope.editTodo = function (todo) {
        $scope.editedTodo = todo;
        $scope.originalTodo = angular.copy(todo);
        stopSync = true;
        setTimeout(function () {
            $(".editable:visible").first().focus();
        });
    };

    $scope.singleClick = function (task, e) {
        if (e.ctrlKey) {
            if (task.selected) {
                task.selected = false;
            } else {
                task.selected = true;
            }
            $scope.multiselect = true;
            return;
        } else {
            $scope.multiselect = false;
        }

        if ($scope.clicked) {
            $scope.cancelClick = true;
            return;
        }

        $scope.clicked = true;

        $timeout(function () {
            if ($scope.cancelClick) {
                $scope.cancelClick = false;
                $scope.clicked = false;
                return;
            }

            $scope.showExtended(task);

            //clean up
            $scope.cancelClick = false;
            $scope.clicked = false;
        }, 500);
    };

    $scope.showExtended = function (task) {
        var promise = $http.get(rootPath + 'p/api/kanban/tasks/' + task.id);
        promise.then(
            function (response) {
                $scope.taskEditExtended = response.data.task;
                $scope.taskEditExtended.spent = response.data.spent;
                $scope.taskEditExtended.tasklogs = response.data.tasklogs;
                $scope.taskEditExtended.history_entries = response.data.history_entries;
                $scope.origTaskEditExtended = angular.copy(response.data.task);
                $('#textarea_html').wysihtml5();
                modalInstance = $modal.open({
                    templateUrl: rootPath+'bundles/flowercore/js/angular/kanban/view/modal/extendedView.html',
                    size: "lg",
                    scope: $scope
                });
            });
    };

    $scope.selectAllStatus = true;
    $scope.selectAll = function() {
        angular.forEach($scope.tasks, function(task) {
          task.selected = $scope.selectAllStatus;
        });
        $scope.multiselect = $scope.selectAllStatus;
        $scope.selectAllStatus  = !$scope.selectAllStatus;
    }

    $scope.doubleClick = function (task) {
        $timeout(function () {
            $scope.editTodo(task);
        });
    };

    $scope.updateError = function (data) {
        modalInstance.close();
    };

    $scope.addNewTo = function (statusIndex) {
        var newTodo = {
            "name": "",
            "description": "",
            "type": "task",
            "position": $scope.tasks[statusIndex].tasks.length,
            "status": $scope.tasks[statusIndex].entity,
            "filter_id": filterId
        };
        $scope.tasks[statusIndex].tasks.push(newTodo);
        $scope.editTodo(newTodo);
    };

    $scope.keypressCallback = function (evt, statusIndex, listIndex) {
        if (evt.keyCode == '27') {
            $scope.revertEditing(statusIndex, listIndex);
        }
    };


    $scope.revertEditing = function (statusIndex, listIndex) {
        $scope.tasks[statusIndex].tasks[listIndex] = $scope.originalTodo;
        $scope.editedTodo = null;
        for (var i = $scope.tasks[statusIndex].tasks.length - 1; i >= 0; i--) {
            if ($scope.tasks[statusIndex].tasks[i].id === undefined) {
                $scope.tasks[statusIndex].tasks.splice(i, 1);
            }
        }
    };

    $scope.saveTask = function (statusIndex, listIndex) {

        $scope.editedTodo.filter_id = filterId;
        $scope.editedTodoCopy = $scope.editedTodo;
        $scope.editedTodo = null;
        $http.post(rootPath + 'p/api/kanban/saveTask', $scope.editedTodoCopy).then(function (response) {
            $scope.tasks[statusIndex].tasks[listIndex] = response.data;
        }, function (response) {
        });
        stopSync = false;
    };

    $scope.doneEditingExtended = function () {
        $scope.taskEditExtended.filter_id = filterId;
        $http.post(rootPath + 'p/api/kanban/updateTaskExtended/' + $scope.taskEditExtended.id, $scope.taskEditExtended).then(function (response) {
            $scope.taskEditExtended = null;

            modalInstance.close();
        }, function (response) {
            $scope.taskEditExtended = null;
            modalInstance.close();
        });
        ;
    };

    $scope.doneEditing = function (todo) {
        $scope.editedTodo = null;
        todo.title = todo.title.trim();

        if (!todo.title) {
            $scope.removeTodo(todo);
        }
    };

    $scope.logTime = function (task) {
        $scope.timelogEditing = angular.copy($scope.timelog);
        $scope.timelogEditing.task = task;
        modalInstance = $modal.open({
            size: "sm",
            templateUrl: 'logTime.html',
            scope: $scope
        });
    };

    $scope.doneLogTime = function () {
        $http.post(rootPath + 'timelog/create/dinamic', $scope.timelogEditing).then(function (response) {
            $scope.timelogEditing = null;
            modalInstance.close();
        }, function (response) {
            $scope.timelogEditing = null;
            modalInstance.close();
        });

    };

    /* realtime updates */
    pusher.subscribe('filter-' + filterId);
    pusher.bind('position-update', function (data) {
        $scope.findAll();
    });
    $scope.findAvailableUsers();
    $scope.findTaskStatuses();


    $scope.toggleFilter = function (element) {
        $scope[element] = !$scope[element];
        if ($scope[element]) {
            var element = $window.document.getElementById(element);
            if (element) {
                $timeout(function () {
                    element.focus();
                })
            }
        }
    };

    var selectedTasks = function () {
        var tasksToBeChanged = [];
        var i;
        for (i in $scope.tasks) {
            var task = $scope.tasks[i];
            if (task.selected) {
                tasksToBeChanged.push(task.id);
            }
        }
        return tasksToBeChanged;
    }

    $scope.bulkStatusChange = function (statusId) {
        var tasksToBeChanged = selectedTasks();

        if (tasksToBeChanged.length > 0) {
            $http.post(rootPath + 'task/' + statusId + '/bulk_status', {
                "tasks": tasksToBeChanged
            }).then(function (response) {
                $scope.findAll();
            }, function (response) {

            });
        }
    };

    $scope.bulkChangeAssignee = function (userId) {
        var tasksToBeChanged = selectedTasks();

        if (tasksToBeChanged.length > 0) {
            $http.post(rootPath + 'task/' + userId + '/bulk_user', {
                "tasks": tasksToBeChanged
            }).then(function (response) {
                $scope.findAll();
            }, function (response) {

            });
        }
    };

    $scope.keypressFilter = function (a, b, c, document) {

    };
})
;
