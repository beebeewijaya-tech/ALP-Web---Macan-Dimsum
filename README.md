# Project Introduction

Dimsum macan adalah bisnis yang dilakukan oleh partner saya, dan kita ingin membuat sistem online supaya bisa meningkatkan

- Revenue
- Jumlah order
- Kemudahan dalam audit


# Cara Ngerun

1. **Run XAMPP**  
   Nyalakan Apache dan MySQL lewat XAMPP Control Panel.

2. **Seed database**  
   Jalankan urutan SQL berikut melalui DBeaver/Workbench:
   - `0001_initial_db_create.sql`
   - `0002_schema_and_seed.sql`
   - `0003_seed_roles.sql`
   - `0004_seed_users.sql`
   - `0005_seed_products.sql`
   - `0006_seed_statuses.sql`

3. **Serve project di XAMPP**  
   Point Apache ke folder `public/` project ini (boleh lewat symlink atau virtual host). Setelah itu akses lewat browser, misal `http://localhost/alp/public`.
