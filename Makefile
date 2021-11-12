start:
	docker-compose build
	docker-compose up -d

sql_migrations:
	docker-compose exec mysql sh -c 'cat /sql/migrations/*.sql | mysql e20'

sql_samples:
	docker-compose exec mysql sh -c 'cat /sql/samples/*.sql | mysql e20'

sql: sql_migrations sql_samples