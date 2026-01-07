USE macan_dimsum_go;

INSERT INTO `user` (`email`, `password`, `address`, `created`, `updated`)
VALUES
  (
    'demo@dimsum.test',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK',
    'Jl. Halim Perdana No. 11, Bangkalan',
    NOW(),
    NOW()
  ),
  (
    'customer@dimsum.test',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK',
    'Jl. Melati No. 22, Surabaya',
    NOW(),
    NOW()
  ),
  (
    'vip@dimsum.test',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lfcGmZ1kn1/4A1NVZy.yK',
    'Jl. Bunga Raya No. 8, Sidoarjo',
    NOW(),
    NOW()
  );
