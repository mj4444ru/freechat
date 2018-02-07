### Установка Node.js 8.x

```sh
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### Установка Typings

```sh
sudo npm install typings --global
```

### Поиск пакетов dt

```sh
typings search fancybox
```

### Добавление пакета

```sh
typings install dt~fancybox --global --save
```
