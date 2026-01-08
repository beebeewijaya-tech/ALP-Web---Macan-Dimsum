USE macan_dimsum_go;

INSERT INTO users (id, role_id, email, password, address, created, updated)
VALUES
  (1, 1, 'admin@dimsum.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK', 'Jl. Administrasi No. 1, Surabaya', NOW(), NOW()),
  (2, 2, 'demo@dimsum.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK', 'Jl. Halim Perdana No. 11, Bangkalan', NOW(), NOW()),
  (3, 2, 'customer@dimsum.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK', 'Jl. Melati No. 22, Surabaya', NOW(), NOW()),
  (4, 2, 'vip@dimsum.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK', 'Jl. Bunga Raya No. 8, Sidoarjo', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  role_id = VALUES(role_id),
  password = VALUES(password),
  address = VALUES(address),
  updated = VALUES(updated);
