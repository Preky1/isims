<?php

declare(strict_types=1);

final class AuthController extends BaseController
{
    public function showLogin(): void
    {
        $this->view('auth/login', [], 'auth');
    }

    public function login(): void
    {
        $user = (new User())->findByEmail($this->input('email'));
        if (! $user || ! password_verify($this->input('password'), $user['password']) || $user['status'] !== 'active') {
            flash('error', 'Invalid login credentials.');
            redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_slug' => $user['role_slug'],
            'role_name' => $user['role_name'],
            'department_id' => $user['department_id'],
            'department_name' => $user['department_name'],
        ];
        $_SESSION['last_activity'] = time();
        (new User())->updateLastLogin((int) $user['id']);
        redirect('/dashboard');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['departments' => (new User())->departments()], 'auth');
    }

    public function register(): void
    {
        $errors = $this->validate(['name' => 'Name', 'email' => 'Email', 'password' => 'Password']);
        if ($errors || $this->input('password') !== $this->input('password_confirmation')) {
            flash('error', 'Please complete all fields and confirm your password.');
            redirect('/register');
        }

        (new User())->createStudent([
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'department_id' => $this->input('department_id'),
            'phone' => $this->input('phone'),
            'student_number' => $this->input('student_number'),
        ]);

        flash('success', 'Registration successful. You can now log in.');
        redirect('/login');
    }

    public function logout(): void
    {
        session_destroy();
        session_start();
        flash('success', 'You have been logged out.');
        redirect('/login');
    }
}
