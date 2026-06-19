<?php

declare(strict_types=1);

$router = new Router();

$auth    = [AuthMiddleware::class];
$csrf    = [CsrfMiddleware::class];
$admin   = [AuthMiddleware::class, RoleMiddleware::require('leader_admin', 'system_admin')];
$leaders = [AuthMiddleware::class, RoleMiddleware::require('isr_leader', 'leader_admin', 'system_admin')];
$sysadmin = [AuthMiddleware::class, RoleMiddleware::require('system_admin')];

// ── Public ──────────────────────────────────────────────────────────────────
$router->get('/',          [HomeController::class, 'index']);
$router->get('/home',      [HomeController::class, 'index']);

// ── Auth ────────────────────────────────────────────────────────────────────
$router->get('/login',     [AuthController::class, 'showLogin']);
$router->post('/login',    [AuthController::class, 'login'], $csrf);
$router->get('/register',  [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register'], $csrf);
$router->get('/logout',    [AuthController::class, 'logout'], $auth);

// ── Dashboard ────────────────────────────────────────────────────────────────
$router->get('/dashboard', [DashboardController::class, 'index'], $auth);

// ── Announcements ────────────────────────────────────────────────────────────
$router->get('/announcements',         [AnnouncementController::class, 'index'],  $auth);
$router->post('/announcements',        [AnnouncementController::class, 'store'],   array_merge($leaders, $csrf));
$router->post('/announcements/archive',[AnnouncementController::class, 'archive'], array_merge($leaders, $csrf));
$router->post('/announcements/delete', [AnnouncementController::class, 'delete'],  array_merge($leaders, $csrf));

// ── Messages ─────────────────────────────────────────────────────────────────
$router->get('/messages',        [MessageController::class, 'index'], $auth);
$router->post('/messages',       [MessageController::class, 'store'], array_merge($auth, $csrf));
$router->post('/messages/reply', [MessageController::class, 'reply'], array_merge($auth, $csrf));

// ── Resources ────────────────────────────────────────────────────────────────
$router->get('/resources',  [ResourceController::class, 'index'], $auth);
$router->post('/resources', [ResourceController::class, 'store'], array_merge($leaders, $csrf));

// ── Events ───────────────────────────────────────────────────────────────────
$router->get('/events',         [EventController::class, 'index'],  $auth);
$router->post('/events',        [EventController::class, 'store'],  array_merge($leaders, $csrf));
$router->post('/events/delete', [EventController::class, 'delete'], array_merge($leaders, $csrf));

// ── FAQs ─────────────────────────────────────────────────────────────────────
$router->get('/faqs',         [FaqController::class, 'index'],  $auth);
$router->post('/faqs',        [FaqController::class, 'store'],  array_merge($leaders, $csrf));
$router->post('/faqs/update', [FaqController::class, 'update'], array_merge($leaders, $csrf));
$router->post('/faqs/delete', [FaqController::class, 'delete'], array_merge($leaders, $csrf));

// ── Notifications ────────────────────────────────────────────────────────────
$router->get('/notifications',          [NotificationController::class, 'index'],      $auth);
$router->post('/notifications/read',    [NotificationController::class, 'markRead'],   array_merge($auth, $csrf));
$router->post('/notifications/read-all',[NotificationController::class, 'markAllRead'],array_merge($auth, $csrf));

// ── Admin: Users ─────────────────────────────────────────────────────────────
$router->get('/admin/users',               [AdminController::class, 'users'],        $admin);
$router->post('/admin/users',              [AdminController::class, 'storeUser'],    array_merge($admin, $csrf));
$router->post('/admin/users/toggle',       [AdminController::class, 'toggleStatus'], array_merge($admin, $csrf));
$router->post('/admin/users/delete',       [AdminController::class, 'deleteUser'],   array_merge($admin, $csrf));

// ── Admin: Reports ────────────────────────────────────────────────────────────
$router->get('/admin/reports', [AdminController::class, 'reports'], $sysadmin);

// ── Admin: Settings ──────────────────────────────────────────────────────────
$router->get('/admin/settings',            [AdminSettingsController::class, 'settings'],        $sysadmin);
$router->post('/admin/settings',           [AdminSettingsController::class, 'saveSettings'],    array_merge($sysadmin, $csrf));

// ── Admin: Faculties ─────────────────────────────────────────────────────────
$router->get('/admin/faculties',           [AdminSettingsController::class, 'faculties'],       $sysadmin);
$router->post('/admin/faculties',          [AdminSettingsController::class, 'storeFaculty'],    array_merge($sysadmin, $csrf));
$router->post('/admin/faculties/update',   [AdminSettingsController::class, 'updateFaculty'],   array_merge($sysadmin, $csrf));
$router->post('/admin/faculties/delete',   [AdminSettingsController::class, 'deleteFaculty'],   array_merge($sysadmin, $csrf));

// ── Admin: Departments ───────────────────────────────────────────────────────
$router->post('/admin/departments',        [AdminSettingsController::class, 'addDepartment'],    array_merge($sysadmin, $csrf));
$router->post('/admin/departments/delete', [AdminSettingsController::class, 'deleteDepartment'], array_merge($sysadmin, $csrf));

// ── Admin: Programs ──────────────────────────────────────────────────────────
$router->post('/admin/programs',           [AdminSettingsController::class, 'addProgram'],      array_merge($sysadmin, $csrf));
$router->post('/admin/programs/delete',    [AdminSettingsController::class, 'deleteProgram'],   array_merge($sysadmin, $csrf));

// ── CMS ───────────────────────────────────────────────────────────────────────
$router->get('/cms',               [CmsController::class, 'index'],          $sysadmin);
$router->get('/cms/hero',          [CmsController::class, 'hero'],           $sysadmin);
$router->post('/cms/hero',         [CmsController::class, 'saveHero'],       array_merge($sysadmin, $csrf));
$router->post('/cms/hero/delete',  [CmsController::class, 'deleteHero'],     array_merge($sysadmin, $csrf));
$router->get('/cms/sections',      [CmsController::class, 'sections'],       $sysadmin);
$router->post('/cms/sections',     [CmsController::class, 'saveSection'],    array_merge($sysadmin, $csrf));
$router->post('/cms/sections/delete', [CmsController::class, 'deleteSection'], array_merge($sysadmin, $csrf));
$router->post('/cms/sections/reorder',[CmsController::class, 'reorderSections'],array_merge($sysadmin, $csrf));
$router->get('/cms/menus',         [CmsController::class, 'menus'],          $sysadmin);
$router->post('/cms/menus',        [CmsController::class, 'saveMenu'],       array_merge($sysadmin, $csrf));
$router->post('/cms/menus/delete', [CmsController::class, 'deleteMenu'],     array_merge($sysadmin, $csrf));
$router->post('/cms/menus/reorder',[CmsController::class, 'reorderMenus'],   array_merge($sysadmin, $csrf));
$router->get('/cms/media',         [CmsController::class, 'media'],          $sysadmin);
$router->post('/cms/media',        [CmsController::class, 'uploadMedia'],    array_merge($sysadmin, $csrf));
$router->post('/cms/media/delete', [CmsController::class, 'deleteMedia'],    array_merge($sysadmin, $csrf));
$router->get('/cms/footer',        [CmsController::class, 'footer'],         $sysadmin);
$router->post('/cms/footer',       [CmsController::class, 'saveFooter'],     array_merge($sysadmin, $csrf));
$router->get('/cms/theme',         [CmsController::class, 'theme'],          $sysadmin);
$router->post('/cms/theme',        [CmsController::class, 'saveTheme'],      array_merge($sysadmin, $csrf));
$router->get('/cms/profile',       [CmsController::class, 'profile'],        $sysadmin);
$router->post('/cms/profile',      [CmsController::class, 'saveProfile'],    array_merge($sysadmin, $csrf));
$router->post('/cms/password',     [CmsController::class, 'changePassword'], array_merge($sysadmin, $csrf));
$router->post('/cms/hero/reorder', [CmsController::class, 'reorderHero'],    array_merge($sysadmin, $csrf));

return $router;
