# Shopware 6.7 Migration Checklist: TopdataDemoshopSwitcherSW6

**Plugin Version:** 1.0.1 -> 1.1.0 (Target)
**Target Shopware Version:** 6.7.*

## I. Pre-Migration Setup (Manual Actions)

*   [ ] **Environment:** Shopware 6.7 development environment set up and running PHP 8.2+.
*   [ ] **Version Control:** New Git branch created (e.g., `feat/sw6.7-compatibility`).

## II. Code & Configuration Updates (To be performed by AI Agent)

### A. `composer.json`
*   [x] **File:** `composer.json`
    *   [x] Update `require."shopware/core"` to include `^6.7.0`.
    *   [x] Add/Update `require.php` to `>=8.2`.
    *   [x] Increment `version` to `1.1.0`.

### B. PHP Files Review & Potential Adjustments
*   **Files to Review:**
    *   `src/Service/DemoshopSwitcherService.php`
    *   `src/Subscriber/MyEventSubscriber.php`
    *   `src/TopdataDemoshopSwitcherSW6.php`
*   [x] **PHP 8.2+ Compatibility:** Review code for any deprecated features or syntax changes. *(Anticipated: No changes needed for this plugin)*.
*   [x] **Shopware Core API Usage:**
    *   [x] Verify `SystemConfigService::get()` usage. *(Anticipated: No changes needed)*.
    *   [x] Verify `RequestStack` methods (`getMainRequest()`, `getUri()`) usage. *(Anticipated: No changes needed)*.
    *   [x] Verify `GenericPageLoadedEvent` usage (`getPage()->assign()`). *(Anticipated: No changes needed)*.

### C. Storefront Twig Template Review
*   **File:** `src/Resources/views/storefront/layout/header/top-bar.html.twig`
*   [x] **Twig Syntax/Filters:** Review for deprecated Twig features. *(Anticipated: No changes needed)*.
*   [x] **Parent Template Compatibility:** Confirm `{% sw_extends %}` and block structure remain valid. *(Anticipated: No changes needed)*.

### D. Configuration Files Review
*   **Files:**
    *   `src/Resources/config/config.xml`
    *   `src/Resources/config/services.xml`
*   [x] **`config.xml` Schema:** Verify schema compatibility. *(Anticipated: No changes needed)*.
*   [x] **`services.xml` Syntax:** Verify service definition syntax. *(Anticipated: No changes needed)*.

### E. Documentation Updates
*   **File:** `CHANGELOG.md`
    *   [x] Add new entry for version `1.1.0` detailing Shopware 6.7 compatibility and PHP 8.2+ support.

## III. CI/CD Workflow Review (Separate Task - For Maintainers)

*   **File:** `.github/workflows/update-demoshops.yaml`
*   [ ] Review if any adjustments are needed for demo shop URLs or internal processes. *(No direct code change for plugin compatibility itself)*.

## IV. Post-Migration Testing & Finalization (Manual or Automated)

*   [ ] **Dependencies:** Run `composer update` and ensure successful resolution.
*   [ ] **Installation:** Plugin installs and activates successfully on Shopware 6.7.
*   [ ] **Configuration:**
    *   [ ] Plugin configuration page loads correctly.
    *   [ ] Saving configuration works as expected.
*   [ ] **Storefront Functionality:**
    *   [ ] Switcher buttons appear in the top bar.
    *   [ ] Buttons have correct labels and tooltips.
    *   [ ] `active` class is correctly applied to the current shop's button.
    *   [ ] Clicking buttons redirects to the correct domain, preserving path and query parameters.
    *   [ ] Test with various URL types (homepage, category, product, checkout).
    *   [ ] Test default domain behavior (if configuration is empty).
*   [ ] **Error Checks:**
    *   [ ] No JavaScript errors in the browser console related to the plugin.
    *   [ ] No plugin-related errors or warnings in Shopware's `var/log`.
*   [ ] **Version Control:**
    *   [ ] Commit all changes to the `feat/sw6.7-compatibility` branch.
    *   [ ] (Human) Review and merge the branch.
    *   [ ] (Human) Create a new Git tag (e.g., `v1.1.0`).
*   [ ] **Release:**
    *   [ ] (Human) Build the plugin .zip file for distribution.

