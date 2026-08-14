<?php
require_once 'config.php';
require_login();

$pdo = db();
$projects = (int)$pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$active = (int)$pdo->query("SELECT COUNT(*) FROM projects WHERE status='active'")->fetchColumn();
$completed = (int)$pdo->query("SELECT COUNT(*) FROM projects WHERE status='completed'")->fetchColumn();
$tasks = (int)$pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
$openTasks = (int)$pdo->query("SELECT COUNT(*) FROM tasks WHERE status <> 'completed'")->fetchColumn();

$recent = $pdo->query("
    SELECT a.message,a.created_at,u.name
    FROM activities a LEFT JOIN users u ON u.id=a.user_id
    ORDER BY a.id DESC LIMIT 6
")->fetchAll();

$projectRows = $pdo->query("
    SELECT p.*, u.name AS owner,
           (SELECT COUNT(*) FROM tasks t WHERE t.project_id=p.id) total_tasks,
           (SELECT COUNT(*) FROM tasks t WHERE t.project_id=p.id AND t.status='completed') done_tasks
    FROM projects p LEFT JOIN users u ON u.id=p.owner_id
    ORDER BY p.id DESC LIMIT 5
")->fetchAll();

include_once 'partials/header.php';
?>
<div class="page-title">
    <div>
        <div class="eyebrow">OVERVIEW</div>
        <h1>Good morning, <?= e(user()['name']) ?> 👋</h1>
        <p>Here is what's happening across your projects today.</p>
    </div>
    <a class="btn primary" href="projects.php?action=new">+ New Project</a>
</div>

<div class="stats">
    <div class="stat"><span class="stat-icon blue">▦</span><div><small>Total Projects</small><strong><?= $projects ?></strong><em><?= $active ?> active</em></div></div>
    <div class="stat"><span class="stat-icon green">✓</span><div><small>Completed</small><strong><?= $completed ?></strong><em>Projects delivered</em></div></div>
    <div class="stat"><span class="stat-icon purple">☷</span><div><small>Total Tasks</small><strong><?= $tasks ?></strong><em><?= $openTasks ?> still open</em></div></div>
    <div class="stat"><span class="stat-icon orange">◷</span><div><small>Open Tasks</small><strong><?= $openTasks ?></strong><em>Needs attention</em></div></div>
</div>

<div class="two-col">
<section class="panel">
    <div class="panel-head"><h2>Projects</h2><a href="projects.php">View all →</a></div>
    <div class="project-list">
    <?php foreach ($projectRows as $p): 
        $pct = $p['total_tasks'] ? round(($p['done_tasks']/$p['total_tasks'])*100) : 0;
    ?>
      <div class="project-row">
        <div class="project-mark"><?= strtoupper(substr($p['name'],0,1)) ?></div>
        <div class="project-main">
          <div class="row-top"><strong><?= e($p['name']) ?></strong><span class="badge <?= e($p['status']) ?>"><?= ucwords(str_replace('_',' ',$p['status'])) ?></span></div>
          <p><?= e($p['description']) ?></p>
          <div class="progress"><span style="width:<?= $pct ?>%"></span></div>
          <small><?= $pct ?>% complete · <?= $p['done_tasks'] ?>/<?= $p['total_tasks'] ?> tasks</small>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <div class="panel-head"><h2>Recent activity</h2><span class="muted">Latest updates</span></div>
    <div class="activity">
    <?php foreach ($recent as $a): ?>
      <div class="activity-row">
        <div class="avatar"><?= strtoupper(substr($a['name'] ?? 'S',0,1)) ?></div>
        <div><strong><?= e($a['name'] ?? 'System') ?></strong><p><?= e($a['message']) ?></p><small><?= e($a['created_at']) ?></small></div>
      </div>
    <?php endforeach; ?>
    </div>
</section>
</div>
<?php include 'partials/footer.php'; ?>
