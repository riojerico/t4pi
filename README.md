# t4pi

## Prerequisites
```
composer update
php artisan key:generate
```
linux
```
chmod -R 777 {dir}
```
Database
```
ubah field API_KEY otenuser menjadi text
```

## Endpoint

### 1. Create Oten [POST]
```
https://{URL}/general/create-oten
```
![image](https://user-images.githubusercontent.com/6455760/47700022-721b9880-dc47-11e8-879c-cc3a7e12ee7f.png)

### 2. Update Oten [PUT]
```
https://{URL}/general/update-oten/{field}/{kode/uname}
```
![image](https://user-images.githubusercontent.com/6455760/47700364-c6734800-dc48-11e8-8bc3-6e946da40053.png)

### 3. Get All User [GET]
```
http://{URL}/user/{key}
```
### 4. Create New User [POST]
```
http://{URL}/user/{key}
```
![image](https://user-images.githubusercontent.com/6455760/47700536-64ffa900-dc49-11e8-948c-2d52a446cb38.png)
