# PHPStan & Husky Setup Guide

## ✅ What's Been Configured

### 1. PHPStan Level 5
- **Configuration file**: `phpstan.neon`
- **Level**: 5 (strict type checking)
- **Integration**: Larastan (PHPStan for Laravel)
- **Analyzed paths**: `app/`, `config/`, `database/`, `routes/`
- **Excluded**: Middleware, migrations, generated files

### 2. Husky Git Hooks
- **Pre-commit hook**: Runs Pint + PHPStan on staged files
- **Pre-push hook**: Runs PHPStan on entire codebase
- **Configuration**: `.husky/` directory

### 3. Composer Scripts
Added to `composer.json`:
```json
"phpstan": [
    "vendor/bin/phpstan analyse --memory-limit=2G"
]
```

### 4. NPM Scripts
Added to `package.json`:
```json
"prepare": "husky",
"lint": "composer phpstan"
```

### 5. Lint-staged
Configured to run on `*.php` files:
- Laravel Pint (code formatting)
- PHPStan Level 5 (static analysis)

## 🚀 Installation Steps

Run these commands to complete the setup:

```bash
# 1. Install npm dependencies (includes Husky & lint-staged)
npm install

# 2. Install/update composer dependencies (includes Larastan)
composer install

# 3. Verify Husky hooks are executable (should already be done)
chmod +x .husky/pre-commit .husky/pre-push .husky/_/husky.sh
```

## 📋 Usage

### Run PHPStan Manually
```bash
# Via composer
composer phpstan

# Via npm
npm run lint

# Direct command
vendor/bin/phpstan analyse --memory-limit=2G
```

### Run Pint (Code Formatter)
```bash
vendor/bin/pint
```

### Test Hooks Manually
```bash
# Test pre-commit hook
npx lint-staged

# Test pre-push hook
.husky/pre-push
```

## 🔄 How It Works

### On Every Commit:
1. You run `git commit`
2. Pre-commit hook triggers
3. Lint-staged identifies staged PHP files
4. Pint formats the staged files
5. PHPStan analyzes the staged files
6. If all checks pass → commit succeeds
7. If checks fail → commit is blocked

### On Every Push:
1. You run `git push`
2. Pre-push hook triggers
3. PHPStan analyzes entire codebase
4. If analysis passes → push succeeds
5. If errors found → push is blocked

## 🎯 Benefits

✅ **Automated Quality**: No manual checks needed  
✅ **Consistent Style**: Pint ensures Laravel coding standards  
✅ **Type Safety**: PHPStan catches type errors early  
✅ **Fast Feedback**: Issues caught before CI/CD  
✅ **Team Alignment**: Everyone follows same standards  

## 🛠️ Troubleshooting

### Hooks not running?
```bash
npm run prepare
chmod +x .husky/pre-commit .husky/pre-push
```

### PHPStan memory issues?
Edit the hooks and increase memory:
```bash
# Change --memory-limit=2G to --memory-limit=4G
```

### Skip hooks (emergency only)?
```bash
git commit --no-verify
git push --no-verify
```

## 📚 Documentation

- **Husky docs**: `.husky/README.md`
- **PHPStan config**: `phpstan.neon`
- **Lint-staged config**: `package.json` (lint-staged section)

## 🎓 PHPStan Level 5 Features

Level 5 checks for:
- ✅ Basic type checking
- ✅ Unknown classes, functions, methods
- ✅ Dead code detection
- ✅ Missing typehints on properties
- ✅ Missing return type declarations
- ✅ Incorrect type usage

This provides a good balance between strictness and practicality for Laravel projects.

---

**Next Steps**: Run `npm install` to activate the hooks!
