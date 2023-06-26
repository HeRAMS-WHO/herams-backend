# who-herams
[![codecov](https://codecov.io/gh/HeRAMS-WHO/herams-backend/branch/master/graph/badge.svg?token=7D48B4O2AM)](https://codecov.io/gh/HeRAMS-WHO/herams-backend)
[![Deploy to staging](https://github.com/HeRAMS-WHO/herams-backend/actions/workflows/build.yml/badge.svg)](https://github.com/HeRAMS-WHO/herams-backend/actions/workflows/build.yml)

# Set up developer environment

For specific Windows installation see [Windows setup](docs/dev/setup/Windows.md).

## Requirements
- Git must be available

## Steps
1. ```sudo apt update```
2. ```sudo apt install apt-transport-https ca-certificates curl software-properties-common```
3. ```curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -```
4. ```sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"```
5. ```sudo apt update```
6. ```sudo apt install docker-ce docker-compose```
7. ```sudo usermod -aG docker ${USER}```
8. Reboot your PC

9. Run the following commands:
 ```
 git clone git@github.com:WorldHealthOrganization/herams-backend.git
 cd herams-backend
 cp .env.default .env
 ```
10. Optionally alter `.env` to suit your preferences, it is recommended to set UID and GID to prevent permission issues also remove parameters that contains "/" like the smtp and database.
11. Run `docker compose up -d devdb testdb phpmyadmin nginx-api nginx`

## Result
After taking the above steps you will have everything up and running:
- A database with username `root` and password `secret`, and a user / password / database combo from your `.env` file.
- The database can be accessed from the phpmyadmin.
- An application with (invalid) email: `admin@user.com` and password: `Test12345`
- A mailcatcher allowing you to inspect mails sent by the system

### Commands available
We expose a number of commands via docker-compose:
- `docker-compose run --rm composer` will run composer, use this to install / update dependencies.
- `docker-composer run --rm codeception` will run the test suite
