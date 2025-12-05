# 🪑 Rizqi Wood Gallery

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-3-FDAE4B?style=for-the-badge&logo=filament&logoColor=black)

**Rizqi Wood Gallery** is a modern, full-stack e-commerce platform dedicated to premium teak wood furniture. Built to showcase "Artistry from NTB, Indonesia," this project combines a high-performance frontend using **Livewire** with a powerful admin dashboard powered by **FilamentPHP**.

## ✨ Key Features

### 🛍️ Storefront (Customer)

-   **Immersive Homepage:** Hero slider, "Why Choose Us" section, and featured collections with smooth horizontal scrolling.
-   **Advanced Catalog System:**
    -   **Filtering:** Filter by Category, Price Range (Min/Max), and Availability (Ready/Pre-order).
    -   **Sorting:** Sort by Newest, Price (Low-High / High-Low).
    -   **Search:** Real-time product search powered by Livewire.
-   **Product Details:**
    -   Interactive image gallery with thumbnail selection.
    -   Detailed specifications tab and visual reviews summary (5-star breakdown).
    -   Related products recommendations.
-   **Shopping Cart:** Real-time cart management with quantity adjustments.

### 🛡️ Admin Panel (Filament)

-   **Dashboard:** Analytics overview for sales and inventory.
-   **Product Management:** Full CRUD with rich text description, specification attributes, and gallery uploads.
-   **Order Processing:** Manage customer orders, shipping status, and payment verification.
-   **Content Management:** Manage categories, banners, and reviews.

## 🛠️ Tech Stack

-   **Framework:** [Laravel 11.x](https://laravel.com/)
-   **Frontend:** [Livewire 3.x](https://livewire.laravel.com/), [Alpine.js](https://alpinejs.dev/), [Tailwind CSS 3.4](https://tailwindcss.com/)
-   **Backend / Admin:** [FilamentPHP v3](https://filamentphp.com/)
-   **Database:** MySQL / MariaDB
-   **Build Tool:** Vite

## 🚀 Installation

Follow these steps to run the project locally:

1.  **Clone the Repository**

    ```bash
    git clone [https://github.com/ahmdhzq/rizqiwoodgallery.git](https://github.com/ahmdhzq/rizqiwoodgallery.git)
    cd rizqiwoodgallery
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Configure your database credentials (DB_DATABASE, DB_USERNAME, etc.) in the `.env` file._

4.  **Database Migration & Seeding**

    ```bash
    php artisan migrate --seed
    ```

5.  **Storage Link (Crucial for Images)**

    ```bash
    php artisan storage:link
    ```

6.  **Run Application**
    ```bash
    php artisan serve
    ```

    ```bash
    npm run dev
    ```

    * **Storefront:** `http://127.0.0.1:8000`
    * **Admin Panel:** `http://127.0.0.1:8000/admin`
````
