<?php

namespace App\Models;

use Core\Model;

class Task extends Model
{
	protected $table = 'tasks';

    const STATUS_NAMES = [
        0 => 'Отредактировано администратором',
        1 => 'В процессе'
    ];
}
