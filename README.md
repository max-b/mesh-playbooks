== Coordinating Server ==

# About

This is a mostly ansible setup for installing peoplesopen.net or other mesh services.

# Install Dependencies

```
apt-get install python-virtualenv python-netaddr
git clone https://github.com/max-b/mesh-playbooks.git
cd mesh-playbooks
virtualenv venv
source ./venv/bin/activate
pip install -r requirements.txt
```

You can then use commands like `ansible` or `ansible-playbook` on the command line in that directory.
When you're done, you can use `deactivate` which will reset your environment.
When you come back to work on it, you can just cd into the folder and then:

```
source ./venv/bin/activate
```


# Configuration

Copy `./hosts.sample` to `./hosts`

Make relevant changes. Remember to create the appropriate `./host_vars/` file. You can examine files in 
`./host_vars.sample` for relevant config sections.

Host inventory file is listed in the `hosts` file. Individual hosts can be given variable hostnames
and used as IP addresses or proper domain names. 

## Networking Setup
You can set up individual network configs in host_vars/{{hostname}}. For example, the contents of host_vars/raspi might include:

```
network_ether_interfaces:
 - device: eth0
   bootproto: dhcp
```
REMEMBER THAT THESE CHANGES HAVE THE POSSIBILITY OF LOCKING YOU OUT!

## Librarian-Ansible

I found that using librarian-ansible was a decent way to consume other folks more elaborate playbooks. 
It is a ruby gem, and the Gemfile is under /ruby, but I'm crossing my fingers that there won't really be a need to 
tinker with it too much.

To install playbooks in your Ansiblefile with verbose debug output, use:
```
librarian-ansible install --verbose
```

You'll likeley have to set up the librarian-install gem, which I'm afraid you're on your own with...


# Usage

## Sample Commands

`ansible all -m ping`
will do a connectivity test for all of the hosts listed in your inventory file

`ansible-playbook -i hosts`
will run all of the playbooks for the hosts listed in the hosts file

If you'd like to only run certain playbooks, you can use filter rules like:
`ansible-playbook -i hosts owncloud-servers.yml -vvvv`

## Recommended steps

### Setting up access

You'll first need to find the ip address of the server. There are a variety of ways of doing this, including running
dnsmasq on your own host machine, but it will also work perfectly fine if you just connect the ethernet port of the server to a 
server that provides dhcp leases. The only trick is finding the ip address of the host that's gotten the dhcp lease. Generally, with
openwrt, you can `cat /tmp/dhcp.leases` which will show you the ip address of the new host.

Once you have the ip address, you should copy your public key over to the host. Assuming that the ip address is 100.64.2.204, you'd want to do something like this:
```
ssh root@100.64.2.204
mkdir ~/.ssh
exit
scp ~/.ssh/id_rsa.pub root@100.64.2.204:~/.ssh/authorized_keys
```

Now you can login as root without a password.

### Bootstrapping Ansible client requirements

This will install the base Ansible client requirements 
```
ansible-playbook -i hosts bootstrapped.yml -vvvv
```

### Setting up hostname and fqdn

```
ansible-playbook -i hosts hostname-fix.yml -vvvv 
```

Make sure to set the following in your host_vars/ files:
```
hostname_name: "my-hostname"
hostname_domain: "sudomesh.org" ## or whatever other domain you want
```

After you've setup the hostname+domain name, you can use it in your ./hosts inventory file, so instead of:
`my-hostname ansible_ssh_host=100.64.2.204 ansible_ssh_user=root`

You could use:
`my-hostname ansible_ssh_host=my-hostname.sudomesh.org ansible_ssh_user=root`

### Installing the good stuff

From here we should be able to install owncloud and etherpad-lite and our monitoring services.

Installing owncloud:
```
ansible-playbook -i hosts owncloud-servers.yml -vvvv 
```

Your owncloud installation should be at: http://my-hostname.sudomesh.org:8081 (or the equivalent depending on your hostname and port config)


Installing etherpad:
```
ansible-playbook -i hosts etherpad-servers.yml -vvvv 
```

Your owncloud installation should be at: http://my-hostname.sudomesh.org:8082 (or the equivalent depending on your hostname and port config)


Setting up snmpd
Make sure to appropriately change your host_vars so that the snmp values are correct
```
ansible-playbook -i hosts monitored-devices.yml -vvvv 
```



# Notes

## bananian

### Resizing
If you follow the directions from https://www.bananian.org/download:
```
dd if=bananian-1504.img of=/dev/<your-sd-card> bs=1M && sync
```

You'll end up with a partition scheme which only allocates 2GB of storage to the root directory.
Use parted or gparted to resize the root partition so that it takes up an appropriate amount of the sd card
that you're installing the OS on.

### Initial setup

https://www.bananian.org/details 

has details about the stock bananian config, including default network parameters and usernames/passwords.
