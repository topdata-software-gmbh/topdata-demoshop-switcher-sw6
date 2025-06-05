# Migration Checklist: Replace Event Subscriber with Twig Extension

## Implementation Tasks
- [x] Create Twig extension class at `src/Twig/DemoshopSwitcherTwigExtension.php`
- [x] Update `src/Resources/config/services.xml`:
  - [x] Register Twig extension service
  - [x] Remove event subscriber service
- [x] Update template `src/Resources/views/storefront/layout/header/top-bar.html.twig`:
  - [x] Replace `page.getExtension()` call with `getShopDomains()` function
- [x] Remove obsolete files:
  - [x] Delete `src/Subscriber/MyEventSubscriber.php`

## Verification Tasks
- [ ] Clear Shopware cache (`bin/console cache:clear`)
- [ ] Verify switcher appears correctly in storefront
- [ ] Test all domain switching functionality
- [ ] Check for errors in logs (`var/log/production.log`)
- [ ] Confirm correct URL generation for all domains
- [ ] Test with multiple domain configurations

## Rollback Preparation
- [ ] Note original state of `services.xml`
- [ ] Backup `MyEventSubscriber.php` before deletion