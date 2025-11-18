# PADI MART Database Migration Guide

## Overview
This migration upgrades the PADI MART product management system to match the example website's architecture while maintaining all PADI-specific features.

## What's Changed

### Database Schema
1. **New `categories` table** - Replaces hardcoded categories with database-driven system
2. **Products table updates**:
   - Added `product_code` (VARCHAR 20) - Auto-generated codes like KD_001, KD_002
   - Added `category_id` (INT) - Foreign key to categories table
   - Added `stock` (INT) - Inventory tracking
   - Changed `image` storage from base64 LONGTEXT to file paths (VARCHAR 255)

### File Structure
1. **New files**:
   - `api/manage-categories.php` - Category CRUD API
   - `database/migration_add_categories.sql` - SQL migration script
   - `database/migration_images_to_files.php` - Image conversion script
   - `database/run_migrations.php` - Migration runner
   - `uploads/` directory - Product images storage

2. **Modified files**:
   - `admin.php` - Complete rebuild with Bootstrap modals and table view
   - `api/manage-products.php` - Updated for file uploads and new schema
   - `api/get-products.php` - Updated to join with categories table
   - `js/admin.js` - Simplified for modal-based system

3. **Deleted files**:
   - `api/products.php` - Redundant API
   - `js/products-data.js` - No longer needed
   - `debug-products.html` - Development file

4. **Backup files**:
   - `admin_old_backup.php` - Original admin interface (can be deleted after verification)

## Migration Steps

### Option 1: Automatic Migration (Recommended)

1. **Backup your database first!**
   ```sql
   mysqldump -u root padi_mart > backup_before_migration.sql
   ```

2. **Run the migration script**:
   ```
   Navigate to: http://localhost/padi/database/run_migrations.php
   ```

3. **Verify the migration**:
   - Check that categories table exists
   - Check that products have product_code, category_id, stock columns
   - Check that images are in uploads/ directory
   - Test admin panel at http://localhost/padi/admin.php

### Option 2: Manual Migration

1. **Backup your database**

2. **Run SQL migration**:
   ```bash
   mysql -u root padi_mart < database/migration_add_categories.sql
   ```

3. **Convert images to files**:
   ```bash
   php database/migration_images_to_files.php
   ```

4. **Create uploads directory**:
   ```bash
   mkdir uploads
   copy "img/PADI MART.png" "uploads/default.jpg"
   ```

## Post-Migration Checklist

- [ ] Categories table created successfully
- [ ] Products table has new columns (product_code, category_id, stock)
- [ ] Images converted to files in uploads/ directory
- [ ] Default image exists at uploads/default.jpg
- [ ] Admin panel loads without errors
- [ ] Can add new category
- [ ] Can add new product with image upload
- [ ] Can edit existing product
- [ ] Can delete product (and image file is removed)
- [ ] Product listing shows correctly on index.php
- [ ] Search and filter work on index.php

## Rollback Instructions

If something goes wrong:

1. **Restore database**:
   ```bash
   mysql -u root padi_mart < backup_before_migration.sql
   ```

2. **Restore admin.php**:
   ```bash
   move admin_old_backup.php admin.php
   ```

3. **Remove new files**:
   - Delete `api/manage-categories.php`
   - Delete `uploads/` directory
   - Restore `api/products.php`, `js/products-data.js` from version control

## New Features

### Admin Panel
- **Modal-based editing** - Cleaner UI with Bootstrap modals
- **Table view** - Better overview of all products
- **Category management** - Add/edit/delete categories
- **File upload** - Direct image upload instead of base64
- **Product codes** - Auto-generated KD_001, KD_002, etc.
- **Stock tracking** - Inventory management
- **Search** - Real-time product search

### API Improvements
- **File-based images** - Better performance, smaller database
- **Category relationships** - Proper foreign keys
- **Automatic cleanup** - Old images deleted when product updated/removed

## Troubleshooting

### Images not showing
- Check that uploads/ directory has write permissions (755)
- Verify image paths in database start with "uploads/"
- Check that default.jpg exists

### Categories not loading
- Verify categories table was created
- Check that migration inserted default categories
- Verify foreign key constraint is working

### Product codes not generating
- Check that product_code column exists
- Verify the generateProductCode() function in manage-products.php

### File upload errors
- Check PHP upload_max_filesize (should be at least 5M)
- Verify uploads/ directory permissions
- Check that move_uploaded_file() is not disabled

## Support

If you encounter issues:
1. Check the browser console for JavaScript errors
2. Check PHP error logs
3. Verify database schema matches expected structure
4. Ensure all files are in correct locations

