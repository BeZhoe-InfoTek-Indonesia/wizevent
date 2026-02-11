#!/bin/bash

# Cleanup Old Admin Resources - Use Filament Exclusively
# This script removes all old Blade-based admin resources

set -e

echo "🧹 Cleaning up old admin resources..."
echo ""

# Step 1: Remove old admin views
echo "📁 Removing old admin Blade views..."
rm -rf resources/views/admin
echo "✅ Admin views removed"
echo ""

# Step 2: Remove old admin controllers
echo "📁 Removing old admin controllers..."
rm -rf app/Http/Controllers/Admin
echo "✅ Admin controllers removed"
echo ""

# Step 3: Clear caches
echo "🧹 Clearing caches..."
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan optimize:clear
echo "✅ Caches cleared"
echo ""

echo "✨ Cleanup Complete!"
echo ""
echo "⚠️  IMPORTANT: You need to manually edit routes/web.php"
echo "Remove lines 36-91 (old admin routes) and replace with:"
echo ""
echo "// Admin routes are now handled by Filament at /admin"
echo "// See: app/Providers/Filament/AdminPanelProvider.php"
echo ""
echo "📍 Next step: Visit http://localhost:8000/admin"
echo ""
