#Set up developer environment on Windows

##Tested versions
The mentioned versions of software below have been verified to work.
- Windows 10
- Vagrant 2.2.16
- Virtual Box 6.1.22
- PHP8.0 (18-11-2021 PHP8.1 resulted in errors)

##Install software
Make sure you download the correct version mentioned above or are willing to test a new version and please update the docs in that case.
- [Vagrant](https://www.vagrantup.com/downloads)
- [Virtual Box](https://www.virtualbox.org/wiki/Download_Old_Builds_6_1)

##Creating the virtual machine
The next steps are performed from the command line. This can be done by the normal command prompt, [Git Bash](https://gitforwindows.org/) or any command line tool of your preference.
- Go to the folder where you want the VM to be created, make sure you have the correct permissions on this folder, i.e. `C:\Users\<user>\vms` and not `C:\vms`
- Run `vagrant box add generic/ubuntu2004` with `virtualbox` as provider
- Run `vagrant init generic/ubuntu2004`, which will create a Vagrantfile, open this file with a text editor like [Notepad++](https://notepad-plus-plus.org/downloads/)
  - Optionally set an IP address for your VM (change the IP to your desired IP):
    - `config.vm.network "private_network", ip: "192.168.10.11"`
  - Add a shared folder of you host PC to the VM (change the dirs to your correct dirs):
    - `config.vm.synced_folder "C:\\Projects\\who-herams", "/projects/who-herams"`
  - Enable symbolic links by adding the next lines to the Vagrantfile, before the last `end`
    - ```
      config.vm.provider "virtualbox" do |v|
        v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
      end
      ```
    - It can be required to update some Windows permissions to enable the symbolic links: https://superuser.com/questions/994093/give-an-application-symlink-permissions-within-a-directory-in-windows

##Running the VM
- Browse to the folder of the VM in the command line
- Run `vagrant up`

##Connecting to the VM
- This can be done by browsing in the command line to the folder where the VM is and running `vagrant ssh`, or connect to the configured IP address via i.e. [Putty](https://www.putty.org/) using username `vagrant` and password `vagrant`.

##Complete installation of VM
- Connect to the VM
- Install [Docker](https://docs.docker.com/engine/install/ubuntu/)
- Install [Docker Compose](https://docs.docker.com/compose/install/)
- Add user to Docker group by running `sudo usermod -aG docker $USER`, reconnect to the VM afterwards
- Run `sudo apt install software-properties-common`
- Run `sudo add-apt-repository ppa:ondrej/php`
- Run `sudo apt install php8.0-cli php8.0-curl php8.0-mbstring php8.0-dom php8.0-gd php8.0-mysql php8.0-intl php8.0-zip`
- Download [Composer](https://getcomposer.org/download/)
  - Also perform the 'Global install' part, meaning running the `sudo mv composer.phar /usr/local/bin/composer` part
- Run `sudo apt install php-dev zlib1g-dev`
- Run `sudo pecl install xlswriter`
- Browse to `/etc/php/8.0/mods-available`
- Copy for example `xmlwriter.ini` to `xlswriter.ini` by i.e. `cp xmlwriter.ini xlswriter.ini`
- Open `xlswriter.ini` and make sure `xlswriter.so` is enabled
- Browse to `/etc/php/8.0/cli/conf.d` run `sudo ln -s /etc/php/8.0/mods-available/xlswriter.ini 20-xlswriter.ini`
- Run `sudo apt install npm`

##Cloning the repository
This part assumes you know how Git works, you can perform these steps either from inside the VM or from the host.
- Inside of the mapped directory clone the devproxy repository: `git clone git@github.com:SAM-IT/devproxy.git`
- Inside of the mapped directory clone the HeRAMS Backend repository: `git clone git@github.com:HeRAMS-WHO/herams-backend.git`
- In HeRAMS Backend, copy `.env.default` to `.env` and fill out the secrets (ask for help if needed)
- In Devproxy create a file `docker-compose.override.yml` with the following content:
  - ```yaml
    version: '3.8'
    services:
      devproxy:
        ports:
        - "0.0.0.0:80:80"
        - "0.0.0.0:443:443"
- In HeRAMS Backend, run `composer install`, possibly requires the option `--ignore-platform-reqs`

##Configure hosts file
- Open (i.e. Notepad++ **in administrator mode**) the hosts file (`C:\Windows\System32\drivers\etc\hosts`)
- Add the following lines (change IP addresses to the configured IP address of your VM)
  - ``` 
    192.168.10.11 devproxy.test
    192.168.10.11 phpmyadmin.herams.test
    192.168.10.11 mailcatcher-herams.test
    192.168.10.11 herams.test
    192.168.10.11 coverage.herams.test

##Setting up devproxy
- Inside the VM in the Devproxy folder
- Run `docker network create devproxy`
- Run `docker-compose up -d devproxy`
- On the host, open a browser and browse to `https://devproxy.test`
- Download the CA certificate and install it in your browser as a Certificate Authority

##Daily usage
See [daily usage](DailyUsage.md).
