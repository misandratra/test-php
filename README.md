# Refactoring Kata Test
### _Hervé ---  Test technique "Les Echos Le Parisien Services"_

 * Pour lancer le projet les commandes suivantes sont à executer dans l'ordre via le terminal en se positionnant à la racine du projet (dans le répertoire où se trouve le Dockerfile)

Si vous avez Make installé sur le poste de travail :
```sh
make build
make run
```
Sinon il faudra executer les commandes docker pour le build et run dans le terminal :
```sh
sudo docker build -t php_test_refacto_kata .
sudo docker run --name php_test_container -p 8081:8081 -d php_test_refacto_kata
```
Une fois les commandes lancées l'application est accessible dans le navigateur depuis l'adresse ci-dessous :
([application](http://localhost:8081/example/example.php))
```sh
http://localhost:8081/example/example.php
```
D'autres commandes sont disponibles : 
| Commande | Description |
| ------ | ------ |
| make run-test | Lancement des tests |
| make stop-remove | Arrêt et suppression des containers |
| make remove-image | Suppression de l'image docker |
| make reset | Suppression Container et Image |
| make composer-install | lance un composer install dans le container |
Le document [$${\color{blue}Refacto-Test-Kata.docx}$$](https://github.com/misandratra/test-php/blob/main/Refacto-Test-Kata.docx) résume la liste des modifications et refacto effectués sur le projet. 

