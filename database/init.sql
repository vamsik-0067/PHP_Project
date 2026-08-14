CREATE DATABASE IF NOT EXISTS devtrack;
USE devtrack;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','manager','developer') DEFAULT 'developer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('planning','active','completed','on_hold') DEFAULT 'planning',
    priority ENUM('low','medium','high','critical') DEFAULT 'medium',
    start_date DATE,
    due_date DATE,
    owner_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    status ENUM('todo','in_progress','testing','completed') DEFAULT 'todo',
    priority ENUM('low','medium','high','critical') DEFAULT 'medium',
    assignee_id INT NULL,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (name,email,password,role) VALUES
('Admin User','admin@devtrack.local',SHA2('admin123',256),'admin'),
('Vamsi Developer','vamsi@devtrack.local',SHA2('developer123',256),'developer'),
('Project Manager','manager@devtrack.local',SHA2('manager123',256),'manager');

INSERT INTO projects (name,description,status,priority,start_date,due_date,owner_id) VALUES
('E-Commerce Platform','Build and release the new customer shopping platform.','active','high',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 30 DAY),2),
('Internal Automation Portal','Central portal for automation execution and reporting.','planning','medium',CURDATE(),DATE_ADD(CURDATE(),INTERVAL 45 DAY),3),
('Customer Analytics','Analytics dashboard and reporting module.','completed','low',DATE_SUB(CURDATE(),INTERVAL 60 DAY),DATE_SUB(CURDATE(),INTERVAL 10 DAY),3);

INSERT INTO tasks (project_id,title,description,status,priority,assignee_id,due_date) VALUES
(1,'Design login experience','Create responsive login and registration screens.','completed','high',2,DATE_ADD(CURDATE(),INTERVAL 2 DAY)),
(1,'Build product module','Implement product listing and search.','in_progress','high',2,DATE_ADD(CURDATE(),INTERVAL 7 DAY)),
(1,'Write API documentation','Document project endpoints.','todo','medium',3,DATE_ADD(CURDATE(),INTERVAL 12 DAY)),
(2,'Create dashboard','Build project metrics dashboard.','in_progress','medium',2,DATE_ADD(CURDATE(),INTERVAL 15 DAY)),
(2,'Database schema','Finalize relational database design.','testing','high',3,DATE_ADD(CURDATE(),INTERVAL 5 DAY)),
(3,'Export reports','Add CSV reporting.','completed','low',2,DATE_SUB(CURDATE(),INTERVAL 12 DAY));

INSERT INTO activities (user_id,message) VALUES
(2,'Completed the login experience task'),
(3,'Moved Database schema to testing'),
(2,'Started work on the product module'),
(3,'Created Internal Automation Portal project');
