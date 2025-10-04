# Event Management Website - Project Structure

## Overview
The project has been restructured into a clean, organized folder structure following modern PHP development practices.

## New Directory Structure

```
EventManagementWebsite/
├── admin/                      # Admin panel files
│   ├── dashboard.php           # Admin dashboard
│   ├── createevent.php         # Create event form
│   ├── createeventsave.php     # Event creation handler
│   ├── editevent.php          # Edit event form
│   ├── edit_event.php         # Event edit handler
│   ├── createuser.php         # Create user form
│   ├── createusersave.php     # User creation handler
│   ├── deleteevent.php        # Event deletion handler
│   ├── closedevent.php        # Closed events page
│   ├── closedeventpublish.php # Publish closed events
│   ├── publish&unpublish.php  # Toggle event publication
│   ├── report.php             # Reports page
│   └── eventreport.php        # Event-specific reports
│
├── api/                        # AJAX/API endpoints
│   ├── event_more.php         # Load more events (infinite scroll)
│   ├── feedbacksubmit.php     # Feedback submission handler
│   ├── galleryupload.php      # Gallery image upload
│   ├── gallerysorting.php     # Gallery sorting handler
│   └── upload.php             # General file upload handler
│
├── app/
│   └── views/                 # All view/template files
│       ├── index.php          # Homepage
│       ├── aboutus.php        # About page (public)
│       ├── aboutusAfterLogIn.php # About page (logged in)
│       ├── eventpage.php      # Event details page
│       ├── eventregistration.php # Event registration form
│       ├── eventregistration1.php # Alternative registration
│       ├── feedback.php       # Feedback page
│       ├── feedbacknotification.php # Feedback notifications
│       ├── gallery.php        # Gallery page
│       ├── notification.php   # User notifications
│       ├── stripepayment.php  # Stripe payment page
│       ├── success.php        # Payment success page
│       ├── success1.php       # Alternative success page
│       ├── successfullyregisterd.php  # Registration success
│       ├── successfullyregisterd1.php # Alt registration success
│       ├── chat.php           # Chat page
│       ├── chat.html          # Chat HTML template
│       └── new.php            # New features page
│
├── auth/                       # Authentication files
│   ├── login.php              # Login form
│   ├── log_in.php             # Login handler
│   ├── signup.php             # Signup form
│   ├── sign_up.php            # Signup handler
│   └── log_out.php            # Logout handler
│
├── config/                     # Configuration files
│   ├── config.php             # Main configuration
│   ├── dbconnect.php          # Database connection
│   ├── stripe_config.php      # Stripe configuration
│   └── paths.php              # Path definitions & helpers
│
├── database/                   # Database files
│   └── schema.sql             # Database schema
│
├── design_files/              # Design source files
│   ├── AllEvents.bsdesign
│   ├── createevent.bsdesign
│   ├── EventPage.bsdesign
│   └── HomeRedefined.bsdesign
│
├── public/                     # Public assets
│   ├── assets/                # Bootstrap & other frameworks
│   │   ├── bootstrap/
│   │   ├── css/
│   │   ├── fonts/
│   │   └── js/
│   ├── css/                   # Custom CSS files
│   ├── images/                # Image files
│   ├── js/                    # JavaScript files
│   ├── slick/                 # Slick carousel library
│   ├── gallery/               # Gallery images
│   ├── uploads/               # User uploaded files
│   ├── install.php            # Installation script
│   ├── test_gd.php            # GD extension test
│   └── test_installation.php # Installation test
│
├── vendor/                     # Composer dependencies
│   └── stripe-php/            # Stripe PHP library
│
├── .env                       # Environment variables
├── .htaccess                  # URL rewriting rules
├── index.php                  # Entry point
├── start.bat                  # Windows startup script
├── start.sh                   # Linux/Mac startup script
└── README.md                  # Project documentation
```

## Key Features of New Structure

### 1. **Organized by Purpose**
   - **admin/**: All administrative functionality
   - **api/**: All AJAX endpoints
   - **auth/**: All authentication logic
   - **config/**: All configuration files
   - **app/views/**: All user-facing pages
   - **public/**: All static assets

### 2. **URL Rewriting**
   - `.htaccess` handles automatic URL rewriting
   - Old URLs still work (e.g., `login.php` → `auth/login.php`)
   - Transparent to users and existing code

### 3. **Path Helpers**
   - `config/paths.php` provides helper functions
   - `asset()` function for asset URLs
   - `url()` function for page URLs
   - Centralized path definitions

### 4. **Clean Root Directory**
   - Only essential files in root
   - All assets in `public/`
   - All code in organized folders
   - Design files in separate folder

## File Path Updates

All PHP files have been updated to use correct relative paths:

```php
// Database connection
require_once __DIR__ . '/../config/dbconnect.php';

// Configuration
require_once __DIR__ . '/../config/config.php';

// Assets (in HTML/views)
<link rel="stylesheet" href="public/assets/bootstrap/css/bootstrap.min.css">
<script src="public/js/global.js"></script>
<img src="public/images/logo.png">
```

## Backward Compatibility

The `.htaccess` file ensures backward compatibility:
- Old URLs automatically redirect to new locations
- No broken links
- Seamless transition for users

## Development Workflow

### Starting the Server
```bash
# Windows
start.bat

# Linux/Mac
./start.sh
```

### File Organization Rules
1. **Views** → `app/views/`
2. **Admin pages** → `admin/`
3. **API endpoints** → `api/`
4. **Auth pages** → `auth/`
5. **Config files** → `config/`
6. **Static assets** → `public/`

## Testing

The restructured application has been tested and confirmed working:
- ✅ Homepage loads successfully
- ✅ All paths updated correctly
- ✅ Database connections working
- ✅ No errors in server logs
- ✅ Assets loading correctly

## Benefits

1. **Maintainability**: Easier to find and update files
2. **Security**: Config files separated from public access
3. **Scalability**: Clear structure for future growth
4. **Standards**: Follows modern PHP project conventions
5. **Clean**: Root directory is uncluttered

## Migration Notes

- 23 files had their paths automatically updated
- All asset references updated to `public/` prefix
- Design files moved to `design_files/` folder
- Temporary/backup files cleaned up
- Original functionality preserved

---

**Last Updated**: October 4, 2025
**Status**: ✅ Complete and Tested
