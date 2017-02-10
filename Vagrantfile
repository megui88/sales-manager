
VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/xenial64"
  config.ssh.forward_agent = true

    config.vm.provider :virtualbox do |v|
        v.name = "salesmanager"
        v.customize [
            "modifyvm", :id,
            "--name", "salesmanager",
            "--memory", 2048,
            "--natdnshostresolver1", "on",
            "--cpus", 2,
        ]
        host = RbConfig::CONFIG['host_os']

          # Give VM 1/4 system memory & access to all cpu cores on the host
          if host =~ /darwin/
            cpus = `sysctl -n hw.ncpu`.to_i
            # sysctl returns Bytes and we need to convert to MB
            mem = `sysctl -n hw.memsize`.to_i / 1024 / 1024 / 4
          elsif host =~ /linux/
            cpus = `nproc`.to_i
            # meminfo shows KB and we need to convert to MB
            mem = `grep 'MemTotal' /proc/meminfo | sed -e 's/MemTotal://' -e 's/ kB//'`.to_i / 1024 / 4
          else # sorry Windows folks, I can't help you
            cpus = 2
            mem = 1024
          end
          v.customize ["modifyvm", :id, "--memory", mem]
          v.customize ["modifyvm", :id, "--cpus", cpus]
    end
  # nfs
  config.vm.synced_folder ".", "/vagrant", owner: "www-data", group:"www-data"
  # Network
  config.vm.network :private_network, ip: "192.168.40.10"
  config.ssh.forward_agent = true
  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "ansible-playbook.yml"
  end

  # Hack - To show the box name in the "global-status" list
  config.vm.define "salesmanager" do |salesmanager|
  end
end
