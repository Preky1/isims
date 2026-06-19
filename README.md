# ISIMS - International Student Information Management System

ISIMS is a PHP 8 MVC web system for international students and ISR leaders. It supports announcements, calendars and events, resources, messaging, FAQs, role-based dashboards, notifications, reports, and secure authentication.

## Roles

- Student: self-registers, reads announcements/events/resources/FAQs, sends messages, receives notifications.
- ISR Leader: manages announcements, messages, events, FAQs, and resources when authorized.
- Leader Admin: creates ISR leaders and manages users.
- System Admin: full access, reporting, settings, and system oversight.

## Setup With XAMPP

1. Copy this `isims` folder into `C:\xampp\htdocs\isims`, or serve the `public` directory directly.
2. Create a MySQL database named `isims`.
3. Import `database/schema.sql`.
4. Copy `.env.example` to `.env` and update database credentials if needed.
5. Visit `http://localhost/isims/public`.

Default seeded users use password `password123`:

- `admin@isims.local` - System Admin
- `leader.admin@isims.local` - Leader Admin
- `president@isims.local` - ISR Leader
- `student@isims.local` - Student

## Structure

```text
app/
  controllers/   Request handlers
  helpers/       Global helper functions and services
  middleware/    Auth, role, and CSRF protection
  models/        PDO-backed data models
  views/         Bootstrap PHP templates
config/          Environment, database, and app bootstrap
database/        MySQL schema and seed data
public/          Web root, assets, uploads, front controller
routes/          Web route declarations
storage/         Logs and generated runtime files
```
