# Migration Plan: TopdataDemoshopSwitcherSW6 to Shopware 6.7

**Plugin Name:** `topdata/topdata-demoshop-switcher-sw6`
**Current Version (from composer.json):** 1.0.1
**Target Shopware Version:** 6.7.*

## 1. Executive Summary

This plugin, `TopdataDemoshopSwitcherSW6`, adds toggle buttons to the storefront header for switching between different configured demoshop domains. It does not include custom Administration UI components. Therefore, the major frontend-related breaking changes in Shopware 6.7 (Vite, Vue 3, Pinia) are **not applicable**.

The migration will focus on:
1.  Updating `composer.json` to declare compatibility with Shopware 6.7 and reflect the PHP 8.2+ requirement.
2.  Reviewing PHP code for compatibility with PHP 8.2+ and any minor API changes in Shopware Core services used (primarily `SystemConfigService` and `RequestStack`).
3.  Verifying the Storefront Twig template extension for compatibility.
4.  Updating the plugin version and changelog.
5.  Ensuring thorough testing on a Shopware 6.7 instance.

## 2. Pre-Migration Setup (Manual Steps Recommended)

*   [ ] **Action (Human):** Ensure a Shopware 6.7 development environment is available, running PHP 8.2 or newer.
*   [ ] **Action (Human):** Create a new branch in the plugin's Git repository for this migration (e.g., `feat/sw6.7-compatibility`).

## 3. Detailed Migration Steps

### Step 3.1: Update `composer.json`

**File:** `composer.json`

**Rationale:**
*   Declare compatibility with Shopware 6.7.
*   Align with Shopware 6.7's PHP 8.2+ requirement.
*   Increment the plugin version.

**Changes:**

1.  **Update `shopware/core` dependency:**
    *   Current: `"shopware/core": "6.4.* || 6.5.* || 6.6.*"`
    *   Target: `"shopware/core": "6.4.* || 6.5.* || 6.6.* || ^6.7.0"`
2.  **Add/Update PHP version requirement (recommended for clarity):**
    *   If `require.php` is not present, add it. If present and lower, update it.
    *   Target: `"php": ">=8.2"`
3.  **Increment plugin version:**
    *   Current: `"version": "1.0.1"`
    *   Target: `"version": "1.1.0"` (or a suitable semantic version for this update)

**Action for AI:**
*   Modify `composer.json` with the following content for the `require` and `version` fields:
    ```json
    {
        "name": "topdata/topdata-demoshop-switcher-sw6",
        "description": "Toggle Buttons to switch between different Demoshops",
        "version": "1.1.0", // Updated
        "type": "shopware-platform-plugin",
        "license": "MIT",
        "authors": [
            {
                "name": "TopData Software GmbH",
                "homepage": "https://www.topdata.de",
                "role": "Manufacturer"
            }
        ],
        "require": {
            "php": ">=8.2", // Added/Updated
            "shopware/core": "6.4.* || 6.5.* || 6.6.* || ^6.7.0" // Updated
        },
        "extra": {
            "topdata-technical-documentation": "https://github.com/topdata-software-gmbh/topdata-demoshop-switcher-sw6/blob/main/README.md",
            "topdata-user-documentation": null,
            "shopware-plugin-class": "Topdata\\TopdataDemoshopSwitcherSW6\\TopdataDemoshopSwitcherSW6",
            "plugin-icon": "src/Resources/config/icon-topdata-demoshop-switcher-sw6-256x256.png",
            "label": {
                "de-DE": "Topdata Demoshop Switcher",
                "en-GB": "Topdata Demoshop Switcher"
            },
            "description": {
                "de-DE": "Toggle Buttons zum Wechseln zwischen verschiedenen Demoshops.",
                "en-GB": "Toggle Buttons for switching between different Demoshops."
            },
            "manufacturerLink": {
                "de-DE": "https://www.topdata.de",
                "en-GB": "https://www.topdata.de"
            },
            "supportLink": {
                "de-DE": "https://www.topdata.de",
                "en-GB": "https://www.topdata.de"
            }
        },
        "autoload": {
            "psr-4": {
                "Topdata\\TopdataDemoshopSwitcherSW6\\": "src/"
            }
        }
    }
    ```

### Step 3.2: PHP Code Review and Potential Adjustments

**Files to Review:**
*   `src/Service/DemoshopSwitcherService.php`
*   `src/Subscriber/MyEventSubscriber.php`
*   `src/TopdataDemoshopSwitcherSW6.php`

**Rationale:**
*   Ensure compatibility with PHP 8.2+ syntax and features.
*   Verify that used Shopware Core services and events have not undergone breaking changes relevant to this plugin's usage.

**Checks:**

1.  **PHP 8.2+ Compatibility:**
    *   The existing code is modern and unlikely to have direct conflicts with PHP 8.2 features (e.g., no dynamic properties that would require `#[AllowDynamicProperties]`, no deprecated string interpolations).
    *   The usage of `$_SERVER['REQUEST_URI']` in `DemoshopSwitcherService.php` is a known pattern and should continue to work.
2.  **Shopware Core Services & Events:**
    *   `SystemConfigService`: The `get()` method is fundamental and unlikely to change its signature in a breaking way for simple string retrieval.
    *   `RequestStack`: `getMainRequest()` and `getUri()` are stable.
    *   `GenericPageLoadedEvent`: `getPage()->assign()` is a standard way to add data to Storefront pages and is expected to remain stable.
    *   The migration document does not indicate breaking changes for these specific, basic usages.

**Action for AI:**
*   **No specific code changes are anticipated for these PHP files based on the plugin's current implementation and the provided Shopware 6.7 migration document.**
*   The AI should primarily ensure that its understanding of PHP 8.2 syntax doesn't flag any false positives in the existing code.
*   If the AI's knowledge base contains specific deprecations or minor signature changes for `SystemConfigService::get()`, `RequestStack::getMainRequest()`, `Request::getUri()`, or `GenericPageLoadedEvent::getPage()->assign()` that are not covered in the provided migration document, it should apply them. However, this is highly unlikely for this plugin's usage.

### Step 3.3: Storefront Twig Template Review

**File:** `src/Resources/views/storefront/layout/header/top-bar.html.twig`

**Rationale:**
*   Ensure the Twig template extension mechanism and syntax remain valid in Shopware 6.7.

**Checks:**

1.  **Twig Syntax and Filters:** The template uses basic Twig features (`{% sw_extends %}`, `{{ parent() }}`, `{% for %}`, `{{ variable.property }}`, `{% if %}` conditional classes) which are standard and stable.
2.  **Parent Template Structure:** The block `layout_header_top_bar` in `@Storefront/storefront/layout/header/top-bar.html.twig` is a core structure and is highly unlikely to be removed or drastically altered in a way that breaks this simple extension.
3.  **Variable Availability:** The `page.TopdataDemoshopSwitcherSW6_shopDomains` variable is assigned by the plugin's subscriber and its availability depends on the subscriber working correctly.

**Action for AI:**
*   **No specific code changes are anticipated for this Twig file.**

### Step 3.4: Configuration Files Review

**Files:**
*   `src/Resources/config/config.xml`
*   `src/Resources/config/services.xml`

**Rationale:**
*   Ensure plugin configuration schema and service definitions remain valid.

**Checks:**

1.  **`config.xml`:** The schema for basic input fields like `textarea` is stable. The `xsi:noNamespaceSchemaLocation` points to the trunk, which implies forward compatibility for stable elements.
2.  **`services.xml`:** Symfony service definition syntax for autowiring and tagging event subscribers is stable.

**Action for AI:**
*   **No specific code changes are anticipated for these configuration files.**

### Step 3.5: Update `CHANGELOG.md`

**File:** `CHANGELOG.md`

**Rationale:**
*   Document the changes made for the new version.

**Action for AI:**
*   Prepend the following entry to `CHANGELOG.md`:
    ```markdown
    ## [1.1.0] - YYYY-MM-DD
    - Added compatibility with Shopware 6.7.
    - Ensured PHP 8.2+ compatibility (primarily via `composer.json` update).
    ```
    *(AI should replace YYYY-MM-DD with the current date or a placeholder if the date is not known).*

### Step 3.6: CI/CD Workflow (Review - No direct code change for plugin)

**File:** `.github/workflows/update-demoshops.yaml`

**Rationale:**
*   This workflow is for internal use (updating demo shops) and not part of the plugin's distributable code. Its functionality related to fetching the composer name will remain unaffected by the plugin's internal changes.

**Action for AI:**
*   **No changes required to this file as part of the plugin's Shopware 6.7 compatibility update.** (Maintainers may review it separately if demo shop URLs or SW versions need updating for their internal processes).

## 4. Post-Migration Steps (Manual or Automated Testing)

*   [ ] **Action (Human/AI with testing capabilities):** Run `composer update` in the plugin directory or the Shopware root (if testing in a full setup) to ensure dependencies are resolved correctly.
*   [ ] **Action (Human/AI with testing capabilities):** Install and activate the updated plugin on a Shopware 6.7 instance.
*   [ ] **Action (Human/AI with testing capabilities):**
    *   Configure some domains in the plugin settings.
    *   Verify the switcher buttons appear correctly in the storefront top bar.
    *   Test clicking the buttons and ensure correct redirection (domain changes, path/query parameters preserved).
    *   Verify the "active" state of buttons.
    *   Check for any errors in Shopware logs or browser console.
    *   Test with default domains if plugin configuration is empty.

## 5. Summary of AI Actions

1.  Modify `composer.json` as specified in Step 3.1.
2.  Review PHP files listed in Step 3.2. (Anticipate no changes, but verify against PHP 8.2+ and core SW API knowledge).
3.  Review Twig file listed in Step 3.3. (Anticipate no changes).
4.  Review XML config files listed in Step 3.4. (Anticipate no changes).
5.  Update `CHANGELOG.md` as specified in Step 3.5.

This migration is expected to be straightforward due to the plugin's nature (no admin UI, simple service, and storefront template).

