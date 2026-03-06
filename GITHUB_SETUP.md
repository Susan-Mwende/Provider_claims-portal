# GitHub Setup Instructions

## 1. Configure Git (if not already done)
```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## 2. Create GitHub Repository
1. Go to GitHub.com
2. Click "New repository"
3. Name: "claims-management-system"
4. Public repository
5. Don't initialize with README
6. Click "Create repository"

## 3. Push to GitHub
Replace the URL below with your actual repository URL:
```bash
cd CURRENT-deploy
git remote add origin https://github.com/YOUR_USERNAME/claims-management-system.git
git branch -M main
git push -u origin main
```

## 4. Next Steps for Render Deployment
1. Go to render.com
2. Connect your GitHub account
3. Select this repository
4. Choose "Web Service"
5. Render will auto-detect Laravel
6. Set environment variables
7. Deploy!

## Environment Variables for Render
- `APP_ENV`: production
- `APP_DEBUG`: false
- `APP_KEY`: (generate with `php artisan key:generate --show`)
- Database credentials (from Render)
- Mail credentials (if needed)

## Files to Exclude from Deployment
The .gitignore already excludes:
- vendor/ (will be installed by composer)
- node_modules/ (will be installed by npm)
- storage/ (will be created)
- .env (will be created on server)
