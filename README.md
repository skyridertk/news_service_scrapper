## Motivation
>	Create a news parsing service from a news resource, for example hightload.today. The service must have a page displaying the list of downloaded news and a CLI command to start parsing.

## Goals
>	Parsing features:
>	- from each article, the download should and be saved:
>		- title
>		- short description
>		- picture
>		- date added
>	- when parsing, it is necessary to check the presence of the title in the database, and if the news is already in the database, make a note about the date and time of the last update
>	- database queries should be optimized for heavy load
>	- parsing should be in several parallel processes (via rabbitMQ)
>	- parsing must be run via cron
>	
>	Features of the page for viewing news from the database:
>	- the page for viewing news from the database should be available only after
>	- authorization in the system (registration is not required)
>	- Authorized users can be with one of two roles: admin or moderator (the administratorcan delete articles)
>	- there must be pagination at the end of the list of articles (10 per page)
>	
>	Stack:
>	- Symfony 5.4
>	- Php 7.4
>	- Mysql
>	- Bootstrap 5.1
>	- Docker (docker-compose)
>	- RabbitMQ



## Get Started

Source Parses: [IGN](https://za.ign.com/article/news)

Initial
```bash
composer install
```

Migrations
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

Run docker services
```bash
docker-compose up -d
```

Start Symfony server
```bash
symfony server:start
```


## Commands:

Run the consumer async
```bash
php bin/console messenger:consume async
```

Command for start scrapping via command line
```bash
php bin/console generateSources
```

Configure Cron to scrap sources periodically
```bash
php bin/console cron:create --name parser --command generateSources --schedule "5 4 * * *" --description "Executes Parser" --enabled=true
```


## Web Routes:
Login
```web
http://localhost:8000:/login
```

Dashboard
```web
http://localhost:8000:/fetch_all_sources
```

Register
```web
http://localhost:8000:/register
```

Invocke parse via browser
```web
http://localhost:8000:/send_message
```


## Configurations

RabbitMQ
>	connection:
>		vhost: news_broker
>		user: app
>		password: 1234
>		queue: messages
>		host: localhost
>		password: 5672

MySql
>	connection:
>		host: localhost:3306
>		user: app
>		db: app
>		password: 1234
>	
>	Generate 2 users
>		username: admin
>		password; 123456
>		
>		username: user
>		password:123456

Queries to generate users
```sql
INSERT INTO app.`user` (username, roles, password) VALUES('admin', '["ROLE_ADMIN"]', '$2y$13$7oGtl9/5ZEItsN8n8Et.tefuC6Ple04QAB8iTsG/lQe8UwYKy2LpC');
```

```sql
INSERT INTO app.`user` (username, roles, password) VALUES('user', '["ROLE_USER"]', '$2y$13$7oGtl9/5ZEItsN8n8Et.tefuC6Ple04QAB8iTsG/lQe8UwYKy2LpC');
```


