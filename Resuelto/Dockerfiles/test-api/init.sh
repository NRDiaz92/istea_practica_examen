#!/bin/bash

CREATE_ENV_FILE () {
    echo 'SQL_PASSWORD='"$(redis-cli -a ${REDIS_PASSWORD} -h test-redis get sql_password)"'' >> /var/app/.env
    echo 'SQL_USER='"$(redis-cli -a ${REDIS_PASSWORD} -h test-redis get sql_user)"'' >> /var/app/.env
    echo 'SQL_DB='"$(redis-cli -a ${REDIS_PASSWORD} -h test-redis get sql_db)"'' >> /var/app/.env
    echo 'SQL_HOST='"$(redis-cli -a ${REDIS_PASSWORD} -h test-redis get sql_host)"'' >> /var/app/.env
    echo 'SQL_TABLE='"$(redis-cli -a ${REDIS_PASSWORD} -h test-redis get sql_table)"'' >> /var/app/.env
}

SERVICE_START () {
    service php7.4-fpm start
    service nginx start
}

LOGS () {
    tail -f -n 50 /var/log/nginx/*
}

SERVICE_TEST () {
    if [[ $(service php7.4-fpm status) = $0 &&  $(service nginx status) = $0 ]]
        then echo "ok"
        else echo "Services failed"; exit 1
    fi
}

API_READY () {
    if [[ SERVICE_TESTS = 'ok' ]]
        then echo "API Ready"; exit 0
        else echo "API not working"; exit 1
    fi
}

START_API () {
    CREATE_ENV_FILE
    SERVICE_START
    LOGS
}

COMMAND=$1
$COMMAND