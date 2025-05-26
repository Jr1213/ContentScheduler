````md
# Laravel Application Setup Guide

## 🚀 Setup Instructions

### 1. Install PHP Dependencies

```bash
composer install
```

### 2. Install JavaScript Dependencies

```bash
npm install
npm run build
```

### 3. Copy Environment File

```bash
cp .env.example .env
```

### 4. Configure the Database

* Create a new database.
* Update the `.env` file with your database credentials.

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Storage Symlink

```bash
php artisan storage:link
```

### 7. Update App URL

In your `.env` file, update:

```env
APP_URL=http://localhost:8000
```

### 8. Run the Application

```bash
php artisan serve
```

Open your browser and go to: [http://localhost:8000](http://localhost:8000)

```
```
