#/bin/bash

while true; do 
    php app/console mon:scheduler:update 2 1
    sleep 5
    php app/console mon:scheduler:run 1 1
    sleep 5 
    php app/console mon:weather:calculate
    sleep 120
done
