#!/bin/bash

# Filament Admin Panel - Complete Setup Script
# Run this script to complete the Filament integration

set -e  # Exit on error

echo "🚀 Filament Admin Panel Setup"
echo "=============================="
echo ""

# Step 1: Install Shield
echo "📦 Step 1: Installing Filament Shield..."
php artisan shield:install --fresh --panel=admin --minimal

echo "✅ Shield installed"
echo ""

# Step 2: Generate Shield Resources
echo "🛡️ Step 2: Generating Shield resources..."
php artisan shield:generate --all --panel=admin

echo "✅ Shield resources generated"
echo ""

# Step 3: Create Super Admin
echo "👑 Step 3: Creating Super Admin role..."
php artisan shield:super-admin --user=1 --panel=admin

echo "✅ Super Admin created"
echo ""

# Step 4: Create User Resource
echo "📝 Step 4: Creating User Resource..."
php artisan make:filament-resource User --generate --panel=admin

echo "✅ User Resource created"
echo ""

# Step 5: Create Role Resource
echo "📝 Step 5: Creating Role Resource..."
php artisan make:filament-resource Role \
    --model="Spatie\Permission\Models\Role" \
    --generate \
    --panel=admin

echo "✅ Role Resource created"
echo ""

# Step 6: Create Permission Resource
echo "📝 Step 6: Creating Permission Resource..."
php artisan make:filament-resource Permission \
    --model="Spatie\Permission\Models\Permission" \
    --generate \
    --panel=admin

echo "✅ Permission Resource created"
echo ""

# Step 7: Create Activity Resource
echo "📝 Step 7: Creating Activity Resource..."
php artisan make:filament-resource Activity \
    --model="Spatie\Activitylog\Models\Activity" \
    --generate \
    --panel=admin

echo "✅ Activity Resource created"
echo ""

# Step 8: Create Dashboard Widgets
echo "📊 Step 8: Creating Dashboard Widgets..."
php artisan make:filament-widget StatsOverview --stats-overview --panel=admin
php artisan make:filament-widget UsersChart --chart --panel=admin
php artisan make:filament-widget RecentActivity --table-widget --panel=admin

echo "✅ Dashboard Widgets created"
echo ""

# Step 9: Clear caches
echo "🧹 Step 9: Clearing caches..."
php artisan optimize:clear
php artisan filament:cache-components

echo "✅ Caches cleared"
echo ""

# Step 10: Build assets
echo "🎨 Step 10: Building assets..."
npm run build

echo "✅ Assets built"
echo ""

echo "✨ Filament Admin Panel Setup Complete!"
echo ""
echo "📍 Next steps:"
echo "1. Visit http://localhost:8000/admin"
echo "2. Login with your Super Admin credentials"
echo "3. Customize resources in app/Filament/Resources/"
echo ""
echo "📚 Documentation:"
echo "- Setup Guide: FILAMENT_SETUP_GUIDE.md"
echo "- Commands: FILAMENT_COMMANDS.md"
echo "- Status: FILAMENT_STATUS.md"
echo ""
