IMAGES_DIR=public/assets/images

.PHONY: setup-permissions
setup-permissions:
	@mkdir -p $(IMAGES_DIR)
	@chmod 777 $(IMAGES_DIR)
	@echo "Ensured $(IMAGES_DIR) exists and is writable."
