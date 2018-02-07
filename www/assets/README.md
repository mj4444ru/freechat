### Получение sha384

```sh
cat FILENAME.js | openssl dgst -sha384 -binary | openssl enc -base64 -A
```