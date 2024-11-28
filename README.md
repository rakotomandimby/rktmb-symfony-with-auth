# Install for development

## Set variables

```bash
export PROJECT_NAME=rktmb-symfony

export GW_IP='172.19.0.1'
export SUBNET_IP='172.19.0.0/29'
export NTW_NAME=${PROJECT_NAME}-network

export POSTGRES_DB=rktmb
export POSTGRES_USER=rktmb
export POSTGRES_PASSWORD=rktmb
export POSTGRES_LANG=en_US.utf8

export DOCKER_IMAGE=postgres:latest
export API_CONT_NAME=${PROJECT_NAME}-api
export DB_CONT_NAME=${PROJECT_NAME}-db
export PUB_PORT=5432
export PRIV_PORT=5432
```

## Create a network

```bash
docker network create --subnet ${SUBNET_IP} --gateway ${GW_IP} ${NTW_NAME}
```

## Create a database container

```bash
docker run -d \
    --name ${DB_CONT_NAME} \
    --network ${NTW_NAME} \
    -e POSTGRES_DB=${POSTGRES_DB} \
    -e POSTGRES_USER=${POSTGRES_USER} \
    -e POSTGRES_PASSWORD=${POSTGRES_PASSWORD} \
    -e LANG=${POSTGRES_LANG} \
    -p ${GW_IP}:${PUB_PORT}:${PRIV_PORT}  ${DOCKER_IMAGE}
```
## Create PGAdmin container

```bash
docker run -d \
    --name ${PROJECT_NAME}-pgadmin \
    --network ${NTW_NAME} \
    -e PGADMIN_DEFAULT_EMAIL=mihamina@rktmb.org \
    -e PGADMIN_DEFAULT_PASSWORD=mihamina \
    -e PGADMIN_DISABLE_POSTFIX=1 \
    -p ${GW_IP}:8080:80  dpage/pgadmin4:latest
```

To create the table `users`:

```sql
CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    first_name character varying(512),
    last_name character varying(512),
    roles character varying(1024)
);
```


To insert a user whose password is `mihamina`:

```sql
INSERT INTO public.users 
VALUES (
    1, 
    'test@example.com', 
    '$2a$12$rryh8u7AE27ySxMOstkMHuq4RX.sJefYN0W5JQ1.yqkLPOL.pjNBy', 
    'Mihamina', 
    'RAKOTOMANDIMBY', 
    'ROLE_MANAGER,ROLE_AGENT');

```

 It is using [bcrypt](https://bcrypt-generator.com/) to generate the password hash.
