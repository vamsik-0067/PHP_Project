<?php
require_once 'config.php';
require_login();
$pdo = db();

if (isset($_GET['delete'])) {
    $stmt=$pdo->prepare("DELETE FROM projects WHERE id=?");
    $stmt->execute([(int)$_GET['delete']]);
    redirect('projects.php');
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt=$pdo->prepare("INSERT INTO projects(name,description,status,priority,start_date,due_date,owner_id) VALUES(?,?,?,?,?,?,?)");
    $stmt->execute([
        trim($_POST['name']), trim($_POST['description']), $_POST['status'], $_POST['priority'],
        $_POST['start_date'] ?: null, $_POST['due_date'] ?: null, (int)$_POST['owner_id']
    ]);
    $id=$pdo->lastInsertId();
    $pdo->prepare("INSERT INTO activities(user_id,message) VALUES(?,?)")->execute([user()['id'],"Created project: ".trim($_POST['name'])]);
    redirect('projects.php');
}

$showForm = ($_GET['action'] ?? '') === 'new';
$owners=$pdo->query("SELECT id,name FROM users ORDER BY name")->fetchAll();
$rows=$pdo->query("
 SELECT p.*,u.name owner,
 (SELECT COUNT(*) FROM tasks t WHERE t.project_id=p.id) total_tasks,
 (SELECT COUNT(*) FROM tasks t WHERE t.project_id=p.id AND t.status='completed') done_tasks
 FROM projects p LEFT JOIN users u ON u.id=p.owner_id ORDER BY p.id DESC
")->fetchAll();

include_once 'partials/header.php';
?>
<div class="page-title">
 <div><div class="eyebrow">WORKSPACE</div><h1>Projects</h1><p>Plan, track and deliver your team's work.</p></div>
 <a class="btn primary" href="projects.php?action=new">+ New Project</a>
</div>

<?php if ($showForm): ?>
<div class="panel form-panel">
 <div class="panel-head"><h2>Create project</h2><a href="projects.php">Cancel</a></div>
 <form method="post" class="form-grid">
  <div class="field full-field"><label>Project name</label><input name="name" required placeholder="e.g. Customer Portal"></div>
  <div class="field full-field"><label>Description</label><textarea name="description" rows="3" placeholder="What are you building?"></textarea></div>
  <div class="field"><label>Status</label><select name="status"><option>planning</option><option>active</option><option>on_hold</option><option>completed</option></select></div>
  <div class="field"><label>Priority</label><select name="priority"><option>low</option><option selected>medium</option><option>high</option><option>critical</option></select></div>
  <div class="field"><label>Start date</label><input type="date" name="start_date"></div>
  <div class="field"><label>Due date</label><input type="date" name="due_date"></div>
  <div class="field"><label>Owner</label><select name="owner_id"><?php foreach($owners as $o): ?><option value="<?= $o['id'] ?>"><?= e($o['name']) ?></option><?php endforeach; ?></select></div>
  <div class="full-field"><button class="btn primary">Create Project</button></div>
 </form>
</div>
<?php endif; ?>

<div class="panel">
 <div class="panel-head"><h2>All projects</h2><span class="muted"><?= count($rows) ?> projects</span></div>
 <div class="table-wrap"><table><thead><tr><th>Project</th><th>Status</th><th>Priority</th><th>Owner</th><th>Progress</th><th>Due</th><th></th></tr></thead><tbody>
 <?php foreach($rows as $p): $pct=$p['total_tasks']?round($p['done_tasks']/$p['total_tasks']*100):0; ?>
 <tr>
  <td><strong><?= e($p['name']) ?></strong><br><small><?= e($p['description']) ?></small></td>
  <td><span class="badge <?= e($p['status']) ?>"><?= ucwords(str_replace('_',' ',$p['status'])) ?></span></td>
  <td><span class="priority <?= e($p['priority']) ?>"><?= ucfirst($p['priority']) ?></span></td>
  <td><?= e($p['owner']) ?></td>
  <td><div class="mini-progress"><span style="width:<?= $pct ?>%"></span></div><small><?= $pct ?>%</small></td>
  <td><?= e($p['due_date']) ?></td>
  <td><a class="danger-link" onclick="return confirm('Delete this project?')" href="?delete=<?= $p['id'] ?>">Delete</a></td>
 </tr>
 <?php endforeach; ?>
 </tbody></table></div>
</div>
<?php include_once 'partials/footer.php'; ?>
