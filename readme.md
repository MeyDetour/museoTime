
# Installation de symfony
https://symfony.com/download 

# Installation de postgresql
https://www.postgresql.org/download/

-création d'un utilisateur
https://www.postgresql.org/docs/8.0/sql-createuser.html

- parametrage symfony 

DATABASE_URL="postgresql://user:password@db:5432/dbname?serverVersion=16&charset=utf8"


# installation de composer 
https://getcomposer.org/
 
# Installation de php 
https://dyma.fr/blog/installation-de-php/?campaignId=22795711356&device=c&utm_source=google&gad_source=1&gad_campaignid=22805258542&gbraid=0AAAAADPXRQlgn_hiTgyU2_QCVE5qWXTYx&gclid=CjwKCAjwr8LHBhBKEiwAy47uUq2b223cEziSZHvDAO5Ir4t8hm35B_3803rDbzMIVjd9k8fbJSgLKhoCf3YQAvD_BwE
 

# Une fois symfony installé
̀```
symfony check:requirements
```


# Erreurs communes

- Gd driver not installed 
solution : sudo apt install php-gd


-  Cannot load migrations from "/var/www/html/museoTime/migrations" because it  
   is not a valid directory                                                    
                               
solution créer le dossier à la racine "migrations" mkdir migrations

