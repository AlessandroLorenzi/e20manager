# e20 Manager

A project to refresh some php/javascript

## Run the project in local

You just need `docker` and `docker-compose`

```bash
docker-compose build
docker-compose up -d
```

Install dependencies

```bash
docker-compose run composer install
```

Wait a few seconds to let mysql start and you can run sql migratinons and add some sample data

```bash
docker-compose exec mysql sh -c 'cat /sql/migrations/*.sql | mysql e20'
docker-compose exec mysql sh -c 'cat /sql/samples/*.sql | mysql e20'
```

Than you can browse the events on [http://localhost:8080/events/]
