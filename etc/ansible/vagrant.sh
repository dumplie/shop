#!/usr/bin/env bash

GIT="git"
ANSIBLE="ansible"
PLAYBOOK_REPO_PATH="/home/vagrant/infrastructure"

if ! type "$ANSIBLE" > /dev/null; then
    sudo apt-get update
    sudo apt-get install -y software-properties-common
    sudo add-apt-repository -y ppa:ansible/ansible-1.9
    sudo apt-get update
    sudo apt-get install -y ansible
fi

if ! type "$GIT" > /dev/null; then
    sudo apt-get update
    sudo apt-get install -y git
fi

# Setup Ansible for Local Use and Run
cp /var/www/dumplie/shop/etc/ansible/inventory/hosts /etc/ansible/hosts -f
chmod 666 /etc/ansible/hosts
cat /var/www/dumplie/shop/etc/ansible/files/authorized_keys >> /home/vagrant/.ssh/authorized_keys

# Add github to known hosts
ssh-keyscan -H github.com >> ~/.ssh/known_hosts

# Clone repository, checkout to branch and pull last changes from that branch
if [ ! -d "$PLAYBOOK_REPO_PATH" ]; then
    git clone git@github.com:dumplie/infrastructure.git $PLAYBOOK_REPO_PATH
fi

cd /home/vagrant/infrastructure
git checkout $1
git fetch --all
git reset --hard origin/$1

sudo ansible-playbook $PLAYBOOK_REPO_PATH/ansible/dumplie.vagrant.provision.yml --connection=local