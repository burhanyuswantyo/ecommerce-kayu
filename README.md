### 1. Clone Manual via Git
```bash
git clone https://github.com/burhanyuswantyo/ecommerce-kayu
cd ecommerce-kayu
````

### 2. Clone via GitHub Desktop

* Buka **GitHub Desktop**
* Pilih **File > Clone Repository**
* Masukkan URL repo:

  ```
  https://github.com/burhanyuswantyo/ecommerce-kayu
  ```
* Pilih folder penyimpanan, lalu klik **Clone**

---

## ⚙️ Instalasi

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Konfigurasi Environment

Copy file `.env.example` menjadi `.env`

```bash
cp .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

### 3. Setup Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```

## ▶️ Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan frontend (Vite):

```bash
npm run dev
```

Akses di browser:

```
http://localhost:8000
```

---

## 🛠️ Akun Default

```
https://localhost:8000/admin/dashboard

Username : superadmin
Password : password
```