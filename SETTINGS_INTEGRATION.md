# Settings Integration - Complete Documentation

## ✅ All Dashboard Settings Are Now Applied to Website Views

### Overview
All settings configured in the admin dashboard (`/admin/settings`) are now automatically applied across the entire website, including frontend pages, navbar, footer, and meta tags.

---

## 📋 Implemented Settings Groups

### 1️⃣ **General Settings**
- ✅ **App Name** - Displayed in navbar, footer, page titles, and meta tags
- ✅ **App Logo** - Shows in navbar and footer (with fallback to default)
- ✅ **App Description** - Used in footer and meta descriptions
- ✅ **Timezone** - Applied application-wide
- ✅ **Currency** - Available for payment processing
- ✅ **Default Language** - Sets application locale
- ✅ **WhatsApp Number** - Used for WhatsApp floating button
- ✅ **Enable WhatsApp FAB** - Toggle floating WhatsApp button
- ✅ **Enable Lat/Long** - Control checkout fields
- ✅ **Enable Cash on Delivery** - Payment option toggle

### 2️⃣ **Appearance Settings**
- ✅ **Primary Color** - Applied throughout the website via CSS variables
- ✅ **Secondary Color** - Available for theme customization
- ✅ **Accent Color** - Available for highlights and CTAs
- ✅ **Dark Mode Enabled** - Toggle dark/light theme
- ✅ **Logo Width** - Control logo sizing

### 3️⃣ **Email Settings**
- ✅ **Mail Driver** (SMTP, Sendmail, Log)
- ✅ **SMTP Host, Port, Username, Password**
- ✅ **Mail Encryption** (TLS/SSL)
- ✅ **From Name & Address** - Email sender information
- ✅ **Contact Email** - Displayed in footer and contact pages
- ✅ **Support Email** - Displayed in footer
- ✅ **Order Notification Email** - For order alerts

### 4️⃣ **Payment Settings**
- ✅ **Payment Gateway** (PayPal/Stripe)
- ✅ **PayPal Mode** (Sandbox/Live)
- ✅ **PayPal Client ID & Secret**
- ✅ **Stripe API Key & Secret**
- ✅ **Enable Cash on Delivery**

### 5️⃣ **SEO Settings**
- ✅ **Meta Title** - Default page title
- ✅ **Meta Description** - Default page description
- ✅ **Meta Keywords** - Default keywords
- ✅ **Google Analytics ID** - Automatically injected tracking code
- ✅ **Robots.txt** - Search engine crawling rules
- ✅ **Open Graph Tags** - Social media sharing optimization

### 6️⃣ **Social Media Settings**
- ✅ **Facebook URL** - Footer social link
- ✅ **Twitter URL** - Footer social link
- ✅ **Instagram URL** - Footer social link
- ✅ **LinkedIn URL** - Footer social link
- ✅ **YouTube URL** - Footer social link
- ✅ **TikTok URL** - Footer social link
- Only active (non-empty) links are displayed

### 7️⃣ **API Settings**
- ✅ **Rate Limit** - API requests per minute
- ✅ **Timeout** - API request timeout
- ✅ **OpenAI API Key** - For AI features

### 8️⃣ **Security Settings**
- ✅ **Force HTTPS** - Secure connections
- ✅ **Password Min Length** - User password requirements
- ✅ **Session Timeout** - Auto logout timer
- ✅ **Enable Two-Factor Auth** - Additional security layer
- ✅ **Max Login Attempts** - Brute force protection

---

## 🎨 Where Settings Are Applied

### **Frontend Navbar** (`navbar-front.blade.php`)
- Logo from `app_logo` setting
- App name from `app_name` setting
- Dynamic login/dashboard button based on auth status

### **Frontend Footer** (`footer-front.blade.php`)
- Logo and app name
- App description
- Contact email with icon
- Support email with icon
- Social media links (only active ones shown)
- Copyright with app name

### **HTML Head** (`commonMaster.blade.php`)
- Page title with app name
- Meta description from SEO settings
- Meta keywords from SEO settings
- Open Graph tags for social sharing
- Google Analytics code (if configured)

### **WhatsApp FAB** (`components/whatsapp-fab.blade.php`)
- Floating action button with pulse animation
- Only shows if enabled in settings
- Uses configured WhatsApp number
- RTL and dark mode support
- Responsive design

### **Email Configuration**
- All email settings automatically configure Laravel mail system
- Used for order notifications, contact forms, etc.

### **Payment Processing**
- PayPal/Stripe credentials from settings
- Cash on delivery toggle

---

## 🔧 Technical Implementation

### **SettingsServiceProvider**
Located: `app/Providers/SettingsServiceProvider.php`

Automatically shares settings with ALL views:
```php
'appSettings' => All settings as array
'appName' => App name
'appLogo' => Logo URL
'appDescription' => Description
'primaryColor' => Primary theme color
'secondaryColor' => Secondary color
'accentColor' => Accent color
'whatsappNumber' => WhatsApp number
'enableWhatsappFab' => WhatsApp FAB toggle
'contactEmail' => Contact email
'supportEmail' => Support email
'paymentConfig' => Payment settings
'emailConfig' => Email settings
'seoConfig' => SEO settings
'socialLinks' => Active social links only
```

### **Helper Functions**
Located: `app/Helpers/Helpers.php`

Available everywhere:
```php
Helpers::getSetting('key', 'default')
Helpers::getSettingsByGroup('group')
Helpers::getAllSettings()
Helpers::getPaymentConfig()
Helpers::getEmailConfig()
Helpers::getSeoConfig()
Helpers::getSocialConfig()
Helpers::getApiConfig()
Helpers::getSecurityConfig()
Helpers::getAppearanceConfig()
```

### **Service Classes**
- `PaymentService::getConfig()` - Payment settings
- `EmailService::getConfig()` - Email settings
- `SeoService::getConfig()` - SEO settings
- `SocialService::getActive()` - Active social links only

---

## 📝 How to Use Settings

### **In Blade Templates**
```blade
<!-- Use app name -->
{{ $appName }}

<!-- Use app logo -->
@if($appLogo)
  <img src="{{ $appLogo }}" alt="{{ $appName }}">
@endif

<!-- Use SEO settings -->
{{ $seoConfig['title'] }}
{{ $seoConfig['description'] }}

<!-- Use social links -->
@foreach($socialLinks as $platform => $url)
  <a href="{{ $url }}">{{ $platform }}</a>
@endforeach

<!-- Use email settings -->
{{ $contactEmail }}
{{ $supportEmail }}
```

### **In Controllers**
```php
use App\Helpers\Helpers;

$appName = Helpers::getSetting('app_name');
$logo = Helpers::getSetting('app_logo');
$allSettings = Helpers::getAllSettings();
$paymentConfig = Helpers::getPaymentConfig();
```

### **In Configuration Files**
Settings automatically update Laravel config:
- `config('app.name')` uses `app_name` setting
- `config('app.timezone')` uses `timezone` setting
- App locale set from `language` setting

---

## 🚀 Quick Testing Guide

1. **Go to Dashboard Settings**: `/admin/settings`
2. **Update any setting** (e.g., App Name, Logo, Colors)
3. **Save changes**
4. **Visit website frontend** - Changes appear immediately
5. **Check multiple areas**:
   - Navbar logo and name
   - Footer content
   - Page title in browser tab
   - View page source for meta tags
   - Social media links in footer
   - WhatsApp floating button
   - Contact emails in footer

---

## ✨ Features

### **Dynamic SEO**
- Every page can override SEO with `@section` directives
- Google Analytics auto-injected if configured
- Open Graph tags for social sharing
- Proper meta description and keywords

### **Automatic Updates**
- No code changes needed
- Update settings in dashboard
- Changes reflect immediately on website
- No cache clearing required

### **Conditional Display**
- Social links only show if configured
- WhatsApp button only if enabled
- Emails only show if set
- Logo falls back to default if not set

### **RTL & Dark Mode Support**
- All components support RTL layout
- Dark mode compatible
- Responsive on all devices

---

## 📊 Settings Database Structure

**Table**: `settings`

Columns:
- `id` - Primary key
- `key` - Unique setting identifier
- `value` - Setting value (longText)
- `group` - Organization group
- `label` - Display label
- `description` - Setting description
- `type` - Input type (text, email, number, boolean, textarea, select, password)
- `options` - JSON options for select fields
- `created_at`, `updated_at` - Timestamps

---

## 🎯 Next Steps

All settings are now fully integrated! To add new settings:

1. Add to `SettingsSeeder.php`
2. Run seeder: `php artisan db:seed --class=SettingsSeeder`
3. Setting is automatically available in all views via `$appSettings` or specific variables

No additional code changes needed! The system is fully dynamic and extensible.

---

## 📞 Support

For questions or issues with settings:
1. Check this documentation
2. Verify settings are in database: `SELECT * FROM settings`
3. Clear Laravel cache if needed: `php artisan config:clear`
4. Check `SettingsServiceProvider` is loaded in `config/app.php`

---

**Last Updated**: December 3, 2025
**Version**: 1.0
**Status**: ✅ All Settings Fully Integrated
