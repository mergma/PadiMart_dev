# ⚡ PADI MART - Quick Start Guide

## For Your Friends (After Pulling from GitHub)

### 🎯 5-Minute Setup

1. **Start XAMPP**
   - Start Apache
   - Start MySQL

2. **Create Database**
   - Open: `http://localhost/phpmyadmin`
   - Create new database: `padi_mart`

3. **Import Database**
   - Select `padi_mart` database
   - Click "Import" tab
   - Choose file: `database/complete_setup.sql`
   - Click "Go"

4. **Configure Database Connection**
   - Copy `api/config.example.php` → `api/config.php`
   - Edit `api/config.php` if needed (usually default XAMPP settings work)

5. **Done!**
   - Visit: `http://localhost/padi/`
   - Login: `http://localhost/padi/login.php`
   - Username: `admin` | Password: `admin123`

---

## 📝 What to Tell Your Friends

Send them this message:

```
setup (follow all steps in order, if mess up just redownload the project from the repo & start over)

1. Make sure you have XAMPP installed
2. Clone/download the project to C:\xampp\htdocs\padi
3. Follow the instructions in SETUP_INSTRUCTIONS.md
4. It takes about 5 minutes!

The main steps are:
- Create database "padi_mart" in phpMyAdmin
- Import the database/complete_setup.sql file
- Copy api/config.example.php to api/config.php
- Visit http://localhost/padi/

Default login: admin / admin123
```

---

## ⚠️ Important Notes

### Files NOT in GitHub (They need to create these):
- `api/config.php` - Copy from `config.example.php`
- Product images in `uploads/` folder

### Files IN GitHub:
- All code files
- Database setup SQL
- Configuration template
- Setup instructions

---

## 🔒 Security Reminder

**Before pushing to GitHub, make sure:**

1. ✅ `.gitignore` is in place
2. ✅ `api/config.php` is NOT committed (only `config.example.php`)
3. ✅ No sensitive passwords in code
4. ✅ `uploads/` folder images are NOT committed

**To check what will be committed:**
```bash
git status
```

**If you accidentally committed config.php:**
```bash
git rm --cached api/config.php
git commit -m "Remove config.php from tracking"
```

---

## 🚀 Ready to Push?

```bash
# Add all files
git add .

# Commit
git commit -m "Initial commit - PADI MART project"

# Push to GitHub
git push origin main
```

Your friends can then:
```bash
git clone <your-repo-url> padi
```

And follow the SETUP_INSTRUCTIONS.md file!

