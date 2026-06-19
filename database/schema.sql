CREATE DATABASE IF NOT EXISTS isims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE isims;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS activity_logs, notifications, faqs, message_replies, messages,
  resources, calendar_events, events, announcements, users, departments, programs,
  faculties, roles, settings;
SET FOREIGN_KEY_CHECKS = 1;

-- ── Roles ──────────────────────────────────────────────────────────
CREATE TABLE roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  slug VARCHAR(80) NOT NULL UNIQUE,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── System Settings ────────────────────────────────────────────────
CREATE TABLE settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(80) NOT NULL UNIQUE,
  `value` TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Faculties ──────────────────────────────────────────────────────
CREATE TABLE faculties (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL UNIQUE,
  code VARCHAR(30) NOT NULL UNIQUE,
  description TEXT NULL,
  color VARCHAR(20) NOT NULL DEFAULT '#1f6feb',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Departments ────────────────────────────────────────────────────
CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  faculty_id INT UNSIGNED NULL,
  name VARCHAR(120) NOT NULL UNIQUE,
  code VARCHAR(30) NOT NULL UNIQUE,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_dept_faculty FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE SET NULL,
  INDEX idx_dept_faculty (faculty_id)
) ENGINE=InnoDB;

-- ── Programs ───────────────────────────────────────────────────────
CREATE TABLE programs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  faculty_id INT UNSIGNED NOT NULL,
  department_id INT UNSIGNED NULL,
  name VARCHAR(220) NOT NULL,
  level ENUM('bachelor','master','doctorate','diploma','certificate') NOT NULL DEFAULT 'bachelor',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_prog_faculty FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE,
  CONSTRAINT fk_prog_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  INDEX idx_prog_faculty (faculty_id),
  INDEX idx_prog_level (level)
) ENGINE=InnoDB;

-- ── Users ──────────────────────────────────────────────────────────
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id INT UNSIGNED NOT NULL,
  department_id INT UNSIGNED NULL,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(40) NULL,
  student_number VARCHAR(60) NULL UNIQUE,
  position VARCHAR(120) NULL,
  status ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
  reset_token VARCHAR(120) NULL,
  reset_token_expires_at DATETIME NULL,
  last_login_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id),
  CONSTRAINT fk_users_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  INDEX idx_users_role_status (role_id, status),
  INDEX idx_users_department (department_id)
) ENGINE=InnoDB;

-- ── Announcements ──────────────────────────────────────────────────
CREATE TABLE announcements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  department_id INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  body TEXT NOT NULL,
  audience ENUM('public','students','department') NOT NULL DEFAULT 'public',
  is_archived TINYINT(1) NOT NULL DEFAULT 0,
  published_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_ann_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_ann_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  INDEX idx_ann_audience (audience, is_archived, published_at)
) ENGINE=InnoDB;

-- ── Events ────────────────────────────────────────────────────────
CREATE TABLE events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NULL,
  category ENUM('prayer_week','sports','seminar','meeting','general') NOT NULL DEFAULT 'general',
  location VARCHAR(180) NULL,
  starts_at DATETIME NOT NULL,
  ends_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_events_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_events_dates (starts_at)
) ENGINE=InnoDB;

-- ── Calendar Events ────────────────────────────────────────────────
CREATE TABLE calendar_events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  event_type ENUM('academic_year','exam','holiday','registration','other') NOT NULL DEFAULT 'other',
  starts_on DATE NOT NULL,
  ends_on DATE NULL,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cal_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Resources ─────────────────────────────────────────────────────
CREATE TABLE resources (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  department_id INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NULL,
  category ENUM('pdf','notes','past_paper','study_guide','other') NOT NULL DEFAULT 'other',
  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(120) NOT NULL,
  file_size INT UNSIGNED NOT NULL,
  downloads INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_res_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  FULLTEXT KEY ft_res (title, description)
) ENGINE=InnoDB;

-- ── Messages ──────────────────────────────────────────────────────
CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  assigned_to INT UNSIGNED NULL,
  subject VARCHAR(180) NOT NULL,
  category ENUM('general_inquiry','academic_concern','resource_request','event_question','complaint','suggestion') NOT NULL,
  body TEXT NOT NULL,
  status ENUM('open','in_progress','resolved') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_msg_student FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_msg_assignee FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_msg_status (student_id, status)
) ENGINE=InnoDB;

CREATE TABLE message_replies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  message_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_rep_msg FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
  CONSTRAINT fk_rep_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_rep_msg (message_id, created_at)
) ENGINE=InnoDB;

-- ── FAQs ──────────────────────────────────────────────────────────
CREATE TABLE faqs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  category ENUM('academic','immigration','registration','campus_life','resources','general') NOT NULL DEFAULT 'general',
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_faq_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FULLTEXT KEY ft_faq (question, answer)
) ENGINE=InnoDB;

-- ── Notifications ─────────────────────────────────────────────────
CREATE TABLE notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  type VARCHAR(80) NOT NULL,
  title VARCHAR(180) NOT NULL,
  body TEXT NULL,
  link VARCHAR(255) NULL,
  read_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_notif_user (user_id, read_at)
) ENGINE=InnoDB;

-- ── Activity Logs ─────────────────────────────────────────────────
CREATE TABLE activity_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(120) NOT NULL,
  context TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_log_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_log_date (created_at)
) ENGINE=InnoDB;

-- ════════════════════════════════════════════════════════════════════
-- SEED DATA
-- ════════════════════════════════════════════════════════════════════

INSERT INTO roles (name, slug, description) VALUES
('Student',     'student',      'Student account'),
('ISR Leader',  'isr_leader',   'President, Vice, SG, Librarian, or authorized SR leader'),
('Leader Admin','leader_admin', 'Creates and manages ISR leader accounts'),
('System Admin','system_admin', 'Full system administrator');

-- System settings
INSERT INTO settings (`key`, `value`) VALUES
('app_name',        'SIMS'),
('school_name',     'University of Lay Adventists of Kigali (UNILAK)'),
('campus_name',     'Nyanza Campus'),
('school_country',  'Rwanda'),
('school_email',    'info@unilak.ac.rw'),
('school_phone',    '+250 788 000 000'),
('school_address',  'Nyanza, Southern Province, Rwanda'),
('logo_path',       ''),
('campus_photo',    ''),
('primary_color',   '#1f6feb'),
('accent_color',    '#0f766e');

-- Faculties
INSERT INTO faculties (name, code, description, color) VALUES
('Faculty of Computing and Information Sciences',   'FCIS', 'IT, Software Engineering, Information Systems', '#1f6feb'),
('Faculty of Economic Sciences and Management',     'FESM', 'Business, Accounting, Finance, MBA programs',   '#0f766e'),
('Faculty of Law',                                  'FL',   'LLB, LLM International and Environmental Law',  '#7c3aed'),
('Faculty of Environmental Studies',                'FES',  'Environmental Management, Rural Development',   '#b45309'),
('Faculty of Education',                            'FED',  'BEd programs in Mathematics, Economics, CS',    '#be185d');

-- Departments (linked to faculties)
INSERT INTO departments (faculty_id, name, code) VALUES
((SELECT id FROM faculties WHERE code='FCIS'), 'Information Technology',           'IT'),
((SELECT id FROM faculties WHERE code='FCIS'), 'Software Engineering',             'SE'),
((SELECT id FROM faculties WHERE code='FCIS'), 'Information Systems Management',   'ISM'),
((SELECT id FROM faculties WHERE code='FESM'), 'Accounting',                       'ACC'),
((SELECT id FROM faculties WHERE code='FESM'), 'Finance',                          'FIN'),
((SELECT id FROM faculties WHERE code='FESM'), 'Marketing',                        'MKT'),
((SELECT id FROM faculties WHERE code='FESM'), 'Human Resource Management',        'HRM'),
((SELECT id FROM faculties WHERE code='FESM'), 'Economics',                        'ECO'),
((SELECT id FROM faculties WHERE code='FESM'), 'Cooperative Management',           'COOP'),
((SELECT id FROM faculties WHERE code='FL'),   'Law',                              'LAW'),
((SELECT id FROM faculties WHERE code='FES'),  'Environmental Management',         'ENV'),
((SELECT id FROM faculties WHERE code='FES'),  'Rural Development',                'RD'),
((SELECT id FROM faculties WHERE code='FES'),  'Emergency and Disaster Management','EDM'),
((SELECT id FROM faculties WHERE code='FED'),  'Education',                        'EDU');

-- Programs – FCIS
INSERT INTO programs (faculty_id, name, level) VALUES
((SELECT id FROM faculties WHERE code='FCIS'), 'BSc in Information Technology',          'bachelor'),
((SELECT id FROM faculties WHERE code='FCIS'), 'BSc in Software Engineering',            'bachelor'),
((SELECT id FROM faculties WHERE code='FCIS'), 'BSc in Information Systems Management',  'bachelor'),
((SELECT id FROM faculties WHERE code='FCIS'), 'MSc in Information Technology',          'master'),
((SELECT id FROM faculties WHERE code='FCIS'), 'MSc in Management Information Systems',  'master');

-- Programs – FESM
INSERT INTO programs (faculty_id, name, level) VALUES
((SELECT id FROM faculties WHERE code='FESM'), 'BBA in Accounting',                          'bachelor'),
((SELECT id FROM faculties WHERE code='FESM'), 'BBA in Finance',                             'bachelor'),
((SELECT id FROM faculties WHERE code='FESM'), 'BBA in Marketing',                           'bachelor'),
((SELECT id FROM faculties WHERE code='FESM'), 'BBA in Human Resource Management',           'bachelor'),
((SELECT id FROM faculties WHERE code='FESM'), 'BA in Economics',                            'bachelor'),
((SELECT id FROM faculties WHERE code='FESM'), 'BA in Cooperative Management',               'bachelor'),
((SELECT id FROM faculties WHERE code='FESM'), 'MBA – Accounting',                           'master'),
((SELECT id FROM faculties WHERE code='FESM'), 'MBA – Finance',                              'master'),
((SELECT id FROM faculties WHERE code='FESM'), 'MBA – Marketing',                            'master'),
((SELECT id FROM faculties WHERE code='FESM'), 'MBA – Human Resources Management',           'master'),
((SELECT id FROM faculties WHERE code='FESM'), 'MBA – Entrepreneurship',                     'master'),
((SELECT id FROM faculties WHERE code='FESM'), 'MBA – Project Management',                   'master');

-- Programs – FL
INSERT INTO programs (faculty_id, name, level) VALUES
((SELECT id FROM faculties WHERE code='FL'), 'Bachelor of Laws (LLB)',                        'bachelor'),
((SELECT id FROM faculties WHERE code='FL'), 'LLM in International Criminal Law',             'master'),
((SELECT id FROM faculties WHERE code='FL'), 'LLM in Environment and Land Use Law',           'master');

-- Programs – FES
INSERT INTO programs (faculty_id, name, level) VALUES
((SELECT id FROM faculties WHERE code='FES'), 'BSc in Environmental Management and Conservation', 'bachelor'),
((SELECT id FROM faculties WHERE code='FES'), 'BSc in Rural Development',                         'bachelor'),
((SELECT id FROM faculties WHERE code='FES'), 'BSc in Emergency and Disaster Management',         'bachelor'),
((SELECT id FROM faculties WHERE code='FES'), 'MSc in Environmental and Development Studies',     'master');

-- Programs – FED
INSERT INTO programs (faculty_id, name, level) VALUES
((SELECT id FROM faculties WHERE code='FED'), 'BEd in Mathematics and Economics',            'bachelor'),
((SELECT id FROM faculties WHERE code='FED'), 'BEd in Mathematics and Computer Science',     'bachelor'),
((SELECT id FROM faculties WHERE code='FED'), 'BEd in Mathematics and Geography',            'bachelor'),
((SELECT id FROM faculties WHERE code='FED'), 'BEd in Economics and Entrepreneurship',       'bachelor');

-- Users
INSERT INTO users (role_id, department_id, name, email, password, student_number, position) VALUES
((SELECT id FROM roles WHERE slug='system_admin'),  NULL, 'System Administrator',  'admin@isims.local',        '$2y$10$oUQcnQ9ktciScJ5OcXlOnedwcal4Dbk19sZoR3oUiRXuTpM72eK3u', NULL,      'System Admin'),
((SELECT id FROM roles WHERE slug='leader_admin'),  NULL, 'Leader Administrator',  'leader.admin@isims.local', '$2y$10$oUQcnQ9ktciScJ5OcXlOnedwcal4Dbk19sZoR3oUiRXuTpM72eK3u', NULL,      'Leader Admin'),
((SELECT id FROM roles WHERE slug='isr_leader'),    NULL, 'ISR President',         'president@isims.local',    '$2y$10$oUQcnQ9ktciScJ5OcXlOnedwcal4Dbk19sZoR3oUiRXuTpM72eK3u', NULL,      'President'),
((SELECT id FROM roles WHERE slug='student'), (SELECT id FROM departments WHERE code='IT'), 'Demo Student', 'student@isims.local', '$2y$10$oUQcnQ9ktciScJ5OcXlOnedwcal4Dbk19sZoR3oUiRXuTpM72eK3u', 'STU-001', NULL);

-- Sample announcements
INSERT INTO announcements (user_id, department_id, title, body, audience, published_at) VALUES
((SELECT id FROM users WHERE email='president@isims.local'), NULL,
 'Welcome to SIMS – UNILAK Nyanza',
 'Welcome to the Student Information Management System. Use this portal for announcements, events, resources, and SR support.',
 'public', NOW()),
((SELECT id FROM users WHERE email='president@isims.local'),
 (SELECT id FROM departments WHERE code='IT'),
 'IT Faculty Orientation',
 'All IT students are invited to an orientation session this Friday at the Main Hall.',
 'department', NOW());

-- Sample event
INSERT INTO events (user_id, title, description, category, location, starts_at, ends_at) VALUES
((SELECT id FROM users WHERE email='president@isims.local'),
 'Students Welcome Seminar',
 'Meet the SR leaders and learn about campus support services available to students.',
 'seminar', 'Main Auditorium',
 DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 7 DAY), INTERVAL 2 HOUR));

-- Sample calendar
INSERT INTO calendar_events (user_id, title, event_type, starts_on, ends_on, notes) VALUES
((SELECT id FROM users WHERE email='admin@isims.local'),
 'Registration Week', 'registration', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 5 DAY),
 'Registration support available at the student office.');

-- Sample FAQs
INSERT INTO faqs (user_id, category, question, answer) VALUES
((SELECT id FROM users WHERE email='president@isims.local'), 'registration',
 'Where do I get registration help?',
 'Visit the student office or send a message to SR leaders from the portal.'),
((SELECT id FROM users WHERE email='president@isims.local'), 'resources',
 'Where can I find study resources?',
 'Open the Resources section and filter by your department or category.');
