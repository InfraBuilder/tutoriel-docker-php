# Tutoriel de conteneurisation avec PHP

## Pré-requis 

Installer docker CE (Community Edition):

- Windows : https://hub.docker.com/editions/community/docker-ce-desktop-windows

- Mac : https://hub.docker.com/editions/community/docker-ce-desktop-mac 

- Linux/Ubuntu : https://docs.docker.com/install/linux/docker-ce/ubuntu/
- Linx/Debian : https://docs.docker.com/install/linux/docker-ce/debian/
- Linux/Centos : https://docs.docker.com/install/linux/docker-ce/centos/
- Linux/Fedora : https://docs.docker.com/install/linux/docker-ce/fedora/

Vous devez pouvoir lancer un terminal (Par exemple : `cmd` sous Windows, `iterm2` sur Mac, `xterm` ou `terminator` sur Linux)

Nota Bene : Sur Windows et Mac, vous devez lancer "Docker Desktop" pour pouvoir utiliser les commandes `docker`

## Premier contact avec Docker

Pour ce premier exercice, nous allons démarrer un conteneur à partir d'une image issue des dépôts officiels Docker Hub, afin de lancer un shell dans un conteneur "Alpine".

Alpine est une distribution linux basée sur "busybox", qui a l'avantage d'être extrêmement légère (seulement 5Mo) et de disposer d'un gestionnaire de paquets : `apk`.

Pour lancer le conteneur, il vous suffit de lancer la commande suivante : 

```bash
docker run -it alpine
```

> `docker run ` permet d'instancier un conteneur, `-it` sert à allouer un terminal et à attacher l'entrée et la sortie standard du conteneur à notre terminal, `alpine` est le nom de notre image de conteneur.

Vous devriez alors obtenir le résultat suivant (à peu de choses près) :

```
Unable to find image 'alpine:latest' locally
latest: Pulling from library/alpine
c9b1b535fdd9: Pull complete
Digest: sha256:ab00606a42621fb68f2ed6ad3c88be54397f981a7b70a79db3d1172b11c4367d
Status: Downloaded newer image for alpine:latest
/ #
```

La présence de `/ #` à la fin indique que vous êtes dans un shell.

On remarque que l'image n'était pas présente sur notre machine locale, donc docker l'a automatiquement téléchargée pour pouvoir lancer un conteneur.

Vous pouvez lancer un `cat /etc/issue` pour voir sur quel OS vous êtes dans ce shell :

```
Welcome to Alpine Linux 3.11
Kernel \r on an \m (\l)
```

Vous êtes bien sur un shell d'une distribution Alpine Linux !

Essayez de lancer une commande `curl http://ifconfig.co` , vous constaterez que la commande "curl" est introuvable. Alpine Linux est une distribution minimaliste qui a pour but d'être la plus petite possible, ainsi il n'y a que très peu d'utilitaires. C'est également une volonté de sécurité, moins il y a de choses, moins il y a de surface d'attaque possible.

Pour ajouter "curl" à notre conteneur, on va utiliser le gestionnaire de paquet de la distribution alpine `apk` .

```bash
apk add --update curl
```

> `apk add` est la commande pour ajouter un paquet à notre installation, `--update` permet de demander à apk de mettre à jour la liste des paquets disponibles avec les dépôts alpine, et `curl` est le nom du paquet à installer

A présent, si on relance la commande `curl http://ifconfig.co` vous devriez avoir votre adresse IP publique !

Pour quitter le shell, lancez la commande `exit` ou appuyez sur `CTRL-D`

Vous devriez revenir sur votre terminal local.

## Lister les conteneurs

Pour lister les conteneurs présents sur la machine, nous utilisons la commande :

```bash
docker container ls
```

Vous devriez avoir une liste vide, car il n'y a aucun conteneur en cours d'exécution actuellement. En revanche, si on utilise la commande avec le flag `-a` pour voir tous les conteneurs :

```bash
docker container ls -a
```

Vous devriez obtenir un réulstat similiaire à :

```
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS                     PORTS               NAMES
d81f16ca9cbd        alpine              "/bin/sh"           19 minutes ago      Exited (0) 5 seconds ago                       cranky_albattani
```

Ici on retrouve notre conteneur alpine, avec l'identifiant `d81f16ca9cbd` et un nom généré aléatoirement `cranky_albattani`

On peut supprimer notre conteneur avec la commande :

```bash
# ou bien avec son identifiant :
docker container rm d81f16ca9cbd
# ou avec son nom :
docker container rm cranky_albattani
# ou encore avec le début de son identifiant (suffisamment long pour être déterminant)
docker container rm d8
```

Nous venons de supprimer le conteneur, c'est à dire l'instance de conteneur qui a été crée sur le modèle de l'image "alpine". Vérifions si nous avons toujours l'image sur notre machine :

```bash
docker image ls
```

Vous devriez avoir un résultat équivalent à :

```
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
alpine              latest              e7d92cdc71fe        2 months ago        5.59MB
```

Si vous relancer un conteneur à partir de l'image "alpine", vous n'aurez pas à la retélécharger. Vous pouvez même l'utiliser hors connexion (train/avion/etc.), par contre vous ne pourrez pas ajouter de paquets sans avoir internet

## Les images sur Docker Hub

L'image alpine que l'on a utilisé ci-dessus provient du dépôt (repository) Docker Hub.

Les images présentes sur Docker Hub sont souvent accompagnée d'une documentation sur comment les utiliser, par exemple sur la page de l'image alpine https://hub.docker.com/_/alpine , on y trouve la description d'alpine :

```
Alpine Linux is a Linux distribution built around musl libc and BusyBox. The image is only 5 MB in size and has access to a package repository that is much more complete than other BusyBox based images. This makes Alpine Linux a great image base for utilities and even production applications. Read more about Alpine Linux here and you can see how their mantra fits in right at home with Docker images.
```

Il y a également de nombreuses images dites officielles, comme par exemple :

- Ubuntu ( https://hub.docker.com/_/ubuntu )
- Debian ( https://hub.docker.com/_/debian )
- nginx ( https://hub.docker.com/_/nginx )
- PHP ( https://hub.docker.com/_/php/ )
- MySQL ( https://hub.docker.com/_/mysql )
- MariaDB ( https://hub.docker.com/_/mariadb )
- Redis ( https://hub.docker.com/_/redis/ )
- Memcached ( https://hub.docker.com/_/memcached/ )
- ...

Vous remarquerez que ces images ont un lien vers Docker Hub avec un underscore suivi du nom de l'image, ex: "/_/{image}", c'est le signe qu'elle sont officielles. En général ces images sont garanties sans vérole, et suivent généralement le protocole de mise à jour de la distribution ou de l'application qu'elle fournit.

Il y a également des images qui sont maintenues par un ou plusieurs mainteneurs, ou par l'éditeur lui-même du logiciel. Par exemple :

- MySQL ( https://hub.docker.com/r/mysql/mysql-server )
- mysql-aws-cli par infraBuilder ( https://hub.docker.com/r/infrabuilder/mysql-aws-cli )
- Maildev par djfarrely ( https://hub.docker.com/r/djfarrelly/maildev )
- SFTP par Atmoz ( https://hub.docker.com/r/atmoz/sftp )
- ...

Ces images sont maintenues par des indépendants ou des organisations, elles sont disponibles via des URL du type : "/r/{maintainer}/{image}"

## Les tags d'image

Une image de conteneur doit fournir un service bien précis. Il y a deux grande familles d'images :

- Les images de base (souvent une simple distibution linux)
- Les images d'application (fournit un service)

Grâce au principe d'héritage des Dockerfile, on peut facilement partir d'une image de base, la configurer selon son bon loisir à l'aide d'instructions Dockerfile, et créer une nouvelle image qui pourra à son tour servir de base ou être utilisée directement.

Par exemple, l'image `redis` ( https://hub.docker.com/_/redis/ ) par défaut (latest) est construite à partir d'une image "debian".

Les tags permettent d'avoir plusieurs images pour fournir un même service. Par exemple, l'image `redis` propose plusieurs tags :

- [`6.0-rc2`, `6.0-rc`, `rc`, `6.0-rc2-buster`, `6.0-rc-buster`, `rc-buster`](https://github.com/docker-library/redis/blob/7678eb71afd668779635758a2e0005cd87cc6c16/6.0-rc/Dockerfile)
- [`6.0-rc2-alpine`, `6.0-rc-alpine`, `rc-alpine`, `6.0-rc2-alpine3.11`, `6.0-rc-alpine3.11`, `rc-alpine3.11`](https://github.com/docker-library/redis/blob/7678eb71afd668779635758a2e0005cd87cc6c16/6.0-rc/alpine/Dockerfile)
- [`5.0.8`, `5.0`, `5`, `latest`, `5.0.8-buster`, `5.0-buster`, `5-buster`, `buster`](https://github.com/docker-library/redis/blob/dc4e9c20b98b370069cff1d250a24d78d31c0f10/5.0/Dockerfile)
- [`5.0.8-32bit`, `5.0-32bit`, `5-32bit`, `32bit`, `5.0.8-32bit-buster`, `5.0-32bit-buster`, `5-32bit-buster`, `32bit-buster`](https://github.com/docker-library/redis/blob/dc4e9c20b98b370069cff1d250a24d78d31c0f10/5.0/32bit/Dockerfile)
- [`5.0.8-alpine`, `5.0-alpine`, `5-alpine`, `alpine`, `5.0.8-alpine3.11`, `5.0-alpine3.11`, `5-alpine3.11`, `alpine3.11`](https://github.com/docker-library/redis/blob/dc4e9c20b98b370069cff1d250a24d78d31c0f10/5.0/alpine/Dockerfile)
- [`4.0.14`, `4.0`, `4`, `4.0.14-buster`, `4.0-buster`, `4-buster`](https://github.com/docker-library/redis/blob/b6d413ceff3a2bca10a430ace121597fa8fe2a2c/4.0/Dockerfile)
- ...

Ici les tags [`5.0.8`, `5.0`, `5`, `latest`, `5.0.8-buster`, `5.0-buster`, `5-buster`, `buster `](https://github.com/docker-library/redis/blob/dc4e9c20b98b370069cff1d250a24d78d31c0f10/5.0/Dockerfile) ne sont que des pointeurs vers la même image. On remarque que dans cette liste de tag, il y a le tag sépcial "latest"

**NB : Si vous lancez l'image `redis` sans préciser de tag, ce sera le tag `latest` qui sera utilisé.**

Avec tous ces tags qui pointent vers une seule et même image, l'équipe de Redis vous permet de choisir avec finesse quels changements de version vous pourriez supporter.

En choisissant le tag "latest", vous aurez toujours la dernière version de redis, donc dés que la version 6 sera disponible, tout téléchargement de l'image redis:latest vous donnera alors une redis 6.

En choisissant le tag "5", vous aurez toujours une redis en version 5, ainsi lorsque l'équipe de redis sortira une version 6, vous serez sûr de na pas avoir la version 6, mais bien la dernière release de la version 5. Si redis sort une nouvelle release de la version 5, le tag "5" pointera alors sur l'image de la dernière release 5.X.X

**Astuce : En général il n'est jamais bon d'utiliser le tag latest en production, sinon on risque de se retrouver avec des surprises au cours du temps**

L'équipe de redis propose également deux versions d'images pour une même version de redis, une image basée sur Debian Buster, et une basée sur Alpine. C'est au développeur de choisir, et la différence est expliquée sur la documentation de l'image (Section "Image Variants") : https://hub.docker.com/_/redis/

Voyons voir la différence de taille entre les deux versions de redis, tout d'abord on télécharge les deux images  :

```bash
docker pull redis:5-alpine
docker pull redis:5-buster
```

Voyons à présent les différences de taille avec `docker image ls` :

```
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
redis               5-alpine            d8415a415147        9 days ago          30.4MB
redis               5-buster            f0453552d7f2        9 days ago          98.2MB
```

C'est presque 70Mo d'écart entre les deux images, soit 227% plus volumineuse, et ce pour le même service rendu : Redis 5 ! 

## Intéractions entre conteneurs

Nous allons à présent lancer un conteneur Redis en mode serveur, pour cela nous allons remplacer le flag `-it` (terminal + interactif) par le flag `-d` (Daemonize) pour que redis puisse tourner en arrière-plan.

```bash
docker run -d --name monredis redis:5-alpine
```

> Ici le flag `--name` permet de définir le nom de notre conteneur plutôt que de laisser docker lui en attribuer un aléatoirement choisi.

On vérifie que notre conteneur est fonctionnel :

```bash
docker container ls
```

Et on lance un nouveau conteneur intéractif, mais cette fois le but est d'ouvrir une console vers le serveur redis qu'on vient de lancer :

```bash
docker run -it --link monredis:serveur redis:5-alpine redis-cli -h serveur
```

> le flag `--link mon-serveur-redis:serveur` va indiquer à docker de créer une entrée "serveur" dans le /etc/hosts du conteneur qu'on lance, pointant vers l'adresse IP du conteneur `mon-serveur-redis`. Ensuite on retrouve l'image qu'on souhaite lancer "redis:5-alpine", et tout ce qui est derrière est interprété par docker comme étant la commande qu'on souhaite lancer dans notre conteneur.

Vous devriez alors vous retrouver dans un cli redis :

```
serveur:6379>
```

Vous pouvez tester que le service fonctionne bien, par exemple avec les commandes redis suivantes :

```
set maclef good
get maclef
incr moncompteur 
incr moncompteur 
get moncompteur 
```

Pour sortir du cli redis, tapez simplement `exit` ou `CTRL-D`

*NB : Vous venez d'utiliser un CLI redis sans avoir à l'installer sur votre poste, vous pouvez même tester plusieurs versions différentes (4, 5, 6) en parallèle sans que les conteneurs ne se gênent les uns les autres.*

## Supprimer les conteneurs arrêtés

Depuis le début de ce tutoriel, nous avons lancé quelques conteneurs intéractifs, que nous avons arrêté en quittant les shell/cli. 

Nous pouvons voir ces conteneurs à l'aide de la commande :

```bash
docker container list -a
```

Pour supprimer rapidement les conteneurs arrêtés, il suffit d'utiliser la commande "prune" :

```bash
docker container prune
```

Vous pouvez vérifier en relançant la commande :

```bash
docker container list -a
```

## Et si on faisait du PHP ?

Il existe une image officielle `php` ( https://hub.docker.com/_/php/ ) qui vous propose plusieurs méthodes pour faire du php (en cli, via php-fpm, en module apache, etc.)

Testons tout d'abord la version "cli" :

```bash
docker run -it --rm php:7-cli-alpine
```

> le flag `--rm` permet de spécifier à Docker que nous souhaitons que le conteneur soit supprimé dés qu'il s'arrêtera, ce qui nous évite d'avoir à penser à faire le "prune" plus tard

Nous nous retrouvons alors dans un shell PHP interactif. On peut donc lancer des instructions PHP :

```php
echo date("Y-m-d H:i:s");
```

Comme d'habitude, pour sortir du shell, faites `exit` ou `CTRL-D`

Pour conteneuriser un site web, on va plutôt utiliser l'image `php:7-apache-alpine`

Voici une commande pour lancer l'image php apache et exposer le port d'apache sur notre machine :

```bash
docker run -it --rm -p 8080:80 php:7-apache
```

> le flag `-p 8080:80` permet d'exposer le port 80 du conteneur (sur lequel écoute apache) sur le port 8080 de notre machine, afin qu'on puisse joindre apache en allant sur : http://localhost:8080

En ouvrant votre navigateur préféré et en allant sur http://localhost:8080, vous verrez alors une page "Forbidden", et une ligne de log sera écrite dans votre terminal. Le conteneur apache est configuré pour logguer sur stdout, **c'est une excellente pratique** car cela permettra à des orchestrateurs de conteneurs comme Kubernetes de facilement centraliser les logs de tous les conteneurs sans avoir rien à configurer de particulier dans ces conteneurs.

Le Forbidden est tout simplement du au fait qu'il n'y a pas de fichier index.php dans le conteneur.

Nous allons remédier à ce problème, pour cela arrêtez le conteneur actuel avec `CTRL-C`

Lancez à présent un conteneur en montant le dossier où se trouve cette tutoriel, en mode serveur :

```bash
docker run -d -p 8080:80 -v $PWD:/var/www/html --name web php:7-apache
```

> le flag `-v $PWD/var/www/html` permet de spécifier à docker de monter le dossier où l'on se trouve ($PWD) sur le dossier /var/www/html du conteneur. Ainsi lorsque le serveur apache va chercher /var/www/html/index.php, il trouvera en vérité le fichier présent dans ce dossier.

Visitez à présent l'URL http://localhost:8080 !

Etant donné que PHP est un langage interprété, et que le dossier `/var/www/html` n'est qu'un montage de notre dossier local, si on modifie le fichier index.php localement, il sera directement visible sur http://localhost:8080 ! Cela est très utile pour développer avec son IDE préféré, et éxécuter le site avec un conteneur. Par exemple, on peut ainsi tester facilement la compatibilité du code avec différentes versions de PHP.

On pense à arrêter et supprimer notre conteneur (notamment pour libérer le port 8080):

```bash
docker container stop web
docker container rm web
```

## Conteneuriser un petit site web PHP

A présent qu'on maîtrise un peu plus les conteneurs, et si on créait notre propre image de conteneur ?

Pour cela nous allons devoir créer un fichier nommé "Dockerfile". Ce Dockerfile va contenir des instructions qui sont référencées ici : https://docs.docker.com/engine/reference/builder/.

Par exemple, on peut créer un Dockerfile, basé sur une image `php:7-apache` et ajouter notre fichier `index.php` :

```dockerfile
FROM php:7-apache
COPY index.php /var/www/html/
```

Une fois le fichier Dockerfile créé au même endroit que notre fichier index.php, on peut lancer la construction de l'image :

```bash
docker build -t monsite:montag .
```

> La commande `docker build  <options> .` va construire l'image de conteneur en utilisant le dossier courant comme contexte, et donc charger le Dockerfile de ce dossier. Le flag `-t monsite:montag` permet de tagguer l'image ainsi construite avec le nom d'image "monsite" et le nom de tag "montag".

On peut maintenant lancer notre nouvelle image, et cette fois, pas la peine de monter de dossier :

```bash
docker run -d -p 8080:80 --name web monsite:montag
```

Notre site est joignable sur l'URL http://localhost:8080 et sert notre fichier index.php directement ! Par contre, comme le dossier /var/www/html n'est pas un montage du dossier local, si on modifie le contenu du fichier index.php sur notre machine, cela n'aura aucun impact sur le conteneur et sur l'image. L'image est immuable. Si je veux la modifier, je dois la reconstruire.

## Conteneurisation avancée

Voici les instructions les plus importantes du Dockerfile : 

```dockerfile
FROM <image>
ENV <VAR>=<valeur>
RUN <commande-linux>
WORKDIR <dossier-de-travail>
COPY --chown=<userid>:<groupid> <dossier-local>/* <dossier-conteneur>/
ADD <source> <dossier-conteneur>/
EXPOSE <port>
USER <utilisateur-conteneur>
CMD ["<commande-par-défaut>"]
ENTRYPOINT ["<point-d-entrée>"]
```

Voici un descriptif rapide de chaque instruction :

- `FROM <image>` : Définit l'image de base à partir de laquelle toutes les autres instructions vont être jouées.
- `ENV <VAR>=<valeur>` : Déclare une variable d'environnement qui sera utilisable dans les autres instructions du Dockerfile, ainsi que dans notre application
- `RUN <commande-linux` : Commande qui va être exécutée et qui va modifier le conteneur **au build** (par exemple: installation de paquet, création de dossier/fichier, compilation de code, etc.). Il peut y avoir plusieurs fois l'instruction RUN, mais on essaie de les regrouper autant que possible avec des `&&`
- `WORKDIR <dossier-de-travail>` : Permet de définir le dossier de travail dans lequel s'exécuteront toutes les instructions suivantes 
- `COPY --chown=<user>:<group> <dossier-local>/* <dossier-conteneur>/` : Copie les fichiers contenus dans *dossier-local* vers *dossier-conteneur* dans le conteneur, et applique un changement de propriétaire avec le *user* et le *group* du conteneur.
-  `ADD <source> <dossier-conteneur>/` : Cette instruction est assez similaire à COPY, mais va permettre des traitements plus intelligents. Si la source est une archive "tar.gz", elle sera décompresser dans le *dossier-conteneur* indiqué. Si la source est une URL, la ressource sera téléchargée et stockée dans *dossier-conteneur*. Attention, si la source est une URL vers un tar.gz, il ne sera pas décompressé !
- `EXPOSE <port>` : Déclare que le conteneur rend un service sur le port TCP concerné
- `USER <utilisateur-conteneur>` : Définit l'utilisateur linux du conteneur qui lancera toutes les instructions à partir de cette ligne
- `CMD ["<commande-par-défaut>"]` : Définit la commande par défaut, si la personne qui lance le conteneur ne définit pas de commande après le nom de l'image dans son "docker run", c'est cette commande qui sera lancée. Doit impérativement être au format exec (tableau au format json) pour assurer un traitement correct des signaux linux.
- `ENTRYPOINT ["<point-d-entrée>"]` : L'instruction ENTRYPOINT permet de spécifier un point d'entrée qui sera exécuté dans tous les cas, et reçoit la CMD (soit celle par défaut, soit celle surchargée apr celui qui lance le docker run) en tant que paramètre.  Ainsi si on définit un entrypoint à `/script.sh`  et que la CMD est "echo coucou", le conteneur lancera la commande `/script.sh echo coucou`, c'est à celui qui écrit l'entrypoint de prendre ses dispositions pour qu'on puisse passer des paramètres ou des surcharge de commande. En général, l'entrypoint est très utilisé pour venir configurer à la volée le conteneur à partir des variables d'environnement grâce à un script, lequel se termine généralement par un  `exec $@` permettant de lancer la CMD demandée.

Voici par exemple le Dockerfile de l'image [infrabuilder/mysql-aws-cli:mysql-5.7](https://github.com/InfraBuilder/docker-mysql-aws-cli/blob/mysql-5.7/Dockerfile) qui fournit un client mysql et aws-cli :

```dockerfile
FROM mysql:5.7
RUN	apt-get update \
	&& apt-get install -y \
		python3-pip \
		screen \
	&& pip3 install awscli \
	&& chmod 777 /run/screen \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

ENV	AWS_ACCESS_KEY_ID="" \
	AWS_SECRET_ACCESS_KEY="" \
	AWS_DEFAULT_REGION="" \
	AWS_DEFAULT_OUTPUT=""

CMD ["bash"]
```

Et un autre exemple, cette fois de l'image [infrabuilder/foldingathome](https://github.com/InfraBuilder/docker-foldingathome/blob/master/Dockerfile) qui permet de lancer le projet Folding@home pour aider la recherche :

```dockerfile
FROM ubuntu:18.04
ADD	https://download.foldingathome.org/releases/public/release/fahclient/debian-stable-64bit/v7.5/fahclient_7.5.1_amd64.deb /root/
RUN	mkdir -p /usr/share/doc/fahclient/ \
	&& mkdir /data \
	&& touch /usr/share/doc/fahclient/sample-config.xml \
	&& DEBIAN_FRONTEND=noninteractive apt-get install /root/fahclient_7.5.1_amd64.deb -y
WORKDIR /data
ENV	USER=Anonymous \
	TEAM=0 \
	PASSKEY="" \
	GPU="false" \
	CPUS="0" \
	FAHDIR=/data

CMD ["sh","-c","/usr/bin/FAHClient --user=${USER} --team=${TEAM} --passkey=${PASSKEY} --gpu=${GPU} --smp=true --cpus=${CPUS} --chdir=$FAHDIR"]
```

## A vous de jouer !

Votre mission, si vous l'acceptez, est de conteneuriser l'application présente dans le dossier [src](src).

Pour cela vous devez écrire le Dockerfile en suivant les instructions suivantes :

- L'application est écrite en PHP, et doit être servie par un serveur apache (*image php:7-apache fortement conseillée*)
- Vous devez ajouter l'extension PECL "redis" et l'activer (cf section [PECL extensions](https://hub.docker.com/_/php/))
- Vous devez activer le module Rewrite (avec la commande `a2enmod rewrite`)
- L'application est configurée grâce à deux variables d'environnement 
  - COLOR1 qui doit valloir par défaut "0000FF"
  - COLOR2 qui doit valloir par défaut "00FF00"
  - REDIS_HOST qui doit valloir par défaut "redis"
- Un script d'entrypoint vous est fourni : `files/entrypoint.sh`
- la commande par défaut doit être `apache2-foreground`
- votre image doit s'appeler : `tutoriel-php:v1`

**Comment tester ?**

Pour tester votre image, voici comment lancer un serveur redis :

```bash
docker run -d --name redis redis:5-alpine 
```

Et voici comment tester votre image :

```bash
docker run -it --rm --name web \
	-p 8080:80 \
	--link redis:redis \
	tutoriel-php:v1
```

Visitez http://localhost:8080 pour en vérifier le bon fonctionnement.

Vérifiez également que cela marche en changeant la configuration :

```bash
docker run -it --rm --name web \
	-p 8080:80 \
	-e COLOR1="FF0000" \
	-e COLOR2="FFFF00" \
	-e REDIS_HOST="myredis" \
	--link redis:myredis \
  tutoriel-php:v1
```

## Et si je n'y arrive vraiment pas ?

Consultez le fichier [ESJNYAP.md](ESJNYAP.md) pour avoir une solution possible !

