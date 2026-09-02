# 📝 Blog Platform API

یک سیستم بک‌اند برای وبلاگ چند-نویسنده، ساخته‌شده با **Laravel** — شامل مدیریت نقش‌های کاربری، انتشار پست، دسته‌بندی، برچسب‌گذاری، سیستم کامنت با تایید مدیریتی، و جستجو؛ همه به‌صورت **RESTful API**.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)
![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)
![Status](https://img.shields.io/badge/status-in%20progress-yellow)

---

## ✨ امکانات

- احراز هویت مبتنی بر توکن (Laravel Sanctum)
- سه سطح دسترسی: مدیر (admin)، نویسنده (author)، بازدیدکننده (visitor)
- عملیات کامل CRUD روی پست‌ها، با وضعیت پیش‌نویس/منتشرشده
- دسته‌بندی و برچسب‌گذاری (رابطه‌ی many-to-many)
- سیستم کامنت با نیاز به تایید مدیر پیش از نمایش عمومی
- جستجو در عنوان و محتوای پست‌ها

## 🛠 پیش‌نیازها

- PHP >= 8.2
- Composer
- MySQL (از طریق XAMPP)

## ⚙️ نصب و راه‌اندازی

```bash
git clone< https://github.com/MalekiRajaM/blog-platform.git>
cd blog-platform
composer install
copy .env.example .env
php artisan key:generate
```

سپس تو فایل `.env`، اطلاعات دیتابیس رو تنظیم کن:

```
DB_DATABASE=blog_platform
DB_USERNAME=root
DB_PASSWORD=
```

و در نهایت:

```bash
php artisan migrate
php artisan serve
```

پروژه روی آدرس `http://127.0.0.1:8000` بالا میاد.

## 🔑 احراز هویت

بعد از ثبت‌نام یا ورود، یک توکن دریافت می‌کنید که باید در هدر درخواست‌های محافظت‌شده ارسال شود:

```
Authorization: Bearer <your-token>
```

## 📚 مستندات API

### Auth

| Method | Endpoint | توضیح | نیاز به توکن |
|---|---|---|---|
| POST | `/api/register` | ثبت‌نام کاربر جدید | خیر |
| POST | `/api/login` | ورود | خیر |
| POST | `/api/logout` | خروج | بله |

### Posts

| Method | Endpoint | توضیح | نیاز به توکن |
|---|---|---|---|
| GET | `/api/posts` | لیست پست‌های منتشرشده (پارامتر `?search=` برای جستجو) | خیر |
| GET | `/api/posts/{id}` | نمایش یک پست | خیر |
| POST | `/api/posts` | ساخت پست جدید | بله (admin/author) |
| PUT | `/api/posts/{id}` | ویرایش پست | بله (صاحب پست یا admin) |
| DELETE | `/api/posts/{id}` | حذف پست | بله (صاحب پست یا admin) |

### Categories

| Method | Endpoint | توضیح | نیاز به توکن |
|---|---|---|---|
| GET | `/api/categories` | لیست دسته‌بندی‌ها | خیر |
| GET | `/api/categories/{id}` | نمایش یک دسته‌بندی با پست‌هاش | خیر |
| POST | `/api/categories` | ساخت دسته‌بندی | بله (admin) |
| DELETE | `/api/categories/{id}` | حذف دسته‌بندی | بله (admin) |

### Tags

| Method | Endpoint | توضیح | نیاز به توکن |
|---|---|---|---|
| GET | `/api/tags` | لیست برچسب‌ها | خیر |
| POST | `/api/tags` | ساخت برچسب | بله (admin/author) |

### Comments

| Method | Endpoint | توضیح | نیاز به توکن |
|---|---|---|---|
| GET | `/api/posts/{id}/comments` | لیست کامنت‌های تاییدشده‌ی یک پست | خیر |
| POST | `/api/posts/{id}/comments` | ثبت کامنت جدید | بله |
| POST | `/api/comments/{id}/approve` | تایید کامنت | بله (admin) |
| DELETE | `/api/comments/{id}` | حذف کامنت | بله (نویسنده‌ی کامنت یا admin) |

## 👤 نقش‌های کاربری

| نقش | دسترسی |
|---|---|
| `visitor` | مشاهده‌ی پست‌ها و ارسال کامنت |
| `author` | + ساخت/ویرایش/حذف پست‌های خودش، ساخت برچسب |
| `admin` | دسترسی کامل به همه‌ی بخش‌ها + تایید کامنت |
