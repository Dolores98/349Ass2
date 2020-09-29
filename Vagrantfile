# -*- mode: ruby -*-
# vi: set ft=ruby :

# A Vagrantfile to set up three VMs, a webserver, a database server,
# and a rootwebserver, connected together using an internal network
# with manually-assigned IP addresses for the VMs.

Vagrant.configure("2") do |config|
  # We are going to ubuntu/xenial64 base box for all three VMs
  config.vm.box = "ubuntu/xenial64"

  # The webserver VM will be a webserver containing all the pages that
  # non-root users will be able to access.
  config.vm.define "webserver" do |webserver|
    # These are options specific to the webserver VM.
    webserver.vm.hostname = "webserver"
    
    # This will allow host computer to connect to localhost via port 8080,
    # to port 80 which is used by the VM.
    webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    
    # This sets up a private network that our VMs will use to communicate
    # with each other. 
    webserver.vm.network "private_network", ip: "192.168.2.11"

    # Required for some computers.
    webserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]

    # The follwing shell commands will set up apache2 which we are going to use
    # as our webserver.
    webserver.vm.provision "shell", inline: <<-SHELL
      # We need to ensure packages are up to date.
      apt-get update

      # Install apache2 and php and sql support.
      apt-get install -y apache2 php libapache2-mod-php php-mysql
            
      # Change VM's webserver's configuration to use shared folder.
      # (Look inside test-website.conf for specifics.)
      cp /vagrant/website.conf /etc/apache2/sites-available/

      # activate our website configuration ...
      a2ensite website

      # ... and disable the default website provided with Apache
      a2dissite 000-default

      # Reload the webserver configuration, to pick up our changes
      service apache2 reload

      # Here we are going to enable ufw firewall for added security
      # ubuntu should come with these but we are going to install 
      # just incase.
      apt-get install ufw
      ufw allow ssh
      ufw allow http
      ufw allow https
      ufw allow 80/tcp
      ufw enable
    SHELL
  end

  # The webserver VM will be a webserver containing all the pages that
  # root users will be able to access.
  config.vm.define "rootserver" do |rootserver|
    # These are options specific to the root webserver VM.
    rootserver.vm.hostname = "rootserver"
    
    # This will allow host computer to connect to localhost via port 8090,
    # to port 80 which is used by the VM.
    rootserver.vm.network "forwarded_port", guest: 80, host: 8090, host_ip: "127.0.0.1"
    
    # This sets up a private network that our VMs will use to communicate
    # with each other. 
    rootserver.vm.network "private_network", ip: "192.168.2.13"

    # Required for some computers.
    rootserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]

    # The follwing shell commands will set up apache2 which we are going to use
    # as our webserver.
    rootserver.vm.provision "shell", inline: <<-SHELL
      # We need to ensure packages are up to date.
      apt-get update

      # Install apache2 and php and sql support.
      apt-get install -y apache2 php libapache2-mod-php php-mysql
            
      # Change VM's webserver's configuration to use shared folder.
      # (Look inside test-website.conf for specifics.)
      cp /vagrant/rootsite.conf /etc/apache2/sites-available/

      # activate our website configuration ...
      a2ensite rootsite

      # ... and disable the default website provided with Apache
      a2dissite 000-default

      # Reload the webserver configuration, to pick up our changes
      service apache2 reload

      # Here we are going to enable ufw firewall for added security
      # ubuntu should come with these but we are going to install 
      # just incase.
      apt-get install ufw
      ufw allow ssh
      ufw allow http
      ufw allow https
      ufw allow 80/tcp
      ufw enable
    SHELL
  end

  # This VM will be our database server.
  config.vm.define "dbserver" do |dbserver|
    dbserver.vm.hostname = "dbserver"
    # Provide the VM with its unique IP address within the private network.
    dbserver.vm.network "private_network", ip: "192.168.2.12"

    # Required for some computers.
    dbserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
    # The following shell commands will setup mysql server in the VM. 
    # We will be using MYSQL.
    dbserver.vm.provision "shell", inline: <<-SHELL
      # Update Ubuntu software packages.
      apt-get update
      
      # We create a shell variable MYSQL_PWD that contains the MySQL root password
      export MYSQL_PWD='insecure_mysqlroot_pw'

      # If you run the `apt-get install mysql-server` command
      # manually, it will prompt you to enter a MySQL root
      # password. The next two lines set up answers to the questions
      # the package installer would otherwise ask ahead of it asking,
      # so our automated provisioning script does not get stopped by
      # the software package management system attempting to ask the
      # user for configuration information.
      echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections 
      echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections

      # Install the MySQL database server.
      apt-get -y install mysql-server

      # Run some setup commands to get the database ready to use.
      # First create a database.
      echo "CREATE DATABASE passwordmanager;" | mysql

      # We create a database user "passuser" with the given password.
      echo "CREATE USER 'passuser'@'%' IDENTIFIED BY 'passwordmanagerpass';" | mysql

      # Grant all permissions to the database user "passuser" regarding
      # the "passwordmanager" database that we just created, above.
      echo "GRANT ALL PRIVILEGES ON passwordmanager.* TO 'passuser'@'%'" | mysql
      
      # Set the MYSQL_PWD shell variable that the mysql command will
      # try to use as the database password.
      export MYSQL_PWD='passwordmanagerpass'

      # Run all of the SQL queries within the setup-database.sql file.
      # The mysql command specifies both the user to connect as (passuser) 
      # and the database to use (passwordmanager).
      cat /vagrant/setup-database.sql | mysql -u passuser passwordmanager

      # By default, MySQL only listens for local network requests,
      # i.e., that originate from within the dbserver VM. We need to
      # change this so that the webserver VM can connect to the
      # database on the dbserver VM. Use of `sed` is pretty obscure,
      # but the net effect of the command is to find the line
      # containing "bind-address" within the given `mysqld.cnf`
      # configuration file and then to change "127.0.0.1" (meaning
      # local only) to "0.0.0.0" (meaning accept connections from any
      # network interface).
      sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

      # We then restart the MySQL server to ensure that it picks up
      # our configuration changes.
      service mysql restart

      # Here we are going to enable ufw firewall for added security
      # ubuntu should come with these but we are going to install 
      # just incase.
      apt-get install ufw
      ufw allow ssh
      ufw allow http
      ufw allow https
      ufw allow 80/tcp
      ufw enable
    SHELL
  end

end

#  LocalWords:  webserver xenial64
