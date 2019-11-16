<?php

namespace App\Controllers;

use Core\Auth;

class AdminController
{
	public function index()
	{
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic realm="Tasks"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Вы отменили авторизацию.';
			exit;
		} else {
			if (Auth::isAdmin()) {
				header('Location: /');
				exit;
			} else {
				header('WWW-Authenticate: Basic realm="Tasks"');
				header('HTTP/1.0 401 Unauthorized');
				echo 'Вы отменили авторизацию.';
				exit;
			}
		}
	}
}
