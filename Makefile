# Docker image name
IMAGE_NAME = php_test_refacto_kata

# Port number
PORT = 8081

# Build docker image
build:
	sudo docker build -t $(IMAGE_NAME) .

# Run docker container
run:
	-sudo docker stop php_test_container
	-sudo docker rm php_test_container
	sudo docker run --name php_test_container -p $(PORT):$(PORT) -d $(IMAGE_NAME)
