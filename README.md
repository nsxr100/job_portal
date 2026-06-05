# Job Portal System

The Job Portal System is a Laravel-based web application created to make job posting and job application management easier for applicants, employers, and administrators. Applicants can browse jobs, submit applications, and upload resumes. Employers can post jobs and review applicants. Administrators can manage users, jobs, applications, resumes, reports, and system records.

This project was developed for educational purposes and demonstrates authentication, CRUD operations, database relationships, middleware protection, RESTful API endpoints, report generation, import/export features, file uploads, and Railway deployment.

## Developers

* Raven Kristian Abarintos
* Kyerbie Adam Bautista
* Carl Justen Earl Sison
* Mikko Catinguel

## Modules

* Authentication and Session Management
* Applicant Registration and Login
* Employer Registration and Login
* Admin Dashboard
* Job Posting Management
* Job Application Management
* Resume Upload and Management
* Reports, Import, and Export
* RESTful API
* Railway Deployment

## System Roles

### Applicant

Applicants can register, log in, browse available jobs, apply for jobs, upload resumes, and view their submitted applications.

### Employer

Employers can register, log in, create job postings, edit their own job postings, delete their own job postings, and manage applications submitted to their jobs.

### Admin

Admins have access to the admin dashboard. They can manage all user accounts, job postings, resumes, and applications. Admin users are roleless in the normal applicant/employer flow because they are system administrators.

## Main Features

* Login and logout
* Laravel session handling
* Password hashing
* Applicant, employer, and admin access
* Create, read, update, and delete job postings
* Apply to jobs
* Resume upload and viewing
* Admin control over accounts, jobs, resumes, and applications
* Report generation
* CSV job import
* Export reports as PDF, XLSX, CSV, and JSON
* RESTful API with GET, POST, PUT/PATCH, and DELETE requests
* Middleware-protected routes
* Laravel migrations, models, controllers, and Blade templates
* Railway deployment with MySQL database

## Database Design

The system uses MySQL and Laravel Eloquent ORM.

Main tables:

* `users`
* `jobs`
* `portal_jobs`
* `applications`
* `resumes`
* `saved_jobs`
* `personal_access_tokens`

Main relationships:

* One user can post many jobs.
* One job can have many applications.
* One applicant can submit many applications.
* One applicant can upload and manage resumes.
* One application belongs to one applicant and one job.

Database design documents are available in:

* `docs/database-design.md`
* `docs/erd-diagram.mmd`

## Installation

You can install the project locally by cloning the repository and running the Laravel setup commands.

### GitHub Clone

```bash
git clone https://github.com/nsxr100/job_portal.git
cd job_portal
```

### Install Dependencies

```bash
composer install
npm install
```

### Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Configure the database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Start the local development server:

```bash
php artisan serve
```

Open the system in your browser:

```text
http://127.0.0.1:8000
```

## Requirements

* PHP 8.4 or higher
* Composer
* Node.js and npm
* MySQL or MariaDB
* Laravel 11
* Laravel Sanctum
* XAMPP, Laragon, or another local PHP/MySQL environment for local testing

## Demo Credentials

Use these sample accounts for testing the deployed or local system when they exist in the database.

| Role | Email | Password |
| --- | --- | --- |
| Applicant | `user@example.com` | `user123` |
| Employer | `employer@example.com` | `employer123` |

Admin credentials should be provided privately during presentation or created directly through the database/admin setup. Do not commit real production admin passwords to GitHub.

## Web Routes

| Feature | URL |
| --- | --- |
| Home / Browse Jobs | `/` |
| Login | `/login` |
| Register | `/register` |
| Employer Dashboard | `/employer/dashboard` |
| Applicant Applications | `/applications` |
| Resume Upload | `/resume` |
| Reports | `/reports` |
| Admin Dashboard | `/admin/dashboard` |

## RESTful API Endpoints

### Authentication

| Method | Endpoint | Description |
| --- | --- | --- |
| POST | `/api/register` | Register a user |
| POST | `/api/login` | Log in and receive API token |
| GET | `/api/user` | Get current authenticated user |
| POST | `/api/logout` | Revoke API token |

### Jobs

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/jobs` | List jobs |
| GET | `/api/jobs/{id}` | View one job |
| POST | `/api/jobs` | Create job |
| PUT | `/api/jobs/{id}` | Update job |
| DELETE | `/api/jobs/{id}` | Delete job |

### Applications and Resumes

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/applications` | List applications |
| POST | `/api/applications` | Submit application |
| GET | `/api/applications/{id}` | View application |
| PATCH | `/api/applications/{id}/status` | Update application status |
| POST | `/api/resumes` | Upload resume |

For protected API routes, send the token using:

```text
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

## Railway Deployment

The project is deployed using Railway.

Deployment link:

```text
https://jobportal-production-cf19.up.railway.app
```

The deployed system uses a Railway MySQL service. The Laravel application service gets its database settings from Railway variable references:

```env
DB_CONNECTION=mysql
DB_HOST=${{MySQL-BQTX.MYSQLHOST}}
DB_PORT=${{MySQL-BQTX.MYSQLPORT}}
DB_DATABASE=${{MySQL-BQTX.MYSQLDATABASE}}
DB_USERNAME=${{MySQL-BQTX.MYSQLUSER}}
DB_PASSWORD=${{MySQL-BQTX.MYSQLPASSWORD}}
```

The actual values are generated by Railway inside the MySQL service Variables tab. They should not be manually typed into GitHub.

## Docker

Docker is used for Railway deployment. The Dockerfile builds the Laravel app with PHP, Composer dependencies, Nginx, PHP-FPM, and required PHP extensions. The startup script prepares Laravel caches, creates the public storage link, runs migrations, and starts the web server.

Main deployment files:

* `Dockerfile`
* `docker/start.sh`
* `nixpacks.toml`

## Reports and Import/Export

The reports module supports:

* PDF export
* XLSX export
* CSV export
* JSON export
* CSV import for job postings

The job import file must contain the expected job posting columns. If the uploaded CSV does not match the required structure, the system rejects it and shows an error message instead of importing incorrect data.

## What's Included?

* Laravel 11 - PHP web framework used for routing, controllers, models, middleware, and Blade views.
* Laravel Sanctum - Token authentication for the RESTful API.
* MySQL - Database used for users, jobs, applications, resumes, and tokens.
* Blade Templates - Server-side views used for the web interface.
* Eloquent ORM - Laravel database model system used for relationships.
* Vite and Tailwind CSS - Frontend asset tooling and styling support.
* Docker - Container setup for Railway deployment.
* Railway - Online hosting platform used for the deployed system and database.
* Postman - Used for testing API requests.

## Project Requirements Implemented

* Authentication system
* Login and logout
* Session handling
* Password hashing
* CRUD operations
* Laravel migrations
* Laravel Eloquent ORM
* Database relationships
* RESTful API
* Master layout and Blade components
* Middleware protection
* Admin dashboard
* Auto-generated reports
* Import and export features
* File uploads
* GitHub repository
* Railway deployment

## License

This project is for educational use.

