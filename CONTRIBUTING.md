# Contributing Guidelines

Thank you for your interest in contributing to the Lab Automation System. This document provides guidelines and workflows to ensure smooth collaboration and high software quality.

---

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Core Contributors](#core-contributors)
- [How to Contribute](#how-to-contribute)
  - [Reporting Issues](#reporting-issues)
  - [Feature Requests](#feature-requests)
  - [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
  - [PHP Guidelines](#php-guidelines)
  - [Database and SQL Guidelines](#database-and-sql-guidelines)
  - [Frontend Guidelines](#frontend-guidelines)
- [Pull Request Checklist](#pull-request-checklist)
- [License Notice](#license-notice)

---

## Code of Conduct

We are committed to providing a welcoming, inclusive, and professional environment for all contributors. Please treat everyone with respect and courtesy regardless of background or experience level.

---

## Core Contributors

The project maintainers and key contributors are listed below:

| Name | Role | Email Contact |
| :--- | :--- | :--- |
| **Muhammad Rutaab Ali** | Project Lead / Owner | [rutaabali3@gmail.com](mailto:rutaabali3@gmail.com) |
| **Muhammad Bilal** | Core Developer | [bilaljaseem207@gmail.com](mailto:bilaljaseem207@gmail.com) |
| **Abdul Rehman Khan** | Core Developer | [ar9205618@gmail.com](mailto:ar9205618@gmail.com) |
| **Muhammad Toaha Yaseen** | Core Developer | [strikergame505@gmail.com](mailto:strikergame505@gmail.com) |

---

## How to Contribute

### Reporting Issues

Before creating a new issue, search existing issues to see if it has already been reported. When submitting a bug report, please include:

1. **Title**: Clear and concise description of the issue.
2. **Steps to Reproduce**: Detailed numbered steps to reproduce the behavior.
3. **Expected Behavior**: What you expected to happen.
4. **Actual Behavior**: What actually happened, including exact error messages or screenshots.
5. **Environment Details**: PHP version, MySQL version, browser version, OS, and web server stack (e.g. XAMPP Apache).

### Feature Requests

Enhancement suggestions are welcome. When requesting a feature:

1. Clearly outline the proposed functionality and its use case.
2. Explain why this feature would benefit users or developers of the Lab Automation System.
3. Describe potential implementation strategies or architectural considerations if applicable.

### Development Workflow

Follow this standard Git workflow for all modifications:

1. **Fork the Repository**: Create a fork of the main repository on GitHub.
2. **Clone Your Fork**:
   ```bash
   git clone https://github.com/YOUR-USERNAME/Lab.git
   cd Lab
   ```
3. **Create a Feature Branch**:
   ```bash
   git checkout -b feature/descriptive-feature-name
   # or for bug fixes:
   git checkout -b fix/descriptive-bug-name
   ```
4. **Environment Setup**:
   - Install XAMPP or configure an equivalent Apache/PHP/MySQL web server.
   - Import `docs2/lab_automation.sql` into MySQL.
   - Configure database credentials in `config/db.php`.
5. **Implement Changes**: Make clean, well-tested code modifications.
6. **Commit Changes**: Follow semantic commit conventions:
   ```bash
   git commit -m "feat(auth): implement two-factor session verification"
   ```
7. **Push to Your Fork**:
   ```bash
   git push origin feature/descriptive-feature-name
   ```
8. **Submit a Pull Request**: Open a PR targeting the `main` branch with a comprehensive description of changes made.

---

## Coding Standards

### PHP Guidelines

- Follow PSR-12 coding style guidelines.
- Use explicit variable naming in camelCase or snake_case consistently within module scope.
- Enforce strict authentication and authorization checks using `config/guard.php` or `config/auth.php` helpers at the top of every sensitive endpoint.
- Always use prepared statements (`PDO` or `mysqli`) with parameterized queries to prevent SQL injection vulnerabilities.
- Escape user input before rendering in HTML templates using `htmlspecialchars()`.

### Database and SQL Guidelines

- Keep table names lowercase using standard snake_case (e.g., `system_settings`, `report_templates`).
- Always define proper foreign key constraints and indexes on frequently queried fields.
- Document any schema changes by updating both `docs2/lab_automation.sql` and `docs2/changelog.md`.

### Frontend Guidelines

- Maintain responsive layouts using Bootstrap grid classes.
- Keep custom CSS organized within the `css/` directory.
- Utilize SweetAlert2 for interactive modals and alerts rather than default browser alerts.
- Ensure all forms use validation scripts (such as Just Validate) on the client side in addition to server-side validation.

---

## Pull Request Checklist

Before submitting a Pull Request, verify the following:

- [ ] Code compiles and runs without PHP warnings, errors, or notice logs.
- [ ] Database updates (if any) are included in `docs2/lab_automation.sql`.
- [ ] Security checks (prepared statements, authorization guards, input sanitization) are in place.
- [ ] All new and existing features pass manual functionality testing.
- [ ] Commit history is clean, descriptive, and squashed if necessary.
- [ ] No emojis or non-standard characters are added to documentation or source code comments.

---

## License Notice

By contributing to this repository, you agree that your contributions will be licensed under the project's [MIT License](LICENSE.md).
