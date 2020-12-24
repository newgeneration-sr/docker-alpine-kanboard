![docker build automated](https://img.shields.io/docker/cloud/automated/dotriver/kanboard)
![docker build status](https://img.shields.io/docker/cloud/build/dotriver/kanboard)
![docker build status](https://img.shields.io/docker/cloud/pulls/dotriver/kanboard)

# Kanboard on Alpine Linux + S6 Overlay

# Auto configuration parameters :

- DATABASE_HOST=mariadb
- DATABASE_PORT=3306
- DATABASE_NAME=kanboard
- DATABASE_USERNAME=kanboard
- DATABASE_PASSWORD=password
- ADMIN_PASSWORD=password
- USE_LDAP=TRUE                   (use ldap to authenticate)
- LDAP_BASE_DN=dc=example,dc=com
- LDAP_HOST=fd
- LDAP_ADMIN_PASS=password
- LDAP_ADMIN_GROUP=admin          (the ldap group containing etherpad admins)
- APP_LANGUAGE=fr_FR

# Compose file example

```
version: '3.1'

services:

  kanboard:
    image: dotriver/kanboard
    environment:
        - DATABASE_HOST=mariadb
        - DATABASE_PORT=3306
        - DATABASE_NAME=kanboard
        - DATABASE_USERNAME=kanboard
        - DATABASE_PASSWORD=password
        - ADMIN_PASSWORD=password
        - USE_LDAP=TRUE
        - LDAP_BASE_DN=dc=example,dc=com
        - LDAP_HOST=fd
        - LDAP_ADMIN_PASS=password
        - LDAP_ADMIN_GROUP=admin
        - APP_LANGUAGE=fr_FR
    ports:
      - 8080:80
    volumes:
      - /tmp/kanboard:/var/www/kanboard/
    networks:
      default:
    deploy:
      resources:
        limits:
          memory: 256M
      restart_policy:
        condition: on-failure
      mode: global

  mariadb:
    image: dotriver/mariadb
    environment:
      - ROOT_PASSWORD=password
      - DB_0_NAME=kanboard
      - DB_0_PASS=password
    ports:
      - 3306:3306
      - 8081:80
    volumes:
      - mariadb-data:/var/lib/mysql/
      - mariadb-config:/etc/mysql/
    networks:
      default:
    deploy:
      resources:
        limits:
          memory: 256M
      restart_policy:
        condition: on-failure
      mode: global

  fd:
    image: dotriver/fusiondirectory
    ports:
      - "8082:80"
    environment:
      DOMAIN: example.com
      ADMIN_PASS: password
      CONFIG_PASS: password
    volumes:
      - openldap-data:/var/lib/openldap/
      - openldap-config:/etc/openldap/

volumes:
    mariadb-data:
    mariadb-config:
    openldap-config:
    openldap-data:

```