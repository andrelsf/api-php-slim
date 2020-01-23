FROM    nginx/unit:1.14.0-php7.3

RUN     mkdir /usr/src/app/ -p \
        #&& sed -i "s/deb http://deb.debian.org/debian buster-updates main/#deb http://deb.debian.org/debian buster-updates main/" /etc/apt/sources.list \
        && apt update || true \
        && apt install -y php7.3-zip php7.3-mysql php7.3-opcache \
        && apt-get autoremove -y \
        && apt-get autoclean -y \
        && apt-get clean -y 
        

WORKDIR /usr/src/app/

ADD    ./config.json /docker-entrypoint.d/