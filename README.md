# Vaaga Academy - Upgrade Documentation

This document provides a comprehensive overview of all dependencies and their versions for upgrade planning and maintenance purposes.

## Project Overview

- **Project Name**: Vaaga Academy (Laravel Boilerplate)
- **PHP Version**: ^8.3.0
- **Laravel Framework**: ^12.0
- **Node.js Package Manager**: npm/pnpm

---

## PHP Dependencies (composer.json)

### Core Framework
- **PHP**: ^8.3.0
- **Laravel Framework**: ^12.0
- **Laravel Passport**: ^12.0
- **Laravel Socialite**: ^5.10
- **Laravel Tinker**: ^2.8
- **Laravel Helpers**: ^1.5

### Database & ORM
- **Doctrine DBAL**: ^3.0
- **Laravel DataTables Oracle**: ^12.0
- **Laravel DataTables Buttons**: ^12.0

### Authentication & Authorization
- **Laravel Passport**: ^12.0
- **LCobucci JWT**: ^4.0
- **Spatie Laravel Permission**: ^6.0

### File Management & Storage
- **Laravel DomPDF**: ^3.0
- **Unisharp Laravel FileManager**: ~1.8
- **League Flysystem AWS S3 V3**: ^3.0

### Payment Gateways
- **Razorpay**: 2.*
- **Stripe PHP**: ^10.0|^11.0|^12.0|^13.0

### Utilities & Helpers
- **CreativeOrange Gravatar**: ~1.0
- **DarrylDecode Cart**: ~4.0
- **DaveJamesMiller Laravel Breadcrumbs**: ^5.0
- **Jenssegers Agent**: ^2.6
- **MTownsend Read Time**: ^2.0
- **Ramsey UUID**: ^4.7
- **Stevebauman Location**: ^7.5

### Third-Party Integrations
- **DevDojo Chatter**: 0.2.*
- **Guzzle HTTP**: ^7.8
- **Maatwebsite Excel**: ^3.1.48
- **SendGrid**: ~7
- **Spatie Laravel Backup**: ^9.0
- **Spatie Laravel HTML**: ^3.0
- **Spatie Laravel Newsletter**: ^5.0

### Security & Encryption
- **Paragonie Certainty**: ^2

### Blade & UI
- **Appstract Laravel Blade Directives**: ^0.4.4

### Development Dependencies
- **Barryvdh Laravel Debugbar**: ^3.9
- **Barryvdh Laravel IDE Helper**: ^3.0
- **FakerPHP Faker**: ^1.23
- **Filp Whoops**: ^2.0
- **FriendsOfPHP PHP CS Fixer**: ^3.0
- **Mockery**: ^1.5
- **Nunomaduro Collision**: ^8.6
- **PestPHP Pest**: ^3.0
- **PHPUnit**: ^11.0

---

## JavaScript/Node.js Dependencies (package.json)

### Build Tools
- **Vite**: ^5.4.0
- **Laravel Vite Plugin**: ^1.0
- **Sass**: ^1.77.0

### Frontend Framework
- **Vue**: ^2.7.14
- **Vue Template Compiler**: ^2.7.14
- **@vitejs/plugin-vue2**: ^2.3.1
- **@vue/test-utils**: ^1.0.0-beta.10

### UI Libraries & Components
- **@coreui/coreui**: ^2.0.4
- **Bootstrap**: ^4.6.0
- **Font Awesome**: ^4.7.0
- **@fortawesome/fontawesome-svg-core**: ^1.2.2
- **@fortawesome/free-brands-svg-icons**: ^5.2.0
- **@fortawesome/free-regular-svg-icons**: ^5.2.0
- **@fortawesome/free-solid-svg-icons**: ^5.2.0
- **Simple Line Icons**: ^2.5.5

### JavaScript Libraries
- **Axios**: ^1.7.0
- **jQuery**: ^3.7.1
- **jQuery UI Dist**: ^1.12.1
- **Lodash**: ^4.17.21
- **Popper.js**: ^1.16.1

### UI Enhancements
- **Pace.js**: ^1.2.4
- **Perfect Scrollbar**: ^1.5.0
- **SweetAlert2**: ^11.10.0

---

## Upgrade Checklist

### Before Upgrading

- [ ] Review Laravel upgrade guide: https://laravel.com/docs/12.x/upgrade
- [ ] Check PHP version compatibility (currently requires PHP 8.3+)
- [ ] Review breaking changes in Laravel 12.x
- [ ] Backup database and application files
- [ ] Review all third-party package compatibility
- [ ] Test in staging environment first

### PHP/Composer Upgrades

#### Critical Dependencies to Monitor
1. **Laravel Framework** (^12.0)
   - Check for Laravel 13.x release and compatibility
   - Review migration guide when upgrading major versions

2. **Laravel Passport** (^12.0)
   - Must match Laravel version
   - Check OAuth2 compatibility

3. **Spatie Packages**
   - Laravel Permission: ^6.0
   - Laravel Backup: ^9.0
   - Laravel HTML: ^3.0
   - Laravel Newsletter: ^5.0
   - Ensure all Spatie packages are compatible with Laravel version

4. **Payment Gateways**
   - Stripe PHP: Currently supports multiple versions (^10.0|^11.0|^12.0|^13.0)
   - Razorpay: 2.* (check for 3.x release)

5. **DataTables**
   - Laravel DataTables Oracle: ^12.0
   - Laravel DataTables Buttons: ^12.0
   - Ensure compatibility with Laravel version

### JavaScript/Node.js Upgrades

#### Critical Dependencies to Monitor
1. **Vue.js** (^2.7.14)
   - Vue 2 is in maintenance mode
   - Consider migration to Vue 3 for long-term support
   - Migration guide: https://v3-migration.vuejs.org/

2. **Vite** (^5.4.0)
   - Check for Vite 6.x release
   - Review breaking changes

3. **Bootstrap** (^4.6.0)
   - Bootstrap 4 is outdated
   - Consider upgrading to Bootstrap 5.x
   - Review migration guide: https://getbootstrap.com/docs/5.3/migration/

4. **jQuery** (^3.7.1)
   - jQuery 3.x is current
   - Consider reducing jQuery dependency for modern JavaScript

5. **Font Awesome** (^4.7.0)
   - Font Awesome 4 is very outdated
   - Consider upgrading to Font Awesome 6.x
   - Review migration guide: https://fontawesome.com/docs/web/setup/upgrade

---

## Upgrade Commands

### PHP/Composer Upgrades

```bash
# Check for outdated packages
composer outdated

# Update all packages to latest compatible versions
composer update

# Update specific package
composer update vendor/package-name

# Update Laravel framework
composer update laravel/framework

# Check for security vulnerabilities
composer audit
```

### JavaScript/Node.js Upgrades

```bash
# Check for outdated packages (npm)
npm outdated

# Check for outdated packages (pnpm)
pnpm outdated

# Update all packages to latest compatible versions (npm)
npm update

# Update all packages (pnpm)
pnpm update

# Update specific package
npm update package-name
# or
pnpm update package-name

# Check for security vulnerabilities (npm)
npm audit

# Check for security vulnerabilities (pnpm)
pnpm audit
```

---

## Version Compatibility Matrix

### PHP Version Support
- **Current**: PHP 8.3+
- **Laravel 12.x**: Requires PHP 8.2+
- **Recommended**: PHP 8.3 or PHP 8.4

### Laravel Version Support
- **Current**: Laravel 12.x
- **Status**: Latest stable release
- **Next Major**: Laravel 13.x (when released)

### Node.js Version Support
- **Recommended**: Node.js 18.x or 20.x LTS
- **Vite 5.x**: Requires Node.js 18+ or 20+

---

## Breaking Changes to Watch

### Laravel 12.x
- Review Laravel 12.x upgrade guide
- Check for deprecated features
- Review route changes
- Check middleware changes

### Vue 2 → Vue 3 Migration (Future)
- Component API changes
- Composition API introduction
- Build tool changes
- Plugin compatibility

### Bootstrap 4 → Bootstrap 5 Migration (Future)
- Class name changes
- JavaScript API changes
- Grid system updates
- Component structure changes

---

## Security Considerations

### Regular Security Updates
- Run `composer audit` regularly
- Run `npm audit` or `pnpm audit` regularly
- Subscribe to Laravel security advisories
- Monitor package security releases

### Critical Security Packages
- Laravel Framework (security patches)
- Laravel Passport (OAuth security)
- Stripe PHP (payment security)
- Guzzle HTTP (HTTP client security)

---

## Testing After Upgrades

### PHP/Composer Upgrades
```bash
# Run PHPUnit tests
composer phpunit

# Run Pest tests
./vendor/bin/pest

# Check code style
composer format
```

### JavaScript/Node.js Upgrades
```bash
# Build assets
npm run build
# or
pnpm build

# Run development server
npm run dev
# or
pnpm dev

# Test production build
npm run preview
# or
pnpm preview
```

---

## Maintenance Schedule Recommendations

### Weekly
- Check for security updates: `composer audit` and `npm audit`

### Monthly
- Review outdated packages: `composer outdated` and `npm outdated`
- Update patch versions for security fixes

### Quarterly
- Review minor version updates
- Test compatibility in staging

### Annually
- Plan major version upgrades
- Review and update deprecated packages
- Consider framework upgrades (Laravel, Vue, Bootstrap)

---

## Notes

- This project uses Laravel 12.x with PHP 8.3+
- Vue 2.7 is in maintenance mode - consider Vue 3 migration
- Bootstrap 4 is outdated - consider Bootstrap 5 migration
- Font Awesome 4 is very outdated - consider Font Awesome 6 migration
- All payment gateway integrations should be tested after upgrades
- Database migrations should be reviewed before Laravel upgrades

---

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Upgrade Guide](https://laravel.com/docs/upgrade)
- [Vue.js Documentation](https://vuejs.org/)
- [Vue 3 Migration Guide](https://v3-migration.vuejs.org/)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Vite Documentation](https://vitejs.dev/)

---

**Last Updated**: January 30, 2026
**Maintained By**: Development Team
