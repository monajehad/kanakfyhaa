# 🚀 Quick Test Commands

## Database Setup
```powershell
cd c:\xampp\htdocs\kanakfyhaa
php artisan migrate:fresh --seed
```

## Run Server
```powershell
cd c:\xampp\htdocs\kanakfyhaa
php artisan serve --host=127.0.0.1 --port=8000
```

## Test URLs

### QR Experience Page (Test Product)
After running the seeder, look for output like:
```
✅ Test product and all related data created successfully!
🔗 Test URL: /experience/958ac031-74df-49dc-b7b3-4a22ad2ab9cb
```

Replace the UUID with the one from your seeder output and visit:
```
http://127.0.0.1:8000/experience/{UUID}
```

### Direct Test Product Query
```powershell
php artisan tinker
>>> $product = App\Models\Product::where('name', 'تجربة غزة التفاعلية')->first();
>>> echo $product->uuid;
```

Then open: `http://127.0.0.1:8000/experience/{UUID}`

---

## Features to Test

✅ **QR Display**
- Click "تحميل QR" (Download QR) → File downloads
- Click "طباعة" (Print) → Print dialog opens
- Click "فتح في نافذة جديدة" (Open new window) → New tab opens

✅ **Product Info**
- Product image shows (120x120px)
- Product name displays in Arabic
- City name shows
- Current date displays

✅ **Landmarks & Artifacts**
- 3 landmarks display in grid
- Each landmark shows 2 images
- Each landmark has 1 video player
- Artifacts nested under landmarks with thumbnails

✅ **Responsive**
- Resize browser window
- Check layout on mobile (< 768px)
- Verify RTL alignment

✅ **Media**
- Click images → No action (gallery view)
- Click video play button → Video plays with controls
- Volume and timeline controls work

---

## Database Verification

```powershell
php artisan tinker

# Get the test product
>>> $p = App\Models\Product::where('name', 'تجربة غزة التفاعلية')->first();
>>> $p->uuid

# Check city and landmarks
>>> $p->city->name
>>> $p->city->landmarks()->count()
>>> $p->city->landmarks()->with('artifacts', 'media')->first()

# Check media counts
>>> $p->city->landmarks()->first()->media()->count()
>>> $p->city->landmarks()->first()->artifacts()->first()->media()->count()

# Exit
>>> exit
```

---

## Troubleshooting

**Server won't start**: 
- Check if port 8000 is in use: `netstat -ano | findstr :8000`
- Try different port: `php artisan serve --port=8001`

**Page shows 404**: 
- Verify UUID exists: `SELECT uuid FROM products WHERE name LIKE '%غزة%';`
- Check route exists: `php artisan route:list | grep experience`

**Images not loading**: 
- Check internet connection (external image URLs)
- Inspect network tab in DevTools (F12)

**Styles missing**: 
- Run `npm run dev` to rebuild assets
- Clear browser cache (Ctrl+Shift+Delete)

---

## Files Modified

1. ✅ `database/seeders/TestProductQRSeeder.php` - NEW
2. ✅ `database/seeders/DatabaseSeeder.php` - UPDATED
3. ✅ `app/Http/Controllers/ExperienceController.php` - UPDATED (QR generation)
4. ✅ `resources/views/website/layout/pages/qr.blade.php` - UPDATED (UI/UX improvements)

---

**Ready to test!** Open your browser and enjoy the QR experience page! 🎉
