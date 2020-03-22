# Attention, ceci est une solution au problème posé dans ce tutoriel

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

Si vous voulez vraiment voir la réponse, descendez plus bas :)

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

plus bas encore ....

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

Vous y êtes bientôt !! encore un effort :)

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

## Solution possible :

```dockerfile
FROM php:7-apache

# On active le mod rewrite et on installe l'extension redis
RUN  a2enmod rewrite \
     && pecl install redis \
     && docker-php-ext-enable redis

# On définit les variables d'environnement par défaut
ENV  COLOR1=0000FF \
     COLOR2=00FF00 \
     REDIS_HOST=redis

# On copie les codes sources du site
COPY --chown=33:33 src/* /var/www/html/

# On copie les fichiers utilitaires (entrypoint.sh)
COPY files/* /

# On définit l'entrypoint
ENTRYPOINT [ "/entrypoint.sh" ]

# Et la commande par défaut :
CMD ["apache2-foreground"]
```

Et on build avec : 

```bash
docker build -t tutoriel-php:v1 .
```

