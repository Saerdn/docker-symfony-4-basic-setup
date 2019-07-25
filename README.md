# Symfony 4 + Docker basic setup
Basic Symfony 4 setup with Docker, containing two entities (parent + child), basic user management
(admin/user), login/authorization implementation and basic tests (functional and unit).

##  Content

1. Sources
2. Features
3. How to start
    1. Start docker
    2. Start/Install project
4. Run tests


# 1. Features
- Login
- Authorization (Admin vs custom user)
    - Admins can create new users
    - Users loaded via fixtures:
        - testadmin@docker-symfony.de => abcf (Admin)
        - testuser@docker-symfony.de => qwertz (User)
        - testuser2@docker-symfony.de => asdf (User)
- Parent entity
- Child entity

# 2. Sources used
- jQuery 3.1.1
- Bootstrap 3.7.7
- Symfony 4
- MySQL 8
- phpMyAdmin


## 3. How to start

### 3.1 Start docker

In root folder of the project (outside docker container)
``` console
$ docker-compose up
```

To run docker-compose in the background, add -d
``` console
$ docker-compose up -d
```

See all running container
```console
$ docker ps
```

Connect to the running php container (as user "dev")
```console
$ docker exec -it --user=dev symfony_php bash
```


### 3.2 Start/Install project
In root folder of the project (inside docker container)

Run project the first time
``` console
$ make install
```

Reset everything (without installing dependencies)
``` console
$ make init
```

- The frontend should be available at __http://localhost__
- phpMyAdmin should be available at __http://localhost:8080__


## 4. Run tests

*All tests*
``` console
$ make run-tests
```

*Unit tests*
``` console
$ make run-unit-tests
```

*Functional tests*
``` console
$ make run-function-tests
```
