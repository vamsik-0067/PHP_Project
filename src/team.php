<?php
require_once 'config.php';
require_login();
$pdo=db();
$users=$pdo->query("
 SELECT u.id,u.name,u.email,u.role,u.created_at,
 (SELECT COUNT(*) FROM tasks t WHERE t.assignee_id=u.id) task_count,
 (SELECT COUNT(*) FROM tasks t WHERE t.assignee_id=u.id AND t.status='completed') done_count
 FROM users u ORDER BY u.id
")->fetchAll();
include_once 'partials/header.php';
?>
<div class="page-title"><div><div class="eyebrow">PEOPLE</div><h1>Team</h1><p>Manage project contributors and see workload.</p></div></div>
<div class="team-grid">
<?php foreach($users as $u): ?>
<div class="person-card">
 <div class="avatar big"><?= strtoupper(substr($u['name'],0,1)) ?></div>
 <h2><?= e($u['name']) ?></h2><span class="role"><?= ucfirst($u['role']) ?></span>
 <p><?= e($u['email']) ?></p>
 <div class="person-stats"><span><strong><?= $u['task_count'] ?></strong>Tasks</span><span><strong><?= $u['done_count'] ?></strong>Done</span></div>
</div>
<?php endforeach; ?>
</div>
<?php include_once 'partials/footer.php'; ?>
