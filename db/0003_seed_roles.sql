USE macan_dimsum_go;

INSERT INTO roles (id, role_name, created)
VALUES
  (1, 'admin', NOW()),
  (2, 'user', NOW())
ON DUPLICATE KEY UPDATE
  role_name = VALUES(role_name),
  created = VALUES(created);
