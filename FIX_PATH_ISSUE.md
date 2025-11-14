# 🔧 Fix for 404/405 Errors - Path Configuration

## Problem
Your friend is getting:
- **404 errors** when adding products
- **405 errors** when deleting products

## Root Cause
The API URLs were hardcoded with `/padi/` path. If the project is in a different directory (like `/padimart/`, `/padi_mart/`, etc.), the URLs won't work.

## Solution Applied ✅

### What Was Fixed

1. **Created `js/config.js`** (NEW)
   - Automatically detects the correct project path
   - Works regardless of directory name
   - Exports `window.CONFIG.API_BASE_URL`

2. **Updated `js/admin.js`**
   - Now uses dynamic API URL from config.js
   - Falls back to `/padi/api` if config not loaded
   - All CRUD operations use dynamic path

3. **Updated `js/landingpage.js`**
   - Now uses dynamic API URL from config.js
   - Falls back to `/padi/api` if config not loaded
   - Product loading uses dynamic path

4. **Updated `index.php`**
   - Added `<script src="js/config.js"></script>` before other scripts
   - Ensures config loads first

5. **Updated `admin.php`**
   - Added `<script src="js/config.js"></script>` before other scripts
   - Ensures config loads first

## How It Works

### Before (Hardcoded)
```javascript
fetch('/padi/api/get-products.php')  // ❌ Only works if project is in /padi/
```

### After (Dynamic)
```javascript
const apiUrl = window.CONFIG?.API_BASE_URL || '/padi/api';
fetch(apiUrl + '/get-products.php')  // ✅ Works in any directory
```

## For Your Friend

### Step 1: Pull Latest Changes
```bash
git pull origin main
# or
git pull
```

### Step 2: Clear Browser Cache
- Press `Ctrl+Shift+Delete` (or `Cmd+Shift+Delete` on Mac)
- Clear cache and cookies
- Or open in private/incognito window

### Step 3: Test

#### Test 1: Check Console
1. Open admin.php
2. Press `F12` to open Developer Tools
3. Go to Console tab
4. Look for messages like:
   ```
   Project Base URL: /padimart
   API Base URL: /padimart/api
   ```
5. If you see these, the fix is working! ✅

#### Test 2: Add Product
1. Go to admin.php
2. Fill in product form
3. Click "Tambah Produk"
4. Should work now! ✅

#### Test 3: Delete Product
1. Go to admin.php
2. Click delete button on a product
3. Confirm deletion
4. Should work now! ✅

## Troubleshooting

### Still Getting 404/405?

**Check 1: Is config.js loaded?**
- Open browser console (F12)
- Look for "Project Base URL" message
- If not there, config.js didn't load

**Check 2: What's the API URL?**
- In console, type: `window.CONFIG.API_BASE_URL`
- Should show something like `/padimart/api`
- If it shows `/padi/api`, check your directory name

**Check 3: Does the API file exist?**
- Visit: `http://localhost/[YOUR_DIRECTORY]/api/test.php`
- Replace `[YOUR_DIRECTORY]` with actual directory name
- Should show green checkmarks

**Check 4: Is PHP working?**
- Visit: `http://localhost/[YOUR_DIRECTORY]/api/test.php`
- If you see PHP errors, check database connection
- See `PHP_INTEGRATION_GUIDE.md` for help

### Still Not Working?

1. **Check directory name**
   - What's the actual directory name?
   - Is it `/padi/`, `/padimart/`, `/padi_mart/`, etc.?

2. **Check URL in browser**
   - What's the full URL when you visit the page?
   - Example: `http://localhost/padimart/admin.php`
   - The directory name is `padimart`

3. **Check console messages**
   - Open F12 console
   - Look for error messages
   - Share the error message

## Files Changed

```
✅ js/config.js                 (NEW - Dynamic path detection)
✅ js/admin.js                  (UPDATED - Uses dynamic path)
✅ js/landingpage.js            (UPDATED - Uses dynamic path)
✅ index.php                    (UPDATED - Loads config.js)
✅ admin.php                    (UPDATED - Loads config.js)
```

## How to Verify

### Method 1: Browser Console
```javascript
// Open F12 console and type:
window.CONFIG.API_BASE_URL

// Should output something like:
// "/padimart/api"
// "/padi/api"
// "/api"
```

### Method 2: Check Network Tab
1. Open F12 → Network tab
2. Add a product
3. Look for request to `manage-products.php`
4. Check the URL in the request
5. Should be correct path

### Method 3: Check Test Page
1. Visit: `http://localhost/[YOUR_DIR]/api/test.php`
2. Should show green checkmarks
3. Confirms API is accessible

## Summary

✅ **Fixed**: Hardcoded paths
✅ **Added**: Dynamic path detection
✅ **Result**: Works in any directory
✅ **Fallback**: Still works with `/padi/` if needed

Your friend should now be able to add/delete products without 404/405 errors!

---

**Status**: ✅ FIXED

**Next**: Pull changes and test! 🚀

