<?php

declare(strict_types=1);

final class AdminController extends BaseController
{
    public function users(): void
    {
        $model = new User();
        $this->view('admin/users', [
            'users'       => $model->all(),
            'departments' => $model->departments(),
        ]);
    }

    public function storeUser(): void
    {
        $errors = $this->validate(['name' => 'Name', 'email' => 'Email', 'password' => 'Password']);
        if ($errors) {
            flash('error', 'Name, email, and password are required.');
            redirect('/admin/users');
        }

        $role = has_role('system_admin') ? $this->input('role_slug', 'isr_leader') : 'isr_leader';
        if (! in_array($role, ['isr_leader', 'leader_admin'], true)) {
            $role = 'isr_leader';
        }

        (new User())->createStaff([
            'name'          => $this->input('name'),
            'email'         => $this->input('email'),
            'password'      => $this->input('password'),
            'department_id' => $this->input('department_id'),
            'phone'         => $this->input('phone'),
            'position'      => $this->input('position'),
        ], $role);

        flash('success', 'User account created.');
        redirect('/admin/users');
    }

    public function toggleStatus(): void
    {
        (new User())->toggleStatus((int) $this->input('id'));
        flash('success', 'User status updated.');
        redirect('/admin/users');
    }

    public function deleteUser(): void
    {
        $id = (int) $this->input('id');
        // Prevent self-deletion
        if ($id === (int) auth_user()['id']) {
            flash('error', 'You cannot delete your own account.');
            redirect('/admin/users');
        }
        (new User())->delete($id);
        flash('success', 'User deleted.');
        redirect('/admin/users');
    }

    public function reports(): void
    {
        $model = new User();
        $this->view('admin/reports', [
            'stats'       => $model->stats(),
            'deptStats'   => $model->departmentStats(),
        ]);
    }
}
