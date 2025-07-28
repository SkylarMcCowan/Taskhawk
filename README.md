# TaskHawk - Dockerized Laravel Task Tracker

A simple web application built with Laravel and running inside Docker containers. TaskHawk allows users to register, login, and manage their tasks with file attachments.

## Features

- **User Authentication**: Register and login with Laravel Breeze
- **Task Management**: Create, view, edit, and delete tasks
- **File Attachments**: Upload and manage file attachments for tasks
- **Task Details**: Each task includes:
  - Name/title
  - Due date (optional)
  - Notes (optional)
  - Multiple file attachments
- **Due Date Tracking**: Visual indicators for overdue and due-today tasks
- **Responsive Design**: Built with Tailwind CSS

## Project Structure

```
taskhawk/
├── app/                # Laravel application source code
│   ├── app/
│   ├── database/
│   ├── public/         # Web root for Nginx
│   ├── resources/
│   ├── routes/
│   ├── storage/        # File attachments stored here
│   └── ...
├── docker/
│   ├── php/
│   │   ├── Dockerfile  # PHP-FPM build config
│   │   └── local.ini   # PHP configuration
│   └── nginx/
│       └── default.conf # Nginx configuration
├── docker-compose.yml  # Container orchestration
├── .env                # Environment variables
└── README.md
```

## Docker Services

- **app**: PHP-FPM container running Laravel
- **web**: Nginx container serving the Laravel app
- **db**: MySQL 8 container for database storage

## Quick Start

### Prerequisites

- Docker and Docker Compose installed on your system

### Installation

1. **Clone or create the project**:
   ```bash
   # The project files are already set up in this directory
   cd /path/to/taskhawk
   ```

2. **Start the Docker containers**:
   ```bash
   docker-compose up -d
   ```

3. **Install dependencies and set up Laravel**:
   ```bash
   # Install Composer dependencies
   docker run --rm -v $(pwd)/app:/var/www composer install

   # Generate application key
   docker exec taskhawk-app php artisan key:generate

   # Run database migrations
   docker exec taskhawk-app php artisan migrate

   # Create symbolic link for storage
   docker exec taskhawk-app php artisan storage:link
   ```

4. **Access the application**:
   Open your browser and navigate to `http://localhost:8080`

### Development Commands

**Run Laravel Artisan commands**:
```bash
docker exec taskhawk-app php artisan [command]
```

**Access the application container**:
```bash
docker exec -it taskhawk-app bash
```

**Access the database**:
```bash
docker exec -it taskhawk-db mysql -u taskhawk -p taskhawk
```

**View logs**:
```bash
docker-compose logs -f [service_name]
```

**Stop containers**:
```bash
docker-compose down
```

## Application Features

### Authentication
- User registration and login powered by Laravel Breeze
- Password reset functionality
- Profile management

### Task Management
- **Create Tasks**: Add new tasks with name, due date, notes, and file attachments
- **View Tasks**: List all your tasks with due date indicators
- **Task Details**: View complete task information and download attachments
- **Edit Tasks**: Update task information and add/remove attachments
- **Delete Tasks**: Remove tasks and associated files

### File Uploads
- Multiple file attachments per task
- Files stored in Laravel's public storage
- Maximum 10MB per file
- Automatic cleanup when tasks are deleted

## Configuration

### Environment Variables

Key environment variables in `.env`:

```env
# App Configuration
APP_NAME=TaskHawk
APP_URL=http://localhost:8080

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=taskhawk
DB_USERNAME=taskhawk
DB_PASSWORD=password
```

### Docker Configuration

- **Nginx Port**: 8080 (configurable in docker-compose.yml)
- **MySQL Port**: 3306 (exposed for external access)
- **PHP Configuration**: Custom settings in `docker/php/local.ini`

## Database Schema

### Tables

1. **users**: User accounts (Laravel default)
2. **tasks**: Task information
   - `id`, `user_id`, `name`, `due_date`, `notes`, `created_at`, `updated_at`
3. **attachments**: File attachments
   - `id`, `task_id`, `filename`, `filepath`, `created_at`, `updated_at`

### Relationships
- User hasMany Tasks
- Task belongsTo User
- Task hasMany Attachments
- Attachment belongsTo Task

## Security Features

- **Task Authorization**: Users can only view/edit their own tasks
- **File Upload Validation**: File size limits and validation
- **CSRF Protection**: All forms protected with CSRF tokens
- **Secure File Storage**: Files stored outside web root with proper access

## Troubleshooting

### Common Issues

1. **Permission Errors**:
   ```bash
   docker exec taskhawk-app chown -R www-data:www-data /var/www/storage
   docker exec taskhawk-app chmod -R 775 /var/www/storage
   ```

2. **Database Connection Issues**:
   - Ensure the database container is running
   - Check database credentials in `.env`
   - Wait for MySQL to fully start before running migrations

3. **File Upload Issues**:
   - Check PHP upload limits in `docker/php/local.ini`
   - Ensure storage symlink exists: `php artisan storage:link`

### Logs

View application logs:
```bash
docker exec taskhawk-app tail -f storage/logs/laravel.log
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test with Docker
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
