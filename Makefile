define default
$(if $(1),$(1),$(2))
endef

define print
@printf "\033[032;5;1m$(1)\033[0m\n"
endef

install:
	cp .env.example .env
	${MAKE} up
	${MAKE} shell run="composer install"
	${MAKE} shell run="composer run build"
	${MAKE} shell run="php artisan migrate --seed"
	${MAKE} test
	$(call print,Good to go)

up:
	@touch .backend/extra/history
	@docker compose ${up-as} up $(call default,${up-with},-d)

down:
	@docker compose down ${down-with}

restart:
	@docker compose restart ${service}

build:
	@touch .backend/extra/history
	${MAKE} up up-with="--build -d"

destroy:
	${MAKE} down down-with="--volumes"

shell:
	@docker compose exec $(call default,${service},backend) $(call default,${run},bash)

logs:
	@docker compose logs ${service}

logs.follow:
	@docker compose logs -f ${service}

ps:
	@docker compose ps

status: ps

test:
	${MAKE} shell run="composer test"