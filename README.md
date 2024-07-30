## Requirement
- [x] Docker
- [x] Docker Compose
- [x] Mikrotik Service API enable

## Library
<!-- - [x] [Laravel Versi 7.30.6](https://laravel.com/docs/7.x) -->
- [x] [RouterOS API PHP](https://github.com/EvilFreelancer/routeros-api-php)
- [x] [Laravel - AdminLTE](https://github.com/mairorodrigues/Laravel-AdminLTE)

## DB
- [x] MySQL

## Credential
- [x] Login Developer
    - username: root@root.com
    - password: admin2000
- [x] Login Admin
    - username: admin@admin.com
    - password: admin
- [x] MySQL ROOT
    - username: root
    - password: admin2000
- [X] MySQL User
    - username: admin
    - password: admin2000

## How To Use
1. Pastikan `docker` dan `docker-compose` sudah terinstall
2. Jalankan command berikut
```bash
cd mikrotik-routeros-php

docker-compose build

docker-compose up -d

docker-compose exec -u 0 web bash

# inside docker
php artisan migrate:fresh

php artisan db:seed
```
3. Lanjutkan dengan akses ke [localhost](http://localhost/)

## Setup App

- [x] database/seeders/ConfigTableSeeder.php 
    - Untuk ubah nama aplikasi yang muncul
    - setelah ubah file ini, jangan lupa untuk menjalankan `php artisan db:seed`

- [x] Setup scheduler untuk auto-update traffic
    - /System/Clock
      - Sesuaikan sesuai jam saat ini
    - /system/scheduler
      - Name : collector-traffic-1m
      - Interval : 00:01:00
      - Source : /tool fetch url="http://localhost:8001/traffic/monitor/add" mode=http 
    
    atau bisa menggunakan menu di **scheduler** --> **generate default scheduler**

## Update ENV 28 Mei 2023
- TRAFFIC_REFRESH --> auto reload grafik traffic (miliseconds)
- SCHEDULER_TRAFFIC_NAME --> nama scheduler yang scr default untuk generate traffic 
- SCHEDULER_TRAFFIC_EVENT --> script untuk generate traffic
- SCHEDULER_TRAFFIC_INTERVAL --> interval pengambilan data