<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Задачи</h5>

    <button type="button" class="btn btn-primary my-0 mr-2" data-toggle="modal" data-target="#createTask">
        Создать задачу
    </button>
</div>
<div class="container">
    <?php if (@$_GET['success_create']) : ?>
        <div class="alert alert-success text-center" role="alert">
            Задача создана!
        </div>
        <script>
            setTimeout(function() {
                window.location.href = '/';
            }, 2000);
        </script>
    <?php endif; ?>
    <?php if (@$_GET['error']) : ?>
        <div class="alert alert-danger" role="alert">
            Обязательные поля не заполнены!
        </div>
        <script>
            setTimeout(function() {
                window.location.href = '/';
            }, 2000);
        </script>
    <?php endif; ?>
    <?php if (@$_GET['success_update']) : ?>
        <div class="alert alert-success" role="alert">
            Задача обновлена!
        </div>
        <script>
            setTimeout(function() {
                window.location.href = '/';
            }, 2000);
        </script>
    <?php endif; ?>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="?order=user_name&orderDir=<?php echo $orderDir == 'asc' ? 'desc' : 'asc'; ?><?php echo $currentPage != 1 ? ('&page=' . $currentPage) : ''; ?>">
                            Имя пользователя
                            <?php if ($order == 'user_name') : ?>
	                            <?php echo $orderDir == 'asc' ? '↑' : '↓'; ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th>
                        <a href="?order=email&orderDir=<?php echo $orderDir == 'asc' ? 'desc' : 'asc'; ?><?php echo $currentPage != 1 ? '&page=' . $currentPage : ''; ?>">
                            Email
	                        <?php if ($order == 'email') : ?>
		                        <?php echo $orderDir == 'asc' ? '↑' : '↓'; ?>
	                        <?php endif; ?>
                        </a>
                    </th>
                    <th>
                        Описание задачи
                    </th>
                    <th>
                        <a href="?order=status&orderDir=<?php echo $orderDir == 'asc' ? 'desc' : 'asc'; ?><?php echo $currentPage != 1 ?  '&page=' . $currentPage : ''; ?>">
                            Статус
	                        <?php if ($order == 0) : ?>
		                        <?php echo $orderDir == 'asc' ? '↑' : '↓'; ?>
	                        <?php endif; ?>
                        </a>
                    </th>
                    <?php if (\Core\Auth::isAdmin()) : ?>
                        <th>Действия</th>
                    <?php endif; ?>

                </tr>
            </thead>
        <tbody>
            <?php foreach ($tasks as $task) : ?>
                <tr>
                    <td scope="row"><?php echo $task->user_name; ?></td>
                    <td><?php echo $task->email; ?></td>
                    <td id="task_description_<?php echo $task->id; ?>"><?php echo $task->description; ?></td>
                    <td><?php echo $status_names[$task->status]; ?></td>
                    <?php if (\Core\Auth::isAdmin()) : ?>
                        <td>
                            <button type="button" onclick="edit(<?php echo $task->id; ?>, <?php echo $task->status; ?>)" class="btn btn-link btn-primary btn-sm">
                                Редактировать
                            </button>
                        </td>
                    <?php endif; ?>

                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
        <?php if ($pagesQty > 1) : ?>
            <nav class="float-right">
                <ul class="pagination">
                    <?php if ($currentPage <= 1) : ?>
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Предыдущая</span>
                            </span>
                        </li>
                    <?php else : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($currentPage - 1) . '&' . $append; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Предыдущая</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php foreach (range(1, $pagesQty) as $page) : ?>
                        <li class="page-item <?php echo $page == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page . '&' . $append; ?>">
                                <?php echo $page; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php if ($currentPage >= $pagesQty) : ?>
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Следующая</span>
                            </span>
                        </li>
                    <?php else : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($currentPage + 1) . '&' . $append; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Следующая</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>



<div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Создать задачу</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post" action="task/store" id="formTaskCreate">
              <div class="form-group">
                  <label for="user_name">Имя пользователя <span class="star">*</span></label>
                  <input class="form-control" name="user_name" id="user_name" maxlength="255" autofocus required>
              </div>
              <div class="form-group">
                  <label for="email">Email <span class="star">*</span></label>
                  <input type="email" class="form-control" for="email" name="email" maxlength="255" required>
              </div>
              <div class="form-group">
                  <label for="description">Описание задачи <span class="star">*</span></label>
                  <textarea class="form-control" for="description" name="description" maxlength="255" required></textarea>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button class="btn btn-primary" form="formTaskCreate">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Создать задачу</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post" action="task/store" id="formTaskCreate">
              <div class="form-group">
                  <label for="user_name">Имя пользователя <span class="star">*</span></label>
                  <input class="form-control" name="user_name" id="user_name" maxlength="255" autofocus required>
              </div>
              <div class="form-group">
                  <label for="email">Email <span class="star">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" maxlength="255" required>
              </div>
              <div class="form-group">
                  <label for="description">Описание задачи <span class="star">*</span></label>
                  <textarea class="form-control" id="description" name="description" maxlength="255" required></textarea>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button class="btn btn-primary" form="formTaskCreate">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<?php if (\Core\Auth::isAdmin()) : ?>
<div class="modal fade" id="updateTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Создать задачу</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post" action="task/update" id="formTaskUpdate">
              <input type="hidden" name="id" id="id_update">
              <div class="form-group">
                  <label for="description">Описание задачи <span class="star">*</span></label>
                  <textarea class="form-control" id="description_update" name="description" required></textarea>
              </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="Radios1" value="0">
                    <label class="form-check-label" for="Radios1">
                        Отредактировано администратором
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="Radios2" value="1">
                    <label class="form-check-label" for="Radios2">
                        В процессе
                    </label>
                </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button class="btn btn-primary" form="formTaskUpdate">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<script>
 function edit(id, status)
 {
     $('#updateTask').modal('show');
     $('#id_update').val(id);
     let description = decodeHtml($("#task_description_" + id).html());
     $('#description_update').val(description);
     if (status == 0) {
        $('#Radios1').click();
     } else {
         $('#Radios2').click();
     }

 }

 function decodeHtml(html) {
     var txt = document.createElement("textarea");
     txt.innerHTML = html;
     return txt.value;
 }

</script>
<?php endif; ?>
