# Render Deployment Guide

## Database Configuration Fixed ✅
- Default connection changed from `sqlsrv` to `mysql`
- Compatible with Render's MySQL database service
- `.env.example` already has MySQL configuration

## Deploy to Render

### Step 1: Push Updated Code to GitHub
```bash
cd CURRENT-deploy
git remote add origin <your-github-repo-url>
git push -u origin main
```

### Step 2: Setup Render
1. **Go to render.com** and sign in
2. **Click "New"** → "Web Service"
3. **Connect GitHub** repository
4. **Select branch**: `main`
5. **Runtime**: Node (or leave default)
6. **Build Command**: `npm install && npm run build`
7. **Start Command**: `php artisan serve --host=0.0.0.0 --port=10000`

### Step 3: Environment Variables
Set these in Render dashboard:

**Database (MySQL):**
- `DB_CONNECTION=mysql`
- `DB_HOST=your-render-mysql-host`
- `DB_PORT=3306`
- `DB_DATABASE=your_database_name`
- `DB_USERNAME=your_username`
- `DB_PASSWORD=your_password`

**Application:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY=your-generated-key`
- `APP_URL=https://your-app.onrender.com`

**Mail (if needed):**
- `MAIL_MAILER=smtp`
- `MAIL_HOST=smtp.gmail.com`
- `MAIL_PORT=587`
- `MAIL_USERNAME=your-email@gmail.com`
- `MAIL_PASSWORD=your-app-password`

### Step 4: Database Setup
1. **Create MySQL database** in Render dashboard
2. **Get connection details** from Render
3. **Update environment variables** with actual values
4. **Redeploy** to apply changes

### Step 5: Final Steps
1. **Run migrations** automatically on first deploy
2. **Test login** with existing user
3. **Verify functionality** (claims, bulk upload, etc.)

## Local Testing with MySQL

### Install MySQL Locally
```bash
# Windows (using XAMPP/WAMP)
# Or install MySQL separately
# Update .env with MySQL credentials
```

### Update Local .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_local_db
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### Test Locally
```bash
php artisan migrate
php artisan serve
```

## Troubleshooting

### Common Issues
- **"could not find driver"** → Database connection mismatch
- **"Maximum execution time"** → PHP timeout (fixed with proper dependencies)
- **"Class not found"** → Missing vendor dependencies

### Solutions
- Ensure `DB_CONNECTION=mysql` in environment
- Run `composer install` on deployment
- Check Render logs for detailed errors

## Files Ready for Deployment ✅
- Database configuration fixed
- All dependencies included
- Proper .gitignore in place
- Size optimized for GitHub

Your application is now ready for Render deployment with MySQL!
