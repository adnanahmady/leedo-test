FROM adnanahmady/php:8.3-fpm-alpine

USER docker

RUN echo 'alias art="php artisan"' >> ~/.bashrc \
    && echo 't() { composer test -- $(echo $1 | sed "s/.*tests/tests/"); }' >> ~/.bashrc \
    && echo 'alias tf="composer test -- --filter"' >> ~/.bashrc \
    && echo 'make() { php artisan make:$@; }' >> ~/.bashrc \
    && echo 'alias fix="composer fix"' >> ~/.bashrc

USER root