
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
