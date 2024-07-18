# test-php
Refactoring kata

* Pour lancer le projet les commandes suivantes sont à executer dans le terminal en se positionnant à la racine du projet 

Si vous avez make installé sur le poste de travail :

    "make build"

    "make run"

Sinon il faut executer les commandes docker pour le build et run dans le terminal :

    "sudo docker build -t php_test_refacto_kata ."

    "sudo docker run --name php_test_container -p 8081:8081 -d php_test_refacto_kata"
