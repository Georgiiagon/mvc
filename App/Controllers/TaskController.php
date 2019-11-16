<?php

namespace App\Controllers;

use Core\View;
use App\Models\Task;

class TaskController {

    public function index()
    {
        $limit = 3;
        $order = 'description';
        $orderDir = 'desc';

        if (@$_COOKIE['order'])
            $order = $_COOKIE['order'];

        if (@$_COOKIE['orderDir'])
            $orderDir = $_COOKIE['orderDir'];

        if (@$_GET['order']) {
            $order = $_GET['order'];
            setcookie("order", $order, 0, "/");
        }

        if (@$_GET['orderDir']) {
            $orderDir = $_GET['orderDir'];
            setcookie("orderDir", $orderDir, 0, "/");
        }

        $count    = Task::count();
        $pagesQty = ceil($count / $limit);
        (@$_GET['page'] !== null && @$_GET['page'] != 1) ? $currentPage = @$_GET['page'] : $currentPage = 1;

        $tasks = Task::paginate($currentPage, 3, $order, $orderDir);

        $status_names = Task::STATUS_NAMES;
        $append = http_build_query([$_GET['order'] ?? 'order', $_GET['orderDir'] ?? 'orderDir']);

        return View::render('index', [
            'tasks' => $tasks,
            'pagesQty' => $pagesQty,
            'currentPage' => $currentPage,
            'order' => $order,
            'orderDir' => $orderDir,
            'status_names' => $status_names,
            'append' => $append
        ]);
    }

    public function store()
    {
        $user_name = trim(htmlspecialchars($_POST['user_name']));
        $email = trim(htmlspecialchars($_POST['email']));
        $description = trim(htmlspecialchars($_POST['description']));

        if (!$user_name || !$email || !$description)
        {
            header('Location: /?error=1');
            exit;
        }

        $task = new Task([
            'user_name'        => $user_name,
            'email'       => $email,
            'description' => $description,
            'status' => 1
        ]);

        $task->save();

        header('Location: /task?success_create=1');
        exit;
    }

    public function update()
    {
        $description = trim(htmlspecialchars($_POST['description']));
        $id = (int) htmlspecialchars($_POST['id']);
        if (in_array($_POST['status'], array_keys(Task::STATUS_NAMES))) {
            $status = (int) $_POST['status'];
        }

        $task = (new Task())->find($id);

        $task->description = $description;
        $task->status = $status ?? $task->status;

        $task->update();

        header('Location: /?success_update=1');
        exit;
    }
}
