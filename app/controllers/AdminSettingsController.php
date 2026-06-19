<?php

declare(strict_types=1);

final class AdminSettingsController extends BaseController
{
    // ── System Settings ────────────────────────────────────────────
    public function settings(): void
    {
        $this->view('admin/settings', [
            'settings' => (new Setting())->all(),
        ]);
    }

    public function saveSettings(): void
    {
        $model = new Setting();

        $fields = ['app_name','school_name','campus_name','school_country','site_tagline',
                   'school_email','school_phone','school_address','primary_color','accent_color'];
        $data = [];
        foreach ($fields as $f) {
            $data[$f] = $this->input($f);
        }

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg','image/png','image/webp','image/svg+xml'];
            $mime = mime_content_type($_FILES['logo']['tmp_name']);
            if (in_array($mime, $allowed, true) && $_FILES['logo']['size'] <= 2 * 1024 * 1024) {
                $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $name = 'logo.' . strtolower($ext);
                move_uploaded_file($_FILES['logo']['tmp_name'], BASE_PATH . '/public/assets/img/' . $name);
                $data['logo_path'] = 'assets/img/' . $name;
            }
        }

        // Handle favicon upload
        if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/x-icon','image/vnd.microsoft.icon','image/png','image/svg+xml'];
            $mime = mime_content_type($_FILES['favicon']['tmp_name']);
            if ($_FILES['favicon']['size'] <= 512 * 1024) {
                $ext = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION)) ?: 'ico';
                $name = 'favicon.' . $ext;
                move_uploaded_file($_FILES['favicon']['tmp_name'], BASE_PATH . '/public/assets/img/' . $name);
                $data['favicon_path'] = 'assets/img/' . $name;
            }
        }

        // Handle campus photo upload
        if (isset($_FILES['campus_photo']) && $_FILES['campus_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg','image/png','image/webp'];
            $mime = mime_content_type($_FILES['campus_photo']['tmp_name']);
            if (in_array($mime, $allowed, true) && $_FILES['campus_photo']['size'] <= 5 * 1024 * 1024) {
                $ext = pathinfo($_FILES['campus_photo']['name'], PATHINFO_EXTENSION);
                $name = 'campus.' . strtolower($ext);
                move_uploaded_file($_FILES['campus_photo']['tmp_name'], BASE_PATH . '/public/assets/img/' . $name);
                $data['campus_photo'] = 'assets/img/' . $name;
            }
        }

        $model->saveMany($data);
        flash('success', 'Settings saved successfully.');
        redirect('/admin/settings');
    }

    // ── Faculties ─────────────────────────────────────────────────
    public function faculties(): void
    {
        $this->view('admin/faculties', [
            'faculties' => (new Faculty())->all(),
        ]);
    }

    public function storeFaculty(): void
    {
        $errors = $this->validate(['name' => 'Faculty name', 'code' => 'Code']);
        if ($errors) { flash('error', 'Name and code are required.'); redirect('/admin/faculties'); }
        (new Faculty())->create([
            'name'        => $this->input('name'),
            'code'        => $this->input('code'),
            'description' => $this->input('description'),
            'color'       => $this->input('color', '#1f6feb'),
        ]);
        flash('success', 'Faculty created.');
        redirect('/admin/faculties');
    }

    public function updateFaculty(): void
    {
        $id = (int) $this->input('id');
        (new Faculty())->update($id, [
            'name'        => $this->input('name'),
            'code'        => $this->input('code'),
            'description' => $this->input('description'),
            'color'       => $this->input('color', '#1f6feb'),
            'is_active'   => (int) (bool) $this->input('is_active'),
        ]);
        flash('success', 'Faculty updated.');
        redirect('/admin/faculties');
    }

    public function deleteFaculty(): void
    {
        (new Faculty())->delete((int) $this->input('id'));
        flash('success', 'Faculty deleted.');
        redirect('/admin/faculties');
    }

    // ── Departments ───────────────────────────────────────────────
    public function addDepartment(): void
    {
        $errors = $this->validate(['name' => 'Department name', 'code' => 'Code']);
        if ($errors) { flash('error', 'Name and code are required.'); redirect('/admin/faculties'); }
        (new Faculty())->addDepartment([
            'faculty_id' => (int) $this->input('faculty_id'),
            'name'       => $this->input('name'),
            'code'       => $this->input('code'),
        ]);
        flash('success', 'Department added.');
        redirect('/admin/faculties');
    }

    public function deleteDepartment(): void
    {
        (new Faculty())->deleteDepartment((int) $this->input('id'));
        flash('success', 'Department deleted.');
        redirect('/admin/faculties');
    }

    // ── Programs ──────────────────────────────────────────────────
    public function addProgram(): void
    {
        $errors = $this->validate(['name' => 'Program name', 'level' => 'Level']);
        if ($errors) { flash('error', 'Program name and level are required.'); redirect('/admin/faculties'); }
        (new Faculty())->addProgram([
            'faculty_id'    => (int) $this->input('faculty_id'),
            'department_id' => $this->input('department_id'),
            'name'          => $this->input('name'),
            'level'         => $this->input('level', 'bachelor'),
        ]);
        flash('success', 'Program added.');
        redirect('/admin/faculties');
    }

    public function deleteProgram(): void
    {
        (new Faculty())->deleteProgram((int) $this->input('id'));
        flash('success', 'Program deleted.');
        redirect('/admin/faculties');
    }
}
