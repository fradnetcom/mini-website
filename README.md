Install mini-website
====================

Clone repository
---

```
git clone https://github.com/fradnetcom/mini-website.git
```

Install composer
---
```
composer install
```

Prepare .htaccess
---
```
ln -s web/.htaccess_<env> web/.htaccess
```

ACL
---
Set ACL (linux/bsd):
```
HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)

sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
```
more:
https://symfony.com/doc/current/setup/file_permissions.html

Prepare web file
---
```
php bin/console assets:install --symlink
php bin/console assetic:dump
php bin/console cache:clear
```