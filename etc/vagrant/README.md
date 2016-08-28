# README

Before you start please make sure you have latest Vagrant https://www.vagrantup.com available. 


### Prepare Vagrantfile

Go to dumplie shop project repository & execute following commands.
  
```
$ cd etc/vagrant
$ cp Vagrantfile.dist Vagrantfile
$ vagrant up
```

### Setup /etc/hosts

At your host machine add following line into `/etc/hosts` file. 

```
10.0.0.200  dumplie.local
```

*you might need to use sudo in order to modify /etc/hosts*

### Infrastructure Development

In order to modify `infrastructure` and test it locally 
without pushing changes to repository at github you need to modify
your Vagrantfile. 

**Before you start please sure you have following folder structure in your workspace:**

```
dumplie:
    - infrastructure 
    - dumplie
    - shop
```

When everything is prepared you just need to open Vagrantfile and make sure
following line is commented: 

```
# config.vm.provision :shell, path: "./../ansible/vagrant.sh", args: ["master"]
```

(just add # before `config`)

You also need to uncomment following line: 

```
config.vm.provision "ansible" do |ansible|
    ansible.playbook = "../infrastructure/ansible/dumplie.vagrant.provision.yml"
    ansible.inventory_path = "../ansible/inventory/hosts"
    ansible.limit = "vagrant"
end
```

Now you are ready to execute `vagrant provision`, from now vagrant will
user playbook available at your local machine instead of downloading 
it from github.