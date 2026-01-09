## Database setup

1. Jalankan `0001_initial_db_create.sql` untuk membuat database `macan_dimsum_go`.
2. Jalankan `0002_schema_and_seed.sql` untuk membuat seluruh tabel entities yang diperlukan (roles, users, products, orders, dst).
3. Jalankan `0003_seed_roles.sql` untuk mengisi role `admin` & `user`.
4. Jalankan `0004_seed_users.sql` untuk mengisi akun contoh (admin + 3 user).
5. Jalankan `0005_seed_products.sql` untuk mengisi daftar produk dimsum.
6. Jalankan `0006_seed_statuses.sql` untuk mengisi status order (PAID, PROCESSED, DELIVERED).

Gunakan client favorit (DBeaver, Workbench, dsb) lalu copy-paste SQL di atas sesuai urutan.
