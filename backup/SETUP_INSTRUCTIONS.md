# 🛒 PADI MART - Setup Instructions

Complete guide to set up PADI MART on your local machine.

---

## 📋 Prerequisites

Before you begin, make sure you have:

- ✅ **XAMPP** (or similar: WAMP, MAMP, LAMP)
  - Download from: https://www.apachefriends.org/
- ✅ **Git** (to clone the repository)
  - Download from: https://git-scm.com/
- ✅ **Web Browser** (Chrome, Firefox, Edge, etc.)

---

## 🚀 Installation Steps

### Step 1: Clone the Repository

```bash
cd C:\xampp\htdocs
git clone <your-github-repo-url> padi
```

Or download the ZIP file and extract it to `C:\xampp\htdocs\padi`

---

### Step 2: Start XAMPP

1. Open **XAMPP Control Panel**
2. Start **Apache**
3. Start **MySQL**

---

### Step 3: Create Database

**Option A: Using phpMyAdmin (Recommended)**

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Database name: `padi_mart`
4. Collation: `utf8mb4_general_ci`
5. Click **"Create"**

**Option B: Using SQL Command**

1. Go to phpMyAdmin → SQL tab
2. Run this command:
```sql
CREATE DATABASE padi_mart CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

or if didn't work
```sql
CREATE DATABASE padi_mart;
```

---

### Step 4: Import Database Tables

1. In phpMyAdmin, select the `padi_mart` database (click on it in the left sidebar)
2. Click the **"Import"** tab at the top
3. Click **"Choose File"**
4. Navigate to: `C:\xampp\htdocs\padi\database\complete_setup.sql`
5. Click **"Go"** at the bottom

✅ You should see a success message!

---

### Step 5: Configure Database Connection

1. Go to the `api` folder: `C:\xampp\htdocs\padi\api\`
2. Find the file: `config.example.php`
3. **Copy** it and rename the copy to: `config.php`
4. Open `config.php` in a text editor
5. Update the database credentials if needed:

```php
define('DB_HOST', 'localhost');     // Usually 'localhost'
define('DB_USER', 'root');          // Default XAMPP username
define('DB_PASS', '');              // Default XAMPP password (empty)
define('DB_NAME', 'padi_mart');     // Database name
```

6. Save the file

---

### Step 6: Set Up Uploads Folder

The `uploads` folder should already exist. If not:

1. Create a folder named `uploads` in the root directory: `C:\xampp\htdocs\padi\uploads\`
2. Make sure it has write permissions

---

### Step 7: Access the Website

Open your browser and visit:

- **Homepage**: `http://localhost/padi/`
- **Admin Panel**: `http://localhost/padi/admin.php`
- **Login Page**: `http://localhost/padi/login.php`

---

## 🔐 Default Login Credentials

### Admin Account (Pre-created)
```
Username: admin
Password: admin123
```

⚠️ **IMPORTANT**: Change this password immediately after first login!

### Regular User Accounts
- Regular users can register via the registration page: `http://localhost/padi/register.php`
- Regular users can view products but **cannot** access the admin panel
- Only admins can access the admin panel and manage products/categories

---

## 📁 Project Structure

```
padi/
├── api/                    # Backend API files
│   ├── config.php         # Database configuration (create from config.example.php)
│   ├── config.example.php # Template for database config
│   ├── get-products.php   # Get products API
│   ├── manage-products.php # Product CRUD operations
│   └── manage-categories.php # Category CRUD operations
├── css/                   # Stylesheets
├── js/                    # JavaScript files
├── database/              # Database setup files
│   ├── complete_setup.sql # Complete database setup (USE THIS!)
│   ├── add_users_table.sql # Add users table to existing database
│   ├── create_admin_table.sql
│   └── setup_admin.php
├── uploads/               # Product images (not tracked by Git)
├── index.php              # Homepage
├── admin.php              # Admin panel
├── login.php              # Login page
├── register.php           # Registration page
├── logout.php             # Logout handler
└── .gitignore             # Git ignore rules
```

---

## 🔄 Upgrading Existing Database

If you already have the database set up from a previous version and just need to add the users table:

1. In phpMyAdmin, select the `padi_mart` database
2. Click the **"Import"** tab
3. Import: `database/add_users_table.sql`
4. Click **"Go"**

This will add the `users` table for regular user authentication without affecting your existing data.

---

## ✅ Verification Checklist

After setup, verify everything works:

- [ ] Homepage loads at `http://localhost/padi/`
- [ ] Can see products on homepage
- [ ] Can access login page
- [ ] Can login with admin credentials (admin/admin123)
- [ ] Admin can access admin panel
- [ ] Can add/edit/delete products in admin panel
- [ ] Can add/edit/delete categories in admin panel
- [ ] Can register new user account
- [ ] Regular users can login but cannot access admin panel
- [ ] Can logout successfully

---

## 🐛 Troubleshooting

### Problem: "Connection failed" error

**Solution**: 
- Make sure MySQL is running in XAMPP
- Check database credentials in `api/config.php`
- Verify database `padi_mart` exists

### Problem: "Table doesn't exist" error

**Solution**: 
- Import `database/complete_setup.sql` in phpMyAdmin
- Make sure you selected the correct database before importing

### Problem: Can't upload product images

**Solution**: 
- Check if `uploads/` folder exists
- Make sure the folder has write permissions
- On Windows: Right-click folder → Properties → Security → Edit → Allow "Full control"

### Problem: Page shows PHP code instead of rendering

**Solution**: 
- Make sure Apache is running in XAMPP
- Access via `http://localhost/padi/` not `file:///`
- Clear browser cache (Ctrl + Shift + Delete)

---

## 🤝 Contributing

When making changes:

1. Create a new branch: `git checkout -b feature-name`
2. Make your changes
3. Commit: `git commit -m "Description of changes"`
4. Push: `git push origin feature-name`
5. Create a Pull Request

---

## 📞 Support

If you encounter any issues:

1. Check the troubleshooting section above
2. Check browser console for errors (F12)
3. Check Apache error logs in XAMPP
4. Contact the project maintainer

---

## 🎉 You're All Set!

Your PADI MART installation is complete. Happy coding! 🚀

