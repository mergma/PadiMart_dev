# 👥 Setup Guide for Your Friend

## Quick Setup (5 Minutes)

### Step 1: Clone/Pull Project
```bash
# If cloning for first time
git clone [repository-url]
cd [project-directory]

# If already cloned, pull latest
git pull origin main
```

### Step 2: Setup Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create database: `padi_mart`
3. Import SQL file: `database/padi_mart_fixed.sql`
4. Done! ✅

### Step 3: Test Connection
1. Visit: `http://localhost/[YOUR_DIR]/api/test.php`
2. Should see green checkmarks
3. If red, check database connection

### Step 4: Test Frontend
1. Visit: `http://localhost/[YOUR_DIR]/index.php`
2. Should see 3 products
3. Try search and filters

### Step 5: Test Admin
1. Visit: `http://localhost/[YOUR_DIR]/admin.php`
2. Should see 3 products in list
3. Try adding a product
4. Try editing a product
5. Try deleting a product

## Common Issues & Fixes

### Issue: 404 Error When Adding Product
**Cause**: Project in different directory
**Fix**: Already fixed! Just pull latest changes
```bash
git pull origin main
```

### Issue: 405 Error When Deleting Product
**Cause**: Project in different directory
**Fix**: Already fixed! Just pull latest changes
```bash
git pull origin main
```

### Issue: Products Not Loading
**Cause**: Database not connected
**Fix**: 
1. Check `/api/test.php`
2. Verify MySQL is running
3. Check database credentials in `api/config.php`

### Issue: Still Getting Errors After Pull
**Fix**: Clear browser cache
- Press `Ctrl+Shift+Delete`
- Clear cache and cookies
- Or use private/incognito window

## Verify Fix is Working

### Method 1: Check Console
1. Open admin.php
2. Press `F12` (Developer Tools)
3. Go to Console tab
4. Look for:
   ```
   Project Base URL: /[YOUR_DIR]
   API Base URL: /[YOUR_DIR]/api
   ```
5. If you see these, fix is working! ✅

### Method 2: Check Network
1. Open admin.php
2. Press `F12` → Network tab
3. Add a product
4. Look for `manage-products.php` request
5. Check URL is correct

### Method 3: Test Operations
1. Add a product → Should work ✅
2. Edit a product → Should work ✅
3. Delete a product → Should work ✅

## File Structure

```
[YOUR_DIR]/
├── index.php                    ← Landing page
├── admin.php                    ← Admin panel
├── api/
│   ├── config.php              ← Database config
│   ├── test.php                ← Connection test
│   ├── get-products.php        ← Fetch products
│   └── manage-products.php     ← CRUD operations
├── js/
│   ├── config.js               ← NEW: Dynamic paths
│   ├── admin.js                ← UPDATED
│   ├── landingpage.js          ← UPDATED
│   └── products-data.js
├── css/
│   ├── admin.css
│   ├── landingpage.css
│   └── responsive-utilities.css
└── database/
    └── padi_mart_fixed.sql     ← Import this
```

## What's New

### New File: `js/config.js`
- Automatically detects project directory
- Exports dynamic API base URL
- Works in any directory

### Updated Files
- `js/admin.js` - Uses dynamic paths
- `js/landingpage.js` - Uses dynamic paths
- `index.php` - Loads config.js
- `admin.php` - Loads config.js

## Testing Checklist

- [ ] Database connected (test.php shows green)
- [ ] Products display on index.php
- [ ] Search works
- [ ] Filters work
- [ ] Can add product
- [ ] Can edit product
- [ ] Can delete product
- [ ] No console errors (F12)

## Troubleshooting

### Check 1: What's your directory name?
```
If URL is: http://localhost/padimart/admin.php
Then directory is: padimart
```

### Check 2: Is config.js loaded?
```javascript
// In browser console (F12):
window.CONFIG.API_BASE_URL
// Should show: /padimart/api (or your directory)
```

### Check 3: Can you reach the API?
```
Visit: http://localhost/[YOUR_DIR]/api/test.php
Should show: Green checkmarks
```

### Check 4: Check browser console
```
Press F12 → Console tab
Look for error messages
Share errors if stuck
```

## Need Help?

1. **Check**: `FIX_PATH_ISSUE.md` - Detailed explanation
2. **Check**: `PHP_INTEGRATION_GUIDE.md` - API documentation
3. **Check**: `TESTING_CHECKLIST.md` - Full test cases
4. **Check**: Browser console (F12) for errors

## Summary

✅ **Fixed**: 404/405 errors
✅ **Added**: Dynamic path detection
✅ **Result**: Works in any directory
✅ **Ready**: To test and use

---

**Status**: ✅ READY TO USE

**Next**: Pull changes and test! 🚀

