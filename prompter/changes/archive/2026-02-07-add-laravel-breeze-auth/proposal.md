# Change: Add Laravel Breeze Authentication System

## Why
The current system lacks a complete authentication implementation. While the foundation includes spatie/laravel-permission for authorization and has database schema for users, there are no authentication controllers, views, or routes. Laravel Breeze provides a production-ready, secure authentication foundation that integrates seamlessly with our existing Livewire-based visitor architecture and supports the required user roles (Super Admin, Event Manager, Visitor, etc.).

## What Changes
- Install and configure Laravel Breeze with Livewire stack
- Implement authentication views and components for both Admin and Visitor portals
- Setup registration, login, logout, password reset, and email verification flows
- Integrate with existing User model and spatie/laravel-permission roles
- Configure role-based redirects after authentication
- Add Google OAuth integration via Socialite to existing User model

## Impact
- **Affected Specs**: `authentication` (enhanced with complete implementation)
- **Affected Code**: Routes, Controllers, Views, Livewire components, User model
- **Dependencies**: Adds `laravel/breeze`, Livewire auth components