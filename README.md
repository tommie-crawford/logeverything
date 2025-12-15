## LogEveryThing

Ik gebruik deze applicatie om al mijn hobbies & sportactiviteiten te loggen. Ik wil hiermee ervaring krijgen in het symfony framework. 

Gebruikte technieken: 

- Symfony 7.4
- RabbitMQ
- Doctrine ORM
- Docker
- PHPUnit
## 📨 Symfony Messenger + RabbitMQ — Gebruik

Onderstaande stappen tonen hoe je de RabbitMQ-transporten aanmaakt en de consumer start.

### 1. Transporten aanmaken in RabbitMQ
```bash
docker compose exec app php bin/console messenger:setup-transports
 ```

### 2. Consumer starten
```bash
docker compose exec app php bin/console messenger:consume async -vv
 ```
