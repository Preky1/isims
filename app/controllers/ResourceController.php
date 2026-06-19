<?php

declare(strict_types=1);

final class ResourceController extends BaseController
{
    public function index(): void
    {
        $this->view('resources/index', [
            'resources' => (new ResourceItem())->search(['q' => $_GET['q'] ?? '', 'department_id' => $_GET['department_id'] ?? '']),
            'departments' => (new User())->departments(),
        ]);
    }

    public function store(): void
    {
        if (! isset($_FILES['resource']) || $_FILES['resource']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please upload a valid file.');
            redirect('/resources');
        }

        $allowed = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $mime = mime_content_type($_FILES['resource']['tmp_name']);
        if (! in_array($mime, $allowed, true) || $_FILES['resource']['size'] > 10 * 1024 * 1024) {
            flash('error', 'Only safe document files up to 10MB are allowed.');
            redirect('/resources');
        }

        $safeName = bin2hex(random_bytes(12)) . '-' . preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['resource']['name']);
        $target = BASE_PATH . '/public/uploads/resources/' . $safeName;
        move_uploaded_file($_FILES['resource']['tmp_name'], $target);

        (new ResourceItem())->create([
            'user_id' => auth_user()['id'],
            'department_id' => $this->input('department_id'),
            'title' => $this->input('title'),
            'description' => $this->input('description'),
            'category' => $this->input('category', 'other'),
            'file_name' => $_FILES['resource']['name'],
            'file_path' => 'uploads/resources/' . $safeName,
            'mime_type' => $mime,
            'file_size' => $_FILES['resource']['size'],
        ]);
        flash('success', 'Resource uploaded.');
        redirect('/resources');
    }
}
