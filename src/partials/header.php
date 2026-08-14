<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>DevTrack</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app">
<aside class="sidebar">
  <div class="brand"><span>◆</span> DevTrack</div>
  <div class="workspace">WORKSPACE</div>
  <nav>
    <a href="index.php">⌂ <span>Dashboard</span></a>
    <a href="projects.php">▦ <span>Projects</span></a>
    <a href="tasks.php">✓ <span>Tasks</span></a>
    <a href="team.php">♙ <span>Team</span></a>
    <a href="reports.php">◔ <span>Reports</span></a>
  </nav>
  <div class="sidebar-bottom">
    <div class="user-mini"><div class="avatar"><?= strtoupper(substr(user()['name'],0,1)) ?></div><div><strong><?= e(user()['name']) ?></strong><small><?= e(user()['role']) ?></small></div></div>
    <a class="logout" href="logout.php">↪ Sign out</a>
  </div>
</aside>
<main class="main">
<header class="topbar"><div class="mobile-brand">DevTrack</div><div class="top-actions"><span>🔔</span><div class="avatar"><?= strtoupper(substr(user()['name'],0,1)) ?></div></div></header>
<div class="content">
