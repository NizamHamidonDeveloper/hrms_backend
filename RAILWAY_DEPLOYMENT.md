# Railway Deployment Guide for HRMS Backend

## Prerequisites
- Railway account
- GitHub repository with your Laravel project
- MySQL database service on Railway

## Deployment Steps

### 1. Environment Variables Setup
Set the following environment variables in Railway dashboard:

#### Required Variables:
```
APP_NAME=HRMS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
DB_CONNECTION=mysql
DB_HOST=<railway-mysql-host>
DB_PORT=3306
DB_DATABASE=<railway-database-name>
DB_USERNAME=<railway-username>
DB_PASSWORD=<railway-password>
TRUSTED_PROXIES=**
```

#### Optional Variables:
```
LOG_LEVEL=debug
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 2. Database Setup
1. Add MySQL service to your Railway project
2. Copy the database credentials from Railway MySQL service
3. Update environment variables with the database credentials
4. The application will automatically run migrations on deployment

### 3. Laravel Passport Setup
After deployment, run these commands in Railway console:
```bash
php artisan passport:keys --force
php artisan passport:client --personal
```

### 4. Deployment Configuration
The following files have been created for Railway deployment:
- `railway.json` - Railway configuration
- `Procfile` - Process definition
- `env.example` - Environment variables template
- `deploy.sh` - Deployment script
- `app/Http/Middleware/TrustProxies.php` - Trusted proxies middleware

### 5. Build Configuration
Railway will automatically:
- Install PHP dependencies via Composer
- Run the deployment script
- Start the application on the assigned port

## Post-Deployment Checklist
- [ ] Verify application is accessible via Railway URL
- [ ] Check database migrations completed successfully
- [ ] Test API endpoints
- [ ] Verify Laravel Passport authentication
- [ ] Check logs for any errors

## Troubleshooting

### Common Issues:
1. **Database Connection Errors**: Verify database credentials in environment variables
2. **Migration Failures**: Check database permissions and connection
3. **Passport Key Errors**: Run `php artisan passport:keys --force` in Railway console
4. **Asset Loading Issues**: Ensure `APP_URL` is set correctly

### Useful Commands:
```bash
# Check application status
php artisan route:list

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations manually
php artisan migrate --force

# Generate Passport keys
php artisan passport:keys --force
```

## Security Notes
- Never commit `.env` file to version control
- Use strong database passwords
- Enable HTTPS in production
- Regularly update dependencies
