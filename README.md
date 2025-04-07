# Реопзиторий перенесён в Bitbucket!!! Версия из github более не поддерживается!

# Библиотека RequestMetadata

## Инициализация

Инициализация существующим значением идентификатора:
```php
$requestMetadata = new RequestMetadata();
$requestMetadata->set(RequestMetadata::ATTR_REQUEST_ID, 'request-id-value');
```

Генерация нового идентификатора:
```php
$requestMetadata = new RequestMetadata();
$requestMetadata->init();
```
