<?php

declare(strict_types=1);

final class CmsController extends BaseController
{
    private CmsContent $cms;
    private Setting    $setting;

    public function __construct()
    {
        $this->cms     = new CmsContent();
        $this->setting = new Setting();
    }

    // ── Dashboard (CMS home) ──────────────────────────────────────
    public function index(): void
    {
        $this->view('cms/index', [
            'settings' => $this->setting->all(),
        ]);
    }

    // ── Hero ─────────────────────────────────────────────────────
    public function hero(): void
    {
        $this->view('cms/hero', [
            'slides' => $this->cms->allHero(),
        ]);
    }

    public function saveHero(): void
    {
        $id = (int) $this->input('id');
        $bg = $this->input('bg_image_current', '');

        if (isset($_FILES['bg_image']) && $_FILES['bg_image']['error'] === UPLOAD_ERR_OK) {
            $bg = $this->uploadImage($_FILES['bg_image'], 'hero');
        }

        $data = [
            'eyebrow'     => $this->input('eyebrow'),
            'title'       => $this->input('title'),
            'subtitle'    => $this->input('subtitle'),
            'description' => $this->input('description'),
            'btn1_text'   => $this->input('btn1_text'),
            'btn1_url'    => $this->input('btn1_url'),
            'btn1_icon'   => $this->input('btn1_icon', 'bi-person-plus'),
            'btn2_text'   => $this->input('btn2_text'),
            'btn2_url'    => $this->input('btn2_url'),
            'btn2_icon'   => $this->input('btn2_icon', 'bi-box-arrow-in-right'),
            'bg_image'    => $bg,
            'is_active'   => (int)(bool)$this->input('is_active'),
            'sort_order'  => (int)$this->input('sort_order', 0),
        ];

        if ($id) {
            $this->cms->saveHero($id, $data);
            flash('success', 'Hero slide updated.');
        } else {
            $this->cms->createHero($data);
            flash('success', 'Hero slide created.');
        }
        redirect('/cms/hero');
    }

    public function deleteHero(): void
    {
        $this->cms->deleteHero((int)$this->input('id'));
        flash('success', 'Hero slide deleted.');
        redirect('/cms/hero');
    }

    // ── Page Sections ─────────────────────────────────────────────
    public function sections(): void
    {
        $page = $this->input('page', 'home');
        $this->view('cms/sections', [
            'page'     => $page,
            'sections' => $this->cms->pageSections($page),
        ]);
    }

    public function saveSection(): void
    {
        $page = $this->input('page', 'home');
        $key  = $this->input('section_key');
        $image = $this->input('image_current', '');

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image'], 'sections');
        }

        $this->cms->saveSection($page, $key, [
            'heading'    => $this->input('heading'),
            'subheading' => $this->input('subheading'),
            'body'       => $this->input('body'),
            'image'      => $image,
            'bg_color'   => $this->input('bg_color', ''),
            'is_active'  => (int)(bool)$this->input('is_active'),
            'sort_order' => (int)$this->input('sort_order', 0),
        ]);
        flash('success', 'Section saved.');
        redirect('/cms/sections?page=' . urlencode($page));
    }

    public function deleteSection(): void
    {
        $id   = (int)$this->input('id');
        $page = $this->input('page', 'home');
        $this->cms->deleteSection($id);
        flash('success', 'Section deleted.');
        redirect('/cms/sections?page=' . urlencode($page));
    }

    public function reorderSections(): void
    {
        $ids  = $_POST['ids'] ?? [];
        $page = $this->input('page', 'home');
        if (is_array($ids)) {
            $this->cms->reorderSections($ids);
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    public function reorderMenus(): void
    {
        $ids = $_POST['ids'] ?? [];
        if (is_array($ids)) {
            $this->cms->reorderMenus($ids);
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    public function reorderHero(): void
    {
        $ids = $_POST['ids'] ?? [];
        if (is_array($ids)) {
            $this->cms->reorderHero($ids);
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    // ── Menus ─────────────────────────────────────────────────────
    public function menus(): void
    {
        $this->view('cms/menus', [
            'menus' => $this->cms->allMenuItems(),
        ]);
    }

    public function saveMenu(): void
    {
        $id = (int) $this->input('id');
        $data = [
            'label'      => $this->input('label'),
            'url'        => $this->input('url', '#'),
            'target'     => $this->input('target', '_self'),
            'icon'       => $this->input('icon', ''),
            'location'   => $this->input('location', 'header'),
            'sort_order' => (int)$this->input('sort_order', 0),
            'is_active'  => (int)(bool)$this->input('is_active'),
        ];
        if ($id) {
            $this->cms->saveMenu($id, $data);
            flash('success', 'Menu item updated.');
        } else {
            $this->cms->createMenu($data);
            flash('success', 'Menu item created.');
        }
        redirect('/cms/menus');
    }

    public function deleteMenu(): void
    {
        $this->cms->deleteMenu((int)$this->input('id'));
        flash('success', 'Menu item deleted.');
        redirect('/cms/menus');
    }

    // ── Media Library ─────────────────────────────────────────────
    public function media(): void
    {
        $type = $this->input('type', '');
        $this->view('cms/media', [
            'files'    => $this->cms->allMedia($type),
            'filter'   => $type,
        ]);
    }

    public function uploadMedia(): void
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Upload failed.');
            redirect('/cms/media');
        }
        $allowed = ['image/jpeg','image/png','image/webp','image/svg+xml',
                    'application/pdf','video/mp4'];
        $mime = mime_content_type($_FILES['file']['tmp_name']);
        if (!in_array($mime, $allowed, true)) {
            flash('error', 'File type not allowed.');
            redirect('/cms/media');
        }
        if ($_FILES['file']['size'] > 20 * 1024 * 1024) {
            flash('error', 'File exceeds 20 MB limit.');
            redirect('/cms/media');
        }
        $ext  = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $name = 'media_' . uniqid() . '.' . $ext;
        $dir  = BASE_PATH . '/public/assets/uploads/';
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        move_uploaded_file($_FILES['file']['tmp_name'], $dir . $name);
        $this->cms->addMedia([
            'file_name'   => $_FILES['file']['name'],
            'file_path'   => 'assets/uploads/' . $name,
            'mime_type'   => $mime,
            'file_size'   => $_FILES['file']['size'],
            'alt_text'    => $this->input('alt_text', ''),
            'uploaded_by' => (int)auth_user()['id'],
        ]);
        flash('success', 'File uploaded successfully.');
        redirect('/cms/media');
    }

    public function deleteMedia(): void
    {
        $path = $this->cms->deleteMedia((int)$this->input('id'));
        if ($path) {
            $full = BASE_PATH . '/public/' . $path;
            if (is_file($full)) { unlink($full); }
        }
        flash('success', 'File deleted.');
        redirect('/cms/media');
    }

    // ── Footer ────────────────────────────────────────────────────
    public function footer(): void
    {
        $this->view('cms/footer', [
            'footer'   => $this->cms->allFooter(),
            'settings' => $this->setting->all(),
        ]);
    }

    public function saveFooter(): void
    {
        $this->cms->saveFooterMany([
            'footer_copyright'  => $this->input('footer_copyright'),
            'footer_tagline'    => $this->input('footer_tagline'),
            'footer_show_login' => (int)(bool)$this->input('footer_show_login'),
        ]);
        $this->setting->saveMany([
            'school_email'     => $this->input('school_email'),
            'school_phone'     => $this->input('school_phone'),
            'school_address'   => $this->input('school_address'),
            'social_facebook'  => $this->input('social_facebook'),
            'social_twitter'   => $this->input('social_twitter'),
            'social_instagram' => $this->input('social_instagram'),
            'social_linkedin'  => $this->input('social_linkedin'),
            'social_youtube'   => $this->input('social_youtube'),
            'copyright_text'   => $this->input('copyright_text'),
        ]);
        flash('success', 'Footer settings saved.');
        redirect('/cms/footer');
    }

    // ── Theme ─────────────────────────────────────────────────────
    public function theme(): void
    {
        $this->view('cms/theme', [
            'settings' => $this->setting->all(),
        ]);
    }

    public function saveTheme(): void
    {
        $this->setting->saveMany([
            'primary_color'  => $this->input('primary_color', '#1f6feb'),
            'accent_color'   => $this->input('accent_color',  '#0f766e'),
            'font_heading'   => $this->input('font_heading',  'Inter'),
            'font_body'      => $this->input('font_body',     'Inter'),
            'dark_mode'      => (int)(bool)$this->input('dark_mode'),
            'custom_css'     => $this->input('custom_css'),
        ]);
        flash('success', 'Theme settings saved.');
        redirect('/cms/theme');
    }

    // ── Profile (shared with system_admin) ───────────────────────
    public function profile(): void
    {
        $this->view('cms/profile', [
            'user' => auth_user(),
        ]);
    }

    public function saveProfile(): void
    {
        $uid  = (int)auth_user()['id'];
        $user = new User();
        $name = trim($this->input('name'));
        $email= trim($this->input('email'));
        if (!$name || !$email) {
            flash('error', 'Name and email are required.');
            redirect('/cms/profile');
        }
        $user->updateProfile($uid, $name, $email, $this->input('phone'));
        // refresh session
        $fresh = $user->findById($uid);
        if ($fresh) {
            $_SESSION['user']['name']  = $fresh['name'];
            $_SESSION['user']['email'] = $fresh['email'];
        }
        flash('success', 'Profile updated.');
        redirect('/cms/profile');
    }

    public function changePassword(): void
    {
        $uid     = (int)auth_user()['id'];
        $current = $this->input('current_password');
        $new     = $this->input('new_password');
        $confirm = $this->input('confirm_password');

        if ($new !== $confirm || strlen($new) < 8) {
            flash('error', 'New password must be at least 8 characters and match confirmation.');
            redirect('/cms/profile');
        }
        $user = new User();
        $row  = $user->findById($uid);
        if (!$row || !password_verify($current, $row['password'])) {
            flash('error', 'Current password is incorrect.');
            redirect('/cms/profile');
        }
        $user->updatePassword($uid, password_hash($new, PASSWORD_BCRYPT));
        flash('success', 'Password changed successfully.');
        redirect('/cms/profile');
    }

    // ── Private helpers ───────────────────────────────────────────
    private function uploadImage(array $file, string $subdir): string
    {
        $allowed = ['image/jpeg','image/png','image/webp','image/svg+xml','image/gif'];
        $mime    = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed, true) || $file['size'] > 5 * 1024 * 1024) {
            return '';
        }
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = $subdir . '_' . uniqid() . '.' . $ext;
        $dir  = BASE_PATH . '/public/assets/img/';
        move_uploaded_file($file['tmp_name'], $dir . $name);
        return 'assets/img/' . $name;
    }
}
