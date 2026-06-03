<<<<<<< HEAD
Job Portal System

Project Description

The Job Portal System is a web-based application developed using Laravel and MySQL. It allows employers to post job opportunities and manage applications, while applicants can browse available jobs, apply online, and upload resumes. The system implements authentication, CRUD operations, RESTful APIs, database relationships, middleware protection, and Blade templates to provide a secure and user-friendly job application platform.

Developers

Raven Kristian Abarintos 

Kyerbie Adam Bautista

Carl justen Earl Sison

Mikko catinguel

How the System Works

Employer Features

Register and log in to the system.

Create, update, view, and delete job postings.

View applicants who applied for posted jobs.

Manage job applications.

Applicant Features

Register and log in to the system.

Browse available job listings.

Apply for jobs.

Upload and manage resumes.

View submitted applications.

System Components

Authentication using Laravel sessions and password hashing.

Middleware to protect authorized routes.

CRUD operations for jobs, applications, and resumes.

RESTful API endpoints for job management.

Database relationships:

One User can post many Jobs.

One Job can have many Applications.

One Applicant can submit many Applications.

One Applicant can upload and manage Resumes.

Installation / Setup Instructions

Prerequisites

MySQL

Laravel Framework

Installation Steps

Clone the repository

Navigate to the project directory:

cd job-portal-system

Install PHP dependencies:

composer install

Install frontend dependencies:

npm install

Create environment file:

cp .env.example .env

Generate application key:

php artisan key:generate

Configure database settings in the .env file.

Run database migrations:

php artisan migrate

Start the development server:

php artisan serve

Open your browser and visit:

http://127.0.0.1:8000

REST API Endpoints

Jobs

GET    /api/jobs
POST   /api/jobs
PUT    /api/jobs/{id}
PATCH  /api/jobs/{id}
DELETE /api/jobs/{id}

Applications

GET    /api/applications
POST   /api/applications
DELETE /api/applications/{id}

Technologies Used

PHP

Laravel Framework

MySQL Database

Blade Templates

Bootstrap / CSS

RESTful API

Git & GitHub
https://github.com/nsxr100/job_portal
Railway Deployment

 sample account
Applicant account:
user@example.com
user123
Employer account
employer@example.com
employer123

Backend Deployment (Railway):
jobportal-production-cf19.up.railway.app


Project Requirements Implemented

✔ Authentication (Login, Logout, Sessions, Password Hashing)

✔ CRUD Operations

✔ RESTful API

✔ Controllers, Models, and Migrations

✔ Blade Templates

✔ Middleware Protection

✔ Route Groups

✔ Database Relationships

✔ GitHub Version Control

✔ Railway Deployment
=======
# Job Portal

Job Portal is a Laravel web application for posting jobs, applying to job openings, uploading resumes, managing applicants, and generating application/job reports. It supports applicant, employer, and admin workflows with authentication, protected routes, database relationships, file uploads, REST API endpoints, and online deployment through Railway.

## Developers

- Aziz Herd Team
- Vince

Update this section with the complete names of all group members before final submission.

## Live Demo

Railway deployment:

https://jobportal-production-cf19.up.railway.app

## Main Features

- User registration, login, logout, session handling, and password hashing
- Role-based navigation for applicants and employers
- Job posting CRUD for employers
- Applicant resume upload and job applications
- Employer dashboard for accepting or rejecting applications
- Admin-only protected route
- RESTful API for authentication, jobs, applications, and resumes
- Reports for applications and job postings
- Report exports as PDF, XLSX, CSV, and JSON
- CSV import for job postings
- MySQL database with Laravel migrations, seeders, factories, and Eloquent relationships

## How The System Works

Applicants can register, browse jobs, upload a resume, apply to job postings, and view their applications. Employers can register, post jobs, edit/delete their own job postings, view applicants, open uploaded resumes, and update application statuses. Admin users can access a protected admin route.

The system uses Laravel Eloquent models to connect users, jobs, applications, resumes, and saved jobs. Reports summarize job postings and applications, and the reports page supports downloading data in multiple export formats.

## Database Design

Main tables:

- `users`
- `portal_jobs`
- `applications`
- `resumes`
- `saved_jobs`
- `personal_access_tokens`

Relationships:

- User has many jobs
- User has many applications
- User has one resume
- Job belongs to an employer/user
- Job has many applications
- Application belongs to a job
- Application belongs to an applicant/user
- Users and jobs have a many-to-many saved-jobs relationship

## REST API

Public endpoints:

- `POST /api/register`
- `POST /api/login`
- `GET /api/jobs`
- `GET /api/jobs/{id}`

Protected Sanctum endpoints:

- `GET /api/user`
- `POST /api/logout`
- `POST /api/jobs`
- `PUT /api/jobs/{id}`
- `DELETE /api/jobs/{id}`
- `GET /api/applications`
- `POST /api/applications`
- `GET /api/applications/{id}`
- `PATCH /api/applications/{id}/status`
- `POST /api/resumes`

## Local Setup

1. Clone the repository.
2. Install PHP dependencies:

```bash
composer install
```

3. Copy the environment file and generate an app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database values in `.env`.
5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Link storage for resume uploads:

```bash
php artisan storage:link
```

7. Start the Laravel development server:

```bash
php artisan serve
```

## Railway Deployment Notes

The deployed app uses Railway environment variables for MySQL and runs through Docker with PHP-FPM, Nginx, and Supervisor. The startup script clears caches, links public storage, prepares resume storage, runs migrations, and starts the web server on Railway's assigned port.

## Version Control

The project should be pushed to GitHub with regular commits from all group members. Add all group members as repository collaborators before final submission.
>>>>>>> a8e233a (Add reports CRUD import export and rubric fixes)
