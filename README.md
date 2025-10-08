# Laravel E-commerce Shop

متجر إلكتروني متكامل مبني بـ Laravel 12 مع Filament Admin Panel.

## المتطلبات

- PHP 8.2 أو أحدث
- Composer
- Node.js 18 أو أحدث
- MySQL أو SQLite
- خدمة البريد الإلكتروني (اختياري)

## تثبيت المشروع

### 1. نسخ المشروع
```bash
git clone <repository-url>
cd Shop
```

### 2. تثبيت التبعيات
```bash
composer install
npm install
```

### 3. تكوين البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. تحديث ملف .env
```env
APP_NAME="Shop"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. إعداد قاعدة البيانات
```bash
php artisan migrate
php artisan db:seed
```

### 6. ربط مجلد التخزين
```bash
php artisan storage:link
```

### 7. بناء الأصول
```bash
npm run build
```

## تشغيل المشروع

### للتطوير
```bash
# تشغيل السيرفر
php artisan serve

# تشغيل Vite للتطوير
npm run dev
```

### للإنتاج
```bash
# تحسين التطبيق
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# بناء الأصول للإنتاج
npm run build
```

## الميزات المتوفرة

### للعملاء
- ✅ عرض المنتجات والفئات والعلامات التجارية
- ✅ البحث في المنتجات
- ✅ إضافة المنتجات للسلة
- ✅ إضافة المنتجات للمفضلة
- ✅ إدارة الطلبات
- ✅ ملف المستخدم الشخصي
- ✅ نظام تسجيل الدخول والتسجيل

### لوحة الإدارة (Filament)
- ✅ إدارة المنتجات
- ✅ إدارة الفئات والعلامات التجارية
- ✅ إدارة الطلبات والمستخدمين
- ✅ إدارة السلة والمفضلة
- ✅ تقارير وإحصائيات

### API
- ✅ Laravel Sanctum للمصادقة
- ✅ إدارة السلة عبر API
- ✅ إدارة الطلبات عبر API
- ✅ تسجيل الدخول والخروج

## الطرق المتاحة

### المستخدمين
- `GET /` - الصفحة الرئيسية
- `GET /products` - عرض المنتجات
- `GET /categories` - عرض الفئات
- `GET /brands` - عرض العلامات التجارية
- `GET /search` - البحث
- `GET /cart` - السلة
- `GET /favorites` - المفضلة
- `GET /orders` - الطلبات

### الإدارة
- `GET /admin` - لوحة التحكم
- مسارات Filament لإدارة جميع الكيانات

### API
- `POST /api/login` - تسجيل الدخول
- `POST /api/register` - التسجيل
- `GET /api/carts` - عرض السلة
- `POST /api/carts` - إضافة للسلة
- `GET /api/orders` - عرض الطلبات

## إعداد بيئة الإنتاج

### 1. متطلبات الخادم
- Apache/Nginx
- PHP 8.2+ مع الإضافات المطلوبة
- MySQL/PostgreSQL
- SSL Certificate

### 2. تحسينات الأداء
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

### 3. الأمان
- تحديث `APP_ENV=production`
- تعطيل `APP_DEBUG=false`
- استخدام HTTPS
- تكوين Firewall

## الاختبارات

```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبارات محددة
php artisan test --filter=AuthenticationTest
```

## المشاكل الشائعة

### مشكلة CSRF Token
إذا واجهت خطأ 419، تأكد من:
- وجود `@csrf` في النماذج
- صحة تكوين الجلسات
- تفعيل JavaScript

### مشكلة الأذونات
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### مشكلة قاعدة البيانات
```bash
php artisan migrate:fresh --seed
```

## المساهمة

1. Fork المشروع
2. إنشاء فرع جديد (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push للفرع (`git push origin feature/AmazingFeature`)
5. إنشاء Pull Request

## الترخيص

هذا المشروع مرخص تحت [MIT license](https://opensource.org/licenses/MIT).

## الدعم

إذا واجهت أي مشاكل، يرجى فتح issue في GitHub أو التواصل مع فريق التطوير.

## الإصدارات

- **v1.0.0** - النسخة الأولى مع الميزات الأساسية
- المزيد قريباً...

---

تم تطوير هذا المشروع بـ ❤️ باستخدام Laravel & Filament
