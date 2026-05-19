# 📝 To-Do List Application

## Live demo: https://symfonytodolist.alwaysdata.net
  
## Description

This project is a web application developed with Symfony and PHP.

It allows users to manage tasks, organize them with drag & drop, receive email notifications, and manage their personal profile.

---

## Features

- User authentication (register / login)
- Create, edit and delete tasks
- Drag & drop task organization
- Email notifications for overdue tasks
- User profile management
- Upload profile picture
- Delete account and all associated data

---

## Prerequisites

- PHP >= 8.2
- Composer
- Symfony CLI
- PostgreSQL 16 (local or Docker)
- Git

---

## Installation

### 1. Clone the project
git clone https://github.com/malygina777/SymfonyToDo
cd YOUR_PROJECT_FOLDER
composer install

---

### 2. Environment variables

Create a .env.local file at the root of the project:
APP_ENV=dev
APP_SECRET=changeme

DATABASE_URL="postgresql://app:ChangeMe!@127.0.0.1:5432/symfony_todo?charset=utf8"

# Messenger (optional)
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0

# Mailer (optional, for local testing)
MAILER_DSN=smtp://127.0.0.1:1025

⚠️ Sensitive data must not be committed.

---

### 3. Start database (Docker)
docker compose up -d

Wait until PostgreSQL is available on:
127.0.0.1:5432

---

### 4. Create database
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n

---

### 5. Start server
symfony server:start -d

Application доступно по адресу:
http://127.0.0.1:8000

---

## Logs & Stop

Logs:
var/log/

Stop server:
symfony server:stop

Stop database:
docker compose down

---




