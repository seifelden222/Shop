#!/bin/bash

# Laravel Shop Helper Script
# يحتوي على أوامر مفيدة للتطوير والإدارة

show_help() {
    echo "🛠️  Laravel Shop Helper Script"
    echo ""
    echo "الاستخدام: ./helper.sh [COMMAND]"
    echo ""
    echo "الأوامر المتاحة:"
    echo "  setup          إعداد المشروع لأول مرة"
    echo "  dev            تشغيل بيئة التطوير"
    echo "  build          بناء الأصول للإنتاج"
    echo "  fresh          إعادة تعيين قاعدة البيانات"
    echo "  test           تشغيل الاختبارات"
    echo "  optimize       تحسين التطبيق للإنتاج"
    echo "  cache:clear    مسح جميع أنواع التخزين المؤقت"
    echo "  user:admin     إنشاء مستخدم إدارة"
    echo "  backup         نسخ احتياطي من قاعدة البيانات"
    echo "  docker:up      تشغيل Docker containers"
    echo "  docker:down    إيقاف Docker containers"
    echo "  help           عرض هذه المساعدة"
    echo ""
}

setup_project() {
    echo "🚀 إعداد المشروع..."
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    php artisan storage:link
    npm run build
    echo "✅ تم إعداد المشروع بنجاح!"
}

dev_environment() {
    echo "🔥 تشغيل بيئة التطوير..."
    echo "السيرفر: http://localhost:8000"
    echo "لوحة الإدارة: http://localhost:8000/admin"
    echo ""
    # تشغيل السيرفر و Vite في الخلفية
    php artisan serve &
    npm run dev &
    wait
}

build_assets() {
    echo "🏗️ بناء الأصول للإنتاج..."
    npm run build
    echo "✅ تم بناء الأصول!"
}

fresh_database() {
    echo "🗄️ إعادة تعيين قاعدة البيانات..."
    php artisan migrate:fresh --seed
    echo "✅ تم إعادة تعيين قاعدة البيانات!"
}

run_tests() {
    echo "🧪 تشغيل الاختبارات..."
    php artisan test
}

optimize_app() {
    echo "⚡ تحسين التطبيق..."
    composer install --optimize-autoloader --no-dev
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    echo "✅ تم تحسين التطبيق!"
}

clear_cache() {
    echo "🧹 مسح التخزين المؤقت..."
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan optimize:clear
    echo "✅ تم مسح التخزين المؤقت!"
}

create_admin() {
    echo "👤 إنشاء مستخدم إدارة..."
    php artisan make:filament-user
}

backup_database() {
    echo "💾 إنشاء نسخة احتياطية..."
    DATE=$(date +%Y%m%d_%H%M%S)
    php artisan db:dump --path="backup_${DATE}.sql"
    echo "✅ تم إنشاء النسخة الاحتياطية: backup_${DATE}.sql"
}

docker_up() {
    echo "🐳 تشغيل Docker containers..."
    docker-compose up -d
    echo "✅ Docker containers تعمل الآن!"
    echo "التطبيق: http://localhost:8000"
}

docker_down() {
    echo "🐳 إيقاف Docker containers..."
    docker-compose down
    echo "✅ تم إيقاف Docker containers"
}

# تحديد الأمر المطلوب
case "${1:-help}" in
    setup)
        setup_project
        ;;
    dev)
        dev_environment
        ;;
    build)
        build_assets
        ;;
    fresh)
        fresh_database
        ;;
    test)
        run_tests
        ;;
    optimize)
        optimize_app
        ;;
    cache:clear)
        clear_cache
        ;;
    user:admin)
        create_admin
        ;;
    backup)
        backup_database
        ;;
    docker:up)
        docker_up
        ;;
    docker:down)
        docker_down
        ;;
    help|*)
        show_help
        ;;
esac