# Audit Report: TrekAdvisor Architecture

As a Senior Laravel Architect, I have conducted a mandatory audit of the current codebase. Below are the identified structural issues that violate standard Laravel practices and the specific project rules.

## 1. Identified Violations

### Duplicate Layouts & Patterns
- **The "Matryoshka" Layout**: `admin/dashboard.blade.php` is wrapping a full sidebar/header structure inside `<x-app-layout>`. This is redundant and makes the `app-layout`'s own navigation (`layouts.navigation`) unnecessary for admins, yet it still loads in the background.
- **Inconsistent Dashboards**: 
    - `Admin`: Uses a custom sidebar and header build.
    - `Staff`: Uses the standard Breeze top-nav layout.
    - `Customer/Hotel`: Defaulting to standard Breeze layouts without a unified admin experience.

### File Duplication
- **Sidebar Mess**: `resources/views/admin/partials/sidebar.blade.php` is a standalone partial that only serves the admin. This violates the "ONE dashboard layout" rule which should handle menus for all roles (Admin, Staff, Hotel, Customer).

### Non-Standard Practices
- **Manual Header Blocks**: The admin dashboard manually defines its `<header>` and toggle scripts instead of utilizing the layout component's capabilities.
- **Hardcoded Styles**: While using Tailwind, some specific hex codes and patterns are repeated across views instead of being abstracted into a layout or a single `custom.css`.

## 2. Proposed Architectural Correction (Task 2)

To align with Viva-safe, beginner-friendly architecture, we will implement:

1.  **`x-app-layout`**: Purely for public-facing or simple auth pages (Login, Register, Welcome).
2.  **`x-dashboard-layout`**: A high-end, single-source component containing:
    - The Slate-gray sidebar.
    - The top header with profile dropdown.
    - **@switch/if** logic to show correct menu items based on `auth()->user()->role`.
3.  **Cleanup**: Delete `resources/views/admin/partials/sidebar.blade.php` and any role-specific sidebars.

---

**Next Steps**: I am ready to generate the two required layout components and refactor all dashboards to use `<x-dashboard-layout>`.
