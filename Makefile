# Docker image name
IMAGE_NAME = php_test_refacto_kata

# Container name
CONTAINER_NAME=php_test_container

# Commande Composer
COMPOSER_INSTALL = composer install --no-interaction --prefer-dist --optimize-autoloader

# Port number
PORT = 8081

# Règle pour exécuter Composer Install
composer-install:
	sudo docker exec -it $(CONTAINER_NAME) bash -c "$(COMPOSER_INSTALL)"

# Build docker image
build:
	sudo docker build -t $(IMAGE_NAME) .

# Start container
start-container:
	sudo docker run -d --name $(CONTAINER_NAME) -v ./php-test:/var/www/html -p $(PORT):$(PORT) $(IMAGE_NAME)

# Run docker container
run: start-container composer-install

# Run test
run-test:
	sudo docker exec -it $(CONTAINER_NAME) bash -c "./vendor/bin/phpunit tests/TemplateManagerTest.php"

# Remove docker container
stop-remove:
	-sudo docker stop $(CONTAINER_NAME)
	-sudo docker rm $(CONTAINER_NAME)

# Remove docker image
remove-image:
	-sudo docker rmi $(IMAGE_NAME)

# Remove docker container and image
reset:
	-sudo docker stop $(CONTAINER_NAME)
	-sudo docker rm $(CONTAINER_NAME)
	-sudo docker rmi $(IMAGE_NAME)