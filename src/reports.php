<?php
require_once 'config.php';
require_login();
$pdo=db();
$status=$pdo->query("SELECT status,COUNT(*) count FROM tasks GROUP BY status")->fetchAll();
$priority=$pdo->query("SELECT priority,COUNT(*) count FROM tasks GROUP BY priority")->fetchAll();
$projectStats=$pdo->query("SELECT p.name,COUNT(t.id) total,SUM(t.status='completed') done FROM projects p LEFT JOIN tasks t ON t.project_id=p.id GROUP BY p.id ORDER BY total DESC")->fetchAll();
include_once 'partials/header.php';
?>
<div class="page-title"><div><div class="eyebrow">INSIGHTS</div><h1>Reports</h1><p>Understand delivery progress at a glance.</p></div></div>
<div class="report-grid">
<div class="panel"><div class="panel-head"><h2>Tasks by status</h2></div><?php foreach($status as $s): ?><div class="bar-row"><span><?= ucwords(str_replace('_',' ',$s['status'])) ?></span><strong><?= $s['count'] ?></strong><div class="bar"><i style="width:<?= min(100,$s['count']*15) ?>%"></i></div></div><?php endforeach; ?></div>
<div class="panel"><div class="panel-head"><h2>Tasks by priority</h2></div><?php foreach($priority as $p): ?><div class="bar-row"><span><?= ucfirst($p['priority']) ?></span><strong><?= $p['count'] ?></strong><div class="bar"><i style="width:<?= min(100,$p['count']*15) ?>%"></i></div></div><?php endforeach; ?></div>
</div>
<div class="panel"><div class="panel-head"><h2>Project delivery</h2></div><div class="table-wrap"><table><caption>List of all projects, including their status, priority, owner, progress, and due date.</caption><thead><tr><th>Project</th><th>Total Tasks</th><th>Completed</th><th>Progress</th></tr></thead><tbody>
<?php foreach($projectStats as $p): $pct=$p['total']?round($p['done']/$p['total']*100):0; ?><tr><td><strong><?= e($p['name']) ?></strong></td><td><?= $p['total'] ?></td><td><?= $p['done'] ?></td><td><div class="mini-progress"><span style="width:<?= $pct ?>%"></span></div><?= $pct ?>%</td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php include_once 'partials/footer.php'; ?>
