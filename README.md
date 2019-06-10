# hartley-contacts
A Test Project for Hartley

#Required
1. Docker
2. Docker Compose
3. Composer

#Run

```
sudo usermod -aG www-data $USER
```

```
cd /hartley-contacts
```

```
docker-compose up -d
```

```
docker exec -it www bash
```

```
composer update
```

```
sudo chown -hR www-data:www-data ../html
```

```
sudo find ../html -type f -exec chmod 664 {} \;
```

```
sudo find ../html -type d -exec chmod 775 {} \;
```

```
sudo find ../html -type d -exec chmod g+s {} \;
```

```
sudo chmod 777 -R stogare/ bootstrap/cache
```

```
php artisan:migrate
```

```
php artisan config:clear && \
php artisan cache:clear && \
composer dump-autoload && \
php artisan view:clear && \
php artisan route:clear && \
php artisan config:cache;
```
