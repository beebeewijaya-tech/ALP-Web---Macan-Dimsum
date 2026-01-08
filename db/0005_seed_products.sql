USE macan_dimsum_go;

INSERT INTO products (id, name, image, price, created)
VALUES
  (1, 'Dimsum Mentai', 'dimsum1.png', 22000, NOW()),
  (2, 'Dimsum Isian Keju', 'dimsum2.png', 22000, NOW()),
  (3, 'Dimsum Kuah Creamy', 'dimsum3.png', 16000, NOW()),
  (4, 'Dimsum Mentai Party Size', 'dimsum4.png', 58000, NOW()),
  (5, 'Dimsum Original', 'dimsum5.png', 18000, NOW()),
  (6, 'Dimsum Mentai Isian Keju', 'dimsum6.png', 26000, NOW())
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  image = VALUES(image),
  price = VALUES(price);
