<?php
require_once 'config.php';
require_login();
$pdo=db();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt=$pdo->prepare("INSERT INTO tasks(project_id,title,description,status,priority,assignee_id,due_date) VALUES(?,?,?,?,?,?,?)");
    $stmt->execute([(int)$_POST['project_id'],trim($_POST['title']),trim($_POST['description']),$_POST['status'],$_POST['priority'],(int)$_POST['assignee_id'],$_POST['due_date'] ?: null]);
    $pdo->prepare("INSERT INTO activities(user_id,message) VALUES(?,?)")->execute([user()['id'],"Created task: ".trim($_POST['title'])]);
    redirect('tasks.php');
}
if(isset($_GET['delete'])){
    $pdo->prepare("DELETE FROM tasks WHERE id=?")->execute([(int)$_GET['delete']]);
    redirect('tasks.php');
}
$projects=$pdo->query("SELECT id,name FROM projects ORDER BY name")->fetchAll();
$users=$pdo->query("SELECT id,name FROM users ORDER BY name")->fetchAll();
$rows=$pdo->query("SELECT t.*,p.name project,u.name assignee FROM tasks t JOIN projects p ON p.id=t.project_id LEFT JOIN users u ON u.id=t.assignee_id ORDER BY t.id DESC")->fetchAll();

include 'partials/header.php';
?>
<div class="page-title"><div><div class="eyebrow">EXECUTION</div><h1>Tasks</h1><p>Track every task from backlog to completion.</p></div><button class="btn primary" onclick="document.getElementById('taskForm').classList.toggle('hidden')">+ New Task</button></div>

<div id="taskForm" class="panel hidden">
 <div class="panel-head"><h2>Create task</h2></div>
 <form method="post" class="form-grid">
  <div class="field full-field"><label>Task title</label><input name="title" required placeholder="What needs to be done?"></div>
  <div class="field full-field"><label>Description</label><textarea name="description" rows="2"></textarea></div>
  <div class="field"><label>Project</label><select name="project_id"><?php foreach($projects as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option><?php endforeach; ?></select></div>
  <div class="field"><label>Assignee</label><select name="assignee_id"><?php foreach($users as $u): ?><option value="<?= $u['id'] ?>"><?= e($u['name']) ?></option><?php endforeach; ?></select></div>
  <div class="field"><label>Status</label><select name="status"><option>todo</option><option>in_progress</option><option>testing</option><option>completed</option></select></div>
  <div class="field"><label>Priority</label><select name="priority"><option>low</option><option selected>medium</option><option>high</option><option>critical</option></select></div>
  <div class="field"><label>Due date</label><input type="date" name="due_date"></div>
  <div class="full-field"><button class="btn primary">Create Task</button></div>
 </form>
</div>

<div class="panel"><div class="panel-head"><h2>Task board</h2><span class="muted"><?= count($rows) ?> tasks</span></div>
<div class="task-grid">
<?php foreach($rows as $t): ?>
<div class="task-card">
 <div class="row-top"><span class="badge <?= e($t['status']) ?>"><?= ucwords(str_replace('_',' ',$t['status'])) ?></span><span class="priority <?= e($t['priority']) ?>"><?= ucfirst($t['priority']) ?></span></div>
 <h3><?= e($t['title']) ?></h3>
 <p><?= e($t['description']) ?></p>
 <div class="task-meta"><span>◼ <?= e($t['project']) ?></span><span>👤 <?= e($t['assignee'] ?? 'Unassigned') ?></span></div>
 <div class="task-bottom"><small>Due <?= e($t['due_date'] ?? '—') ?></small><a class="danger-link" onclick="return confirm('Delete this task?')" href="?delete=<?= $t['id'] ?>">Delete</a></div>
</div>
<?php endforeach; ?>
</div></div>
<?php include 'partials/footer.php'; ?>
