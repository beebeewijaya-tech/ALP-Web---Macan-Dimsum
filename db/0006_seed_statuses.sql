USE macan_dimsum_go;

INSERT INTO statuses (id, status_type, status_desc, created)
VALUES
  (1, 'PAID', 'Order has been paid', NOW()),
  (2, 'PROCESSED', 'Order is being prepared', NOW()),
  (3, 'DELIVERED', 'Order has been delivered', NOW())
ON DUPLICATE KEY UPDATE
  status_type = VALUES(status_type),
  status_desc = VALUES(status_desc);
