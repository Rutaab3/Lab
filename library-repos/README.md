# Library Repositories

This directory contains the **full source code repositories** for all JavaScript and CSS libraries used in the Lab Automation project.

## Purpose

While the `/libraries/` directory contains only the production-ready minified files used in the application, this `/library-repos/` directory contains the complete source code repositories including:

- Full source code (unminified)
- Build scripts and configuration
- Documentation and examples
- Tests and development tools
- Complete git history

## Cloned Repositories

### 1. GSAP (GreenSock Animation Platform)
- **GitHub**: https://github.com/greensock/GSAP
- **License**: Custom (GreenSock License)
- **Description**: Professional-grade JavaScript animation library
- **Used Version**: 3.12.5
- **Purpose**: Powers all animations and smooth transitions in the UI

### 2. Just-validate
- **GitHub**: https://github.com/horprogs/Just-validate
- **License**: MIT
- **Description**: Modern, promise-based validation library
- **Used Version**: Latest
- **Purpose**: Client-side form validation for login, registration, and data entry

### 3. Bootstrap
- **GitHub**: https://github.com/twbs/bootstrap
- **License**: MIT
- **Description**: Popular CSS framework for responsive design
- **Used Version**: 5.3.3
- **Purpose**: Core CSS framework for layout, components, and utilities

### 4. Bootstrap Icons
- **GitHub**: https://github.com/twbs/icons
- **License**: MIT
- **Description**: Official open source SVG icon library for Bootstrap
- **Used Version**: 1.13.1
- **Purpose**: Provides icon fonts used throughout the application UI

### 5. SweetAlert2
- **GitHub**: https://github.com/sweetalert2/sweetalert2
- **License**: MIT
- **Description**: Beautiful, responsive, customizable popup boxes
- **Used Version**: 11
- **Purpose**: User-friendly alerts, confirmations, and modal dialogs

## Directory Structure

```
library-repos/
├── GSAP/                 # Full GSAP repository
├── Just-validate/        # Full Just-validate repository
├── bootstrap/            # Full Bootstrap repository
├── bootstrap-icons/      # Full Bootstrap Icons repository
└── sweetalert2/          # Full SweetAlert2 repository
```

## Usage

### Viewing Source Code
Navigate to any repository to explore the source code:
```bash
cd library-repos/GSAP
# View source files, documentation, examples
```

### Updating Repositories
To pull the latest changes from the remote repositories:
```bash
cd library-repos/GSAP
git pull origin main

cd ../Just-validate
git pull origin master

cd ../bootstrap
git pull origin main

cd ../bootstrap-icons
git pull origin main

cd ../sweetalert2
git pull origin master
```

### Building from Source
Each repository contains its own build instructions. Refer to the individual README files:
- `library-repos/GSAP/README.md`
- `library-repos/Just-validate/README.md`
- `library-repos/bootstrap/README.md`
- `library-repos/bootstrap-icons/README.md`
- `library-repos/sweetalert2/README.md`

## Important Notes

### Production vs. Development
- **`/libraries/`** - Contains only production-ready minified files used by the application
- **`/library-repos/`** - Contains full source repositories for reference and development

### Git Ignore
The `.gitignore` file should exclude `library-repos/` from the main project repository to avoid:
- Massive repository size (these repos are large)
- Nested git repositories causing conflicts
- Unnecessary duplication (these are already on GitHub)

Add to `.gitignore`:
```
library-repos/
```

### When to Use Each Directory

**Use `/libraries/`:**
- When including scripts/styles in production
- For deploying to production servers
- When you need minified, optimized files

**Use `/library-repos/`:**
- When studying library internals
- When debugging library-specific issues
- When exploring advanced features
- For learning and reference

## Maintenance

### Checking for Updates
Periodically check if newer versions are available:
```bash
cd library-repos/GSAP && git fetch && git status
cd ../Just-validate && git fetch && git status
cd ../bootstrap && git fetch && git status
cd ../bootstrap-icons && git fetch && git status
cd ../sweetalert2 && git fetch && git status
```

### Upgrading Production Libraries
When upgrading a library in `/libraries/`:
1. Check the changelog in the corresponding `/library-repos/` directory
2. Review breaking changes and new features
3. Download the new minified production files
4. Update files in `/libraries/`
5. Test thoroughly before deploying

## Repository Information

### Clone Commands
If you need to re-clone any repository:

```bash
# GSAP
git clone https://github.com/greensock/GSAP.git library-repos/GSAP

# Just-validate
git clone https://github.com/horprogs/Just-validate.git library-repos/Just-validate

# Bootstrap
git clone https://github.com/twbs/bootstrap.git library-repos/bootstrap

# Bootstrap Icons
git clone https://github.com/twbs/icons.git library-repos/bootstrap-icons

# SweetAlert2
git clone https://github.com/sweetalert2/sweetalert2.git library-repos/sweetalert2
```

## License Compliance

All libraries used in this project are open source with permissive licenses (MIT or custom). Ensure compliance by:
- Retaining original license files
- Providing attribution where required
- Following license terms for commercial use

## Resources

### Official Documentation
- **GSAP**: https://greensock.com/docs/
- **Just-validate**: https://just-validate.dev/
- **Bootstrap**: https://getbootstrap.com/docs/
- **Bootstrap Icons**: https://icons.getbootstrap.com/
- **SweetAlert2**: https://sweetalert2.github.io/

### Community
- **GSAP Forum**: https://greensock.com/forums/
- **Bootstrap Forum**: https://discourse.getbootstrap.com/
- **Stack Overflow**: Tag searches for each library

## Conclusion

This directory serves as a comprehensive reference for all third-party libraries used in the Lab Automation project. Having the full repositories locally enables deep understanding, debugging, and contribution to these open-source projects.

---

**Last Updated**: February 11, 2026  
**Total Repositories**: 5  
**Total Size**: Varies (can be several hundred MB combined)
