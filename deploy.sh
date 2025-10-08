#!/bin/bash

# Laravel Shop Deployment Script
echo "🚀 بدء نشر Laravel Shop..."

# التحقق من وجود المتطلبات
echo "📋 فحص المتطلبات..."

# التحقق من PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP غير مثبت"
    exit 1
fi

# التحقق من Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer غير مثبت"
    exit 1
fi

# التحقق من Node.js
if ! command -v node &> /dev/null; then
    echo "❌ Node.js غير مثبت"
    exit 1
fi

echo "✅ جميع المتطلبات متوفرة"

# تثبيت تبعيات PHP
echo "📦 تثبيت تبعيات PHP..."
composer install --optimize-autoloader --no-dev

# تثبيت تبعيات Node.js
echo "📦 تثبيت تبعيات Node.js..."
npm install

# إنشاء ملف .env إذا لم يكن موجوداً
if [ ! -f .env ]; then
    echo "⚙️ إنشاء ملف .env..."
    cp .env.example .env
    php artisan key:generate
fi

# تشغيل الهجرات
echo "🗄️ تشغيل هجرات قاعدة البيانات..."
php artisan migrate --force

# ربط مجلد التخزين
echo "🔗 ربط مجلد التخزين..."
php artisan storage:link

# بناء الأصول
echo "🏗️ بناء الأصول للإنتاج..."
npm run build

# تحسين التطبيق
echo "⚡ تحسين التطبيق..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# تحديد الأذونات
echo "🔐 تحديد أذونات الملفات..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ تم النشر بنجاح!"
echo "🌐 يمكنك الآن الوصول للتطبيق"

# عرض معلومات مفيدة
echo ""
echo "📝 معلومات مهمة:"
echo "- لوحة الإدارة: /admin"
echo "- API Documentation: /api/documentation"
echo "- تأكد من تكوين قاعدة البيانات في .env"
echo "- تأكد من تكوين البريد الإلكتروني في .env"

echo ""
echo "🎉 Laravel Shop جاهز للاستخدام!"