# Libraries Documentation

This folder contains all the JavaScript and CSS libraries used in the Lab Automation project. All libraries have been downloaded locally to improve performance and reduce dependency on external CDNs.

## 📚 Libraries Included

### 1. GSAP (GreenSock Animation Platform) - v3.12.5
**Location:** `libraries/gsap/`
- `gsap.min.js` - Core GSAP animation library
- `ScrollTrigger.min.js` - GSAP ScrollTrigger plugin

**GitHub Repository:** https://github.com/greensock/GSAP
**Purpose:** Advanced JavaScript animations and scroll-triggered effects

---

### 2. Just Validate - Latest
**Location:** `libraries/just-validate/`
- `just-validate.production.min.js` - Form validation library

**GitHub Repository:** https://github.com/horprogs/Just-validate
**Purpose:** Client-side form validation

---

### 3. Bootstrap - v5.3.3
**Location:** `libraries/bootstrap/`
- `bootstrap.min.css` - Bootstrap CSS framework
- `bootstrap.bundle.min.js` - Bootstrap JavaScript (includes Popper.js)

**GitHub Repository:** https://github.com/twbs/bootstrap
**Purpose:** Responsive layout framework and UI components

---

### 4. Bootstrap Icons - v1.13.1
**Location:** `libraries/bootstrap-icons/`
- `bootstrap-icons.min.css` - Icon font CSS
- `fonts/bootstrap-icons.woff` - Icon font file (WOFF format)
- `fonts/bootstrap-icons.woff2` - Icon font file (WOFF2 format)

**GitHub Repository:** https://github.com/twbs/icons
**Purpose:** Icon library for UI elements

---

### 5. SweetAlert2 - v11
**Location:** `libraries/sweetalert2/`
- `sweetalert2.min.css` - SweetAlert2 styles
- `sweetalert2.all.min.js` - SweetAlert2 JavaScript (includes all features)

**GitHub Repository:** https://github.com/sweetalert2/sweetalert2
**Purpose:** Beautiful, customizable alert/modal dialogs

---

## 🔗 Integration

All libraries are integrated in `xtras/link.php` with the following structure:

```html
<!-- GSAP Animation Library -->
<script src="../libraries/gsap/gsap.min.js"></script>
<script src="../libraries/gsap/ScrollTrigger.min.js"></script>

<!-- Just Validate Library -->
<script src="../libraries/just-validate/just-validate.production.min.js"></script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="../libraries/bootstrap-icons/bootstrap-icons.min.css">

<!-- Bootstrap CSS & JS -->
<link rel="stylesheet" href="../libraries/bootstrap/bootstrap.min.css">
<script src="../libraries/bootstrap/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="../libraries/sweetalert2/sweetalert2.min.css">
<script src="../libraries/sweetalert2/sweetalert2.all.min.js"></script>
```

## 📁 Directory Structure

```
libraries/
├── gsap/
│   ├── gsap.min.js
│   └── ScrollTrigger.min.js
├── just-validate/
│   └── just-validate.production.min.js
├── bootstrap/
│   ├── bootstrap.min.css
│   └── bootstrap.bundle.min.js
├── bootstrap-icons/
│   ├── bootstrap-icons.min.css
│   └── fonts/
│       ├── bootstrap-icons.woff
│       └── bootstrap-icons.woff2
└── sweetalert2/
    ├── sweetalert2.min.css
    └── sweetalert2.all.min.js
```

## ✅ Benefits of Local Libraries

1. **Faster Load Times** - No external network requests required
2. **Offline Capability** - Works without internet connection
3. **Version Control** - Locked to specific versions, no unexpected updates
4. **Privacy** - No external tracking or CDN dependencies
5. **Reliability** - Not affected by CDN outages

## 🔄 Updating Libraries

To update any library:
1. Visit the library's GitHub repository (links above)
2. Download the latest version
3. Replace the corresponding file in the libraries folder
4. Test thoroughly to ensure compatibility

## 📝 Notes

- All files are minified production versions for optimal performance
- Bootstrap Icons includes font files for icon rendering
- Bootstrap bundle includes Popper.js for tooltips and popovers
- Downloaded on: February 11, 2026
