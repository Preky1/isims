<?php
/**
 * Runs on container start via docker-entrypoint.sh
 * Creates the database + all tables if they don't exist.
 * Safe to re-run (uses CREATE TABLE IF NOT EXISTS + INSERT IGNORE).
 */
declare(strict_types=1);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_DATABASE') ?: 'isims';
$user = getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: '';

// Connect without DB selected to create it if needed
$pdo = new PDO(
    "mysql:host=$host;port=$port;charset=utf8mb4",
    $user, $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
$pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `$name`");

// Check if already set up
$tables = $pdo->query("SHOW TABLES LIKE 'users'")->fetchColumn();
if ($tables) {
    echo "[setup] Tables already exist — skipping schema.\n";
} else {
    echo "[setup] Running schema.sql...\n";
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    // Strip CREATE DATABASE / USE / DROP / FK_CHECKS lines — handled above
    $schema = preg_replace('/^(CREATE DATABASE|USE\s|SET FOREIGN_KEY_CHECKS|DROP TABLE).*?;\s*/im', '', $schema);
    $schema = "SET FOREIGN_KEY_CHECKS=0;\n" . $schema . "\nSET FOREIGN_KEY_CHECKS=1;";
    foreach (array_filter(array_map('trim', explode(';', $schema))) as $stmt) {
        try { $pdo->exec($stmt); } catch (Exception $e) {
            echo "[setup] Warning: " . $e->getMessage() . "\n";
        }
    }
    echo "[setup] Schema done.\n";
}

// CMS tables
$cmsTables = [
    'cms_hero' => "CREATE TABLE IF NOT EXISTS cms_hero (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        eyebrow VARCHAR(180) NOT NULL DEFAULT '',
        title VARCHAR(255) NOT NULL DEFAULT '',
        subtitle VARCHAR(255) NOT NULL DEFAULT '',
        description TEXT NULL,
        btn1_text VARCHAR(80) NOT NULL DEFAULT '',
        btn1_url VARCHAR(255) NOT NULL DEFAULT '',
        btn1_icon VARCHAR(60) NOT NULL DEFAULT 'bi-person-plus',
        btn2_text VARCHAR(80) NOT NULL DEFAULT '',
        btn2_url VARCHAR(255) NOT NULL DEFAULT '',
        btn2_icon VARCHAR(60) NOT NULL DEFAULT 'bi-box-arrow-in-right',
        bg_image VARCHAR(255) NOT NULL DEFAULT '',
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB",
    'cms_menus' => "CREATE TABLE IF NOT EXISTS cms_menus (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        label VARCHAR(120) NOT NULL,
        url VARCHAR(255) NOT NULL DEFAULT '#',
        target VARCHAR(20) NOT NULL DEFAULT '_self',
        icon VARCHAR(60) NOT NULL DEFAULT '',
        parent_id INT UNSIGNED NULL,
        sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
        location ENUM('header','footer') NOT NULL DEFAULT 'header',
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_menu_parent FOREIGN KEY (parent_id) REFERENCES cms_menus(id) ON DELETE SET NULL
    ) ENGINE=InnoDB",
    'cms_sections' => "CREATE TABLE IF NOT EXISTS cms_sections (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        page VARCHAR(80) NOT NULL DEFAULT 'home',
        section_key VARCHAR(80) NOT NULL,
        heading VARCHAR(255) NOT NULL DEFAULT '',
        subheading VARCHAR(255) NOT NULL DEFAULT '',
        body TEXT NULL,
        image VARCHAR(255) NOT NULL DEFAULT '',
        bg_color VARCHAR(40) NOT NULL DEFAULT '',
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uq_page_key (page, section_key)
    ) ENGINE=InnoDB",
    'cms_media' => "CREATE TABLE IF NOT EXISTS cms_media (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        file_name VARCHAR(255) NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        mime_type VARCHAR(100) NOT NULL,
        file_size INT UNSIGNED NOT NULL DEFAULT 0,
        alt_text VARCHAR(255) NOT NULL DEFAULT '',
        uploaded_by INT UNSIGNED NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_media_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_media_mime (mime_type)
    ) ENGINE=InnoDB",
    'cms_footer' => "CREATE TABLE IF NOT EXISTS cms_footer (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        \`key\` VARCHAR(80) NOT NULL UNIQUE,
        \`value\` TEXT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB",
];

foreach ($cmsTables as $table => $sql) {
    try {
        $pdo->exec($sql);
        echo "[setup] CMS table: $table OK\n";
    } catch (Exception $e) {
        echo "[setup] $table: " . $e->getMessage() . "\n";
    }
}

// Seed CMS settings keys
$settingSeeds = [
    'site_tagline'     => 'Empowering International Students at UNILAK Nyanza',
    'favicon_path'     => '',
    'social_facebook'  => '',
    'social_twitter'   => '',
    'social_instagram' => '',
    'social_linkedin'  => '',
    'social_youtube'   => '',
    'copyright_text'   => '',
    'font_heading'     => 'Inter',
    'font_body'        => 'Inter',
    'dark_mode'        => '0',
    'custom_css'       => '',
];
foreach ($settingSeeds as $k => $v) {
    $pdo->prepare("INSERT IGNORE INTO settings (`key`,`value`) VALUES (?,?)")->execute([$k, $v]);
}

// Seed CMS hero
$pdo->exec("INSERT IGNORE INTO cms_hero
    (id,eyebrow,title,subtitle,description,btn1_text,btn1_url,btn1_icon,btn2_text,btn2_url,btn2_icon,bg_image,is_active,sort_order)
    VALUES (1,
    'University of Lay Adventists of Kigali','UNILAK Nyanza Campus',
    'Student Information Management System',
    'Your central hub for announcements, campus events, academic resources, and SR leadership.',
    'Student Registration','/register','bi-person-plus',
    'Login to Portal','/login','bi-box-arrow-in-right','',1,0)");

// Seed page sections
$sections = [
    ['home','stats_announcements','Announcements','','',0],
    ['home','stats_events','Campus Events','','',1],
    ['home','stats_resources','Resources','','',2],
    ['home','stats_support','SR Support','','',3],
    ['home','about_heading','Empowering Students','About UNILAK Nyanza',
     "UNILAK Nyanza Campus is home to a vibrant community of students.\n\nThrough the ISIMS portal, students receive timely announcements and communicate with SR leaders.",4],
    ['home','announcements_section','Latest Announcements','Stay Informed','Official updates from SR leadership',5],
    ['home','events_section','Upcoming Events','Campus Life','Prayer weeks, seminars, sports, and more',6],
    ['home','features_heading','Everything You Need in One Place','What We Offer','',7],
    ['home','cta_heading','Ready to Get Started?','','Register as a student or log in.',8],
];
$sStmt = $pdo->prepare("INSERT IGNORE INTO cms_sections (page,section_key,heading,subheading,body,sort_order) VALUES (?,?,?,?,?,?)");
foreach ($sections as $s) { $sStmt->execute($s); }

// Seed footer
$fStmt = $pdo->prepare("INSERT IGNORE INTO cms_footer (`key`,`value`) VALUES (?,?)");
foreach (['footer_copyright'=>'','footer_tagline'=>'Student Information Management System','footer_show_login'=>'1'] as $k=>$v) {
    $fStmt->execute([$k,$v]);
}

// Seed nav menus
$pdo->exec("INSERT IGNORE INTO cms_menus (id,label,url,icon,location,sort_order) VALUES (1,'Login','/login','bi-box-arrow-in-right','header',0)");
$pdo->exec("INSERT IGNORE INTO cms_menus (id,label,url,icon,location,sort_order) VALUES (2,'Register','/register','bi-person-plus','header',1)");

// Uploads dir
@mkdir('/var/www/html/public/assets/uploads', 0755, true);

echo "[setup] All done.\n";
