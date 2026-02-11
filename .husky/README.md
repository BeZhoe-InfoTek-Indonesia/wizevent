# Husky Git Hooks

This project uses [Husky](https://typicode.github.io/husky/) to enforce code quality checks before commits and pushes.

## Installed Hooks

### 🔍 Pre-commit Hook
Runs automatically before each commit:
- **Laravel Pint**: Auto-formats PHP code according to Laravel standards
- **PHPStan Level 5**: Analyzes staged PHP files for type safety and potential bugs

Only staged PHP files are checked, making commits fast and efficient.

### 🚀 Pre-push Hook
Runs automatically before pushing to remote:
- **PHPStan Level 5**: Full codebase analysis to ensure no issues slip through

This prevents pushing code with type errors or potential bugs.

## Configuration

### PHPStan Configuration
Located in `phpstan.neon`:
- **Level**: 5 (strict type checking)
- **Paths analyzed**: `app/`, `config/`, `database/`, `routes/`
- **Excluded**: Middleware, migrations, and generated files

### Lint-staged Configuration
Located in `package.json` under `lint-staged`:
```json
{
  "*.php": [
    "vendor/bin/pint",
    "vendor/bin/phpstan analyse --memory-limit=2G"
  ]
}
```

## Usage

### Running Checks Manually

```bash
# Run PHPStan on entire codebase
composer phpstan

# Or via npm
npm run lint

# Run Pint formatter
vendor/bin/pint

# Run lint-staged on currently staged files
npx lint-staged
```

### Skipping Hooks (Not Recommended)

If you absolutely need to skip hooks:

```bash
# Skip pre-commit hook
git commit --no-verify -m "your message"

# Skip pre-push hook
git push --no-verify
```

⚠️ **Warning**: Only skip hooks when absolutely necessary. They exist to maintain code quality.

## Setup for New Contributors

After cloning the repository:

```bash
# Install npm dependencies (includes Husky)
npm install

# Install PHP dependencies (includes PHPStan/Larastan)
composer install

# Hooks are automatically installed via the "prepare" script
```

## Troubleshooting

### Hook not running
```bash
# Reinstall Husky
npm run prepare
chmod +x .husky/pre-commit .husky/pre-push
```

### PHPStan memory issues
The hooks already use `--memory-limit=2G`. If you still have issues:
```bash
# Edit .husky/pre-commit or .husky/pre-push
# Change: --memory-limit=2G
# To: --memory-limit=4G
```

### False positives
If PHPStan reports false positives, you can:
1. Add ignoreErrors to `phpstan.neon`
2. Use PHPStan annotations in your code:
   ```php
   /** @phpstan-ignore-next-line */
   ```

## Benefits

✅ **Consistent code style** - Pint ensures all code follows Laravel standards  
✅ **Early bug detection** - PHPStan catches type errors before they reach production  
✅ **Automated quality** - No manual checks needed, hooks run automatically  
✅ **Fast feedback** - Issues caught immediately, not in CI/CD  
✅ **Team alignment** - Everyone follows the same standards  

## PHPStan Level 5

Level 5 provides:
- ✅ Basic type checking
- ✅ Unknown class checking
- ✅ Unknown function checking
- ✅ Unknown method checking
- ✅ Dead code detection
- ✅ Missing typehints on properties and return types

This is a good balance between strictness and practicality for Laravel projects.
