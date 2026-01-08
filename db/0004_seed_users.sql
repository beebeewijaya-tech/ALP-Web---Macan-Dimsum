USE macan_dimsum_go;

INSERT INTO users (id, role_id, email, password, address, phone, created, updated)
VALUES
  (1, 1, 'admin@dimsum.test', '$2y$10$Rbiv/UjDUBK14rHLDnFPJuTGsCJ4DBd0GuQF9hOWMksp4coE0z1CK', 'Jl. Administrasi No. 1, Surabaya', '+6281991538410', NOW(), NOW()),
  (2, 2, 'demo@dimsum.test', '$2y$10$Rbiv/UjDUBK14rHLDnFPJuTGsCJ4DBd0GuQF9hOWMksp4coE0z1CK', 'Jl. Halim Perdana No. 11, Bangkalan', '+6281991538410', NOW(), NOW());