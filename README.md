## 📨 Symfony Messenger + RabbitMQ — Gebruik

Onderstaande stappen tonen hoe je de RabbitMQ-transporten aanmaakt, een testbericht verstuurt en de consumer start.

### 1. Transporten aanmaken in RabbitMQ
```bash
docker compose exec app php bin/console messenger:setup-transports
 ```

### 2. Consumer starten
```bash
docker compose exec app php bin/console messenger:consume divelog -vv
 ```
