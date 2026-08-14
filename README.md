# DevTrack — PHP Project Management System

A real-world PHP + MySQL project management application. Docker is used only to run the PHP application and MySQL database.

## Features

- Login / logout
- Role-based user data
- Dashboard
- Project creation and deletion
- Task creation and deletion
- Team management
- Reports
- Progress tracking
- Recent activity
- MySQL persistence
- Responsive modern UI

## Stack

- PHP 8.3
- Apache
- MySQL 8.4
- HTML/CSS/JavaScript
- Docker
- Docker Compose

## Run

```bash
docker compose up -d --build
```

Open:

http://localhost:8080

## Demo login

Admin:

```text
admin@devtrack.local
admin123
```

Developer:

```text
vamsi@devtrack.local
developer123
```

## Useful commands

```bash
docker compose ps
docker compose logs -f web
docker compose logs -f db
docker exec -it devtrack-web bash
docker exec -it devtrack-db bash
```

Stop:

```bash
docker compose down
```

Remove database volume too:

```bash
docker compose down -v
```

## Folder structure

```text
devtrack_php/
├── Dockerfile
├── docker-compose.yml
├── database/
│   └── init.sql
├── src/
│   ├── assets/
│   │   └── style.css
│   ├── partials/
│   │   ├── header.php
│   │   └── footer.php
│   ├── config.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── projects.php
│   ├── tasks.php
│   ├── team.php
│   └── reports.php
└── README.md
```

## Important

This is intentionally a straightforward PHP application so the code is easy to understand and modify. For production, add CSRF protection, stronger password hashing with `password_hash()`, authorization middleware, validation, secure cookies, secrets management, migrations, and HTTPS.
