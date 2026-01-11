# Project Introduction

Dimsum macan adalah bisnis yang dilakukan oleh partner saya, dan kita ingin membuat sistem online supaya bisa meningkatkan

- Revenue
- Jumlah order
- Kemudahan dalam audit


# Cara Ngerun (Local)

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
   Pastikan $baseUrl, $adminUrl, $imagePath sesuai sama pathnya

# Deploy ke Ubuntu Droplet

1. **Install library yang dibutuhkan seperti apache dan php dan mysql**
   ```bash
   sudo apt update
   sudo apt install -y apache2 php libapache2-mod-php php-mysql mysql-server git make
   ```

2. **Clone project**
   ```bash
   sudo git clone <repo-url> /var/www/alp
   sudo chown -R www-data:www-data /var/www/alp
   ```

3. **Permission untuk upload foto di foldernya**
   ```bash
   cd /var/www/alp
   sudo -u www-data make setup-permissions
   ```

4. **Konfigurasi Apache**  
   Edit `/etc/apache2/sites-available/alp.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName <ip-atau-domain>
       DocumentRoot /var/www/alp/public

       SetEnv DB_USER <user-db>
       SetEnv DB_PASS <password-db>

       <Directory /var/www/alp/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
   Lalu enable:
   ```bash
   sudo a2ensite alp.conf
   sudo a2dissite 000-default.conf
   sudo systemctl reload apache2
   ```

5. **Seed database di server**
   ```bash
   mysql -u root -p < db/0001_initial_db_create.sql
   mysql -u root -p macan_dimsum_go < db/0002_schema_and_seed.sql
   mysql -u root -p macan_dimsum_go < db/0003_seed_roles.sql
   mysql -u root -p macan_dimsum_go < db/0004_seed_users.sql
   mysql -u root -p macan_dimsum_go < db/0005_seed_products.sql
   mysql -u root -p macan_dimsum_go < db/0006_seed_statuses.sql
   ```

6. **Restart Apachenya**
   ```bash
   sudo systemctl restart apache2
   sudo systemctl restart mysql
   ```

7. **Akses aplikasi**  
   Buka `http://<ip-atau-domain>/`. Login dengan user seed (`admin@dimsum.test`, dsb) pakai password `password`.
