#Daily usage

##Start the VM (Windows)
- From the command line in your host, from the folder where the VM is installed, run `vagrant up`
- Connect to the VM via either `vagrant ssh` or Putty (or others...).

##Start devproxy
- In the VM go the the `devproxy folder` and run `docker-compose up -d devproxy`

##Start HeRAMS Backend
- In the VM go the the `HeRAMS Backend folder` and run `docker-compose up -d nginx`

##Access Yii console application
- In the VM go the the `HeRAMS Backend folder` and run `docker-compose run --rm cli <command>`

##Running tests
- In the VM go the the `HeRAMS Backend folder`
- Run `composer test`
- Or run `composer test-with-cc` for tests with coverage

##Update test database export
First make sure the database is in a "starting state", meaning we "down" the container and up it again so the data files are loaded.
- In the VM go the the `HeRAMS Backend folder`
- Run `docker-compose down`
- Run `composer test`
- Run `docker-run --rm testcli database/update-test`
