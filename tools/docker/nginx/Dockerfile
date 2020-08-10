FROM nginx:1

RUN rm /etc/nginx/conf.d/default.conf

COPY tools/docker/nginx/conf.d/* /etc/nginx/conf.d/

COPY web /app/web

WORKDIR /app/web
