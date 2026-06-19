-- ════════════════════════════════════════════════════════════════════
-- ISIMS CMS Migration — run once against the isims database
-- ════════════════════════════════════════════════════════════════════
USE isims;

-- ── CMS Hero Slides ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cms_hero (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  eyebrow      VARCHAR(180) NOT NULL DEFAULT '',
  title        VARCHAR(255) NOT NULL DEFAULT '',
  subtitle     VARCHAR(255) NOT NULL DEFAULT '',
  description  TEXT NULL,
  btn1_text    VARCHAR(80)  NOT NULL DEFAULT '',
  btn1_url     VARCHAR(255) NOT NULL DEFAULT '',
  btn1_icon    VARCHAR(60)  NOT NULL DEFAULT 'bi-person-plus',
  btn2_text    VARCHAR(80)  NOT NULL DEFAULT '',
  btn2_url     VARCHAR(255) NOT NULL DEFAULT '',
  btn2_icon    VARCHAR(60)  NOT NULL DEFAULT 'bi-box-arrow-in-right',
  bg_image     VARCHAR(255) NOT NULL DEFAULT '',
  is_active    TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── CMS Navigation Menus ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cms_menus (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label        VARCHAR(120) NOT NULL,
  url          VARCHAR(255) NOT NULL DEFAULT '#',
  target       VARCHAR(20)  NOT NULL DEFAULT '_self',
  icon         VARCHAR(60)  NOT NULL DEFAULT '',
  parent_id    INT UNSIGNED NULL,
  sort_order   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  location     ENUM('header','footer') NOT NULL DEFAULT 'header',
  is_active    TINYINT(1)   NOT NULL DEFAULT 1,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_menu_parent FOREIGN KEY (parent_id) REFERENCES cms_menus(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ── CMS Page Sections ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cms_sections (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page         VARCHAR(80)  NOT NULL DEFAULT 'home',
  section_key  VARCHAR(80)  NOT NULL,
  heading      VARCHAR(255) NOT NULL DEFAULT '',
  subheading   VARCHAR(255) NOT NULL DEFAULT '',
  body         TEXT NULL,
  image        VARCHAR(255) NOT NULL DEFAULT '',
  bg_color     VARCHAR(40)  NOT NULL DEFAULT '',
  is_active    TINYINT(1)   NOT NULL DEFAULT 1,
  sort_order   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_page_key (page, section_key)
) ENGINE=InnoDB;

-- ── CMS Media Library ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cms_media (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  file_name    VARCHAR(255) NOT NULL,
  file_path    VARCHAR(255) NOT NULL,
  mime_type    VARCHAR(100) NOT NULL,
  file_size    INT UNSIGNED NOT NULL DEFAULT 0,
  alt_text     VARCHAR(255) NOT NULL DEFAULT '',
  uploaded_by  INT UNSIGNED NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_media_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_media_mime (mime_type)
) ENGINE=InnoDB;

-- ── CMS Footer Content ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cms_footer (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`        VARCHAR(80)  NOT NULL UNIQUE,
  `value`      TEXT NULL,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Extend settings with CMS keys ────────────────────────────────
INSERT IGNORE INTO settings (`key`, `value`) VALUES
('site_tagline',     'Empowering International Students at UNILAK Nyanza'),
('favicon_path',     ''),
('social_facebook',  ''),
('social_twitter',   ''),
('social_instagram', ''),
('social_linkedin',  ''),
('social_youtube',   ''),
('copyright_text',   ''),
('font_heading',     'Inter'),
('font_body',        'Inter'),
('dark_mode',        '0'),
('custom_css',       '');

-- ── Hero seed ─────────────────────────────────────────────────────
INSERT IGNORE INTO cms_hero
  (id, eyebrow, title, subtitle, description, btn1_text, btn1_url, btn1_icon,
   btn2_text, btn2_url, btn2_icon, bg_image, is_active, sort_order)
VALUES (1,
  'University of Lay Adventists of Kigali',
  'UNILAK Nyanza Campus',
  'International Student Information Management System',
  'Your central hub for announcements, campus events, academic resources, and direct communication with ISR leadership.',
  'Student Registration', '/register', 'bi-person-plus',
  'Login to Portal',      '/login',    'bi-box-arrow-in-right',
  '', 1, 0
);

-- ── Page sections seed ────────────────────────────────────────────
INSERT IGNORE INTO cms_sections (page, section_key, heading, subheading, body, sort_order) VALUES
('home','stats_announcements', 'Announcements', '', '', 0),
('home','stats_events',        'Campus Events', '', '', 1),
('home','stats_resources',     'Resources',     '', '', 2),
('home','stats_support',       'ISR Support',   '', '', 3),
('home','about_heading',       'Empowering International Students', 'About UNILAK Nyanza',
 'UNILAK Nyanza Campus is home to a vibrant community of international students from across Africa and beyond. The International Student Representative (ISR) leadership team is here to support your academic journey and campus experience.\n\nThrough the ISIMS portal, students receive timely announcements, access academic resources, view campus events, and communicate directly with ISR leaders.', 4),
('home','announcements_section','Latest Announcements','Stay Informed','Official updates from ISR leadership at UNILAK Nyanza Campus', 5),
('home','events_section',       'Upcoming Events',    'Campus Life',  'Prayer weeks, seminars, sports, and more at UNILAK Nyanza', 6),
('home','features_heading',     'Everything You Need in One Place','What We Offer','', 7),
('home','cta_heading',          'Ready to Get Started?', '',
 'Register as an international student or log in to access your ISIMS portal.', 8);

-- ── CMS footer seed ───────────────────────────────────────────────
INSERT IGNORE INTO cms_footer (`key`, `value`) VALUES
('footer_copyright',   ''),
('footer_tagline',     'International Student Information Management System'),
('footer_show_login',  '1');

-- ── Header nav seed ───────────────────────────────────────────────
INSERT IGNORE INTO cms_menus (id, label, url, icon, location, sort_order) VALUES
(1, 'Login',    '/login',    'bi-box-arrow-in-right', 'header', 0),
(2, 'Register', '/register', 'bi-person-plus',        'header', 1);
