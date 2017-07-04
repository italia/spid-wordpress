# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  ENV["LC_ALL"] = "en_US.UTF-8" # set locale for SSH agent

  config.vm.box = "bento/ubuntu-16.04"
  config.vm.hostname = 'spid-wordpress'

  config.vm.synced_folder ".", "/vagrant", disabled: true
  config.vm.synced_folder "./spid-wordpress", "/spid-wordpress"

  config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"

  config.vm.provider "virtualbox" do |vb|
    vb.name = "spid-wordpress"
    vb.customize [ "modifyvm", :id, "--uartmode1", "disconnected" ] # disable cloudimg-console.log
  end

  config.vm.provision "shell", path: "./scripts/bootstrap.sh"

end
