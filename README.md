== Coordinating Server ==

# About

This is a mostly ansible setup which will allow us to better manage
host machines as we do PILO SDN experiments in our physical testbed.

# Install Dependencies

This requires ansible to be installed on the host system. Installation, setup, etc can be found here:
http://docs.ansible.com/intro_installation.html

# Configuration
Host inventory file is listed in the `hosts` file. Individual hosts can be given variable hostnames
and used as IP addresses or proper domain names. 

## Requirements
python-netaddr

## Networking Setup
You can set up individual network configs in host_vars/{{hostname}}. For example, the contents of host_vars/denovo_soekris might include:

```
network_ether_interfaces:
 - device: eth1
   bootproto: static
   address: 10.1.1.2
   netmask: 255.255.255.0
   gateway: 10.1.1.1
 - device: eth0
   bootproto: dhcp
```
REMEMBER THAT THESE CHANGES HAVE THE POSSIBILITY OF LOCKING YOU OUT!

## OpenVPN Server
I'm using a cheap, $5/mo droplet from digital ocean. It's ubuntu 14.04 lts, although I assume that debian would
work as well. All you have to do is make sure that your ssh pub key is in the authorized_keys file. Ansible *should*
take care of the rest

## Librarian-Ansible
I found that using librarian-ansible was a decent way to consume other folks more elaborate playbooks. 
It is a ruby gem, and the Gemfile is under /ruby, but I'm crossing my fingers that there won't really be a need to 
tinker with it too much.

# Usage

`ansible all -m ping`
will do a connectivity test for all of the hosts listed in your inventory file

`ansible-playbook -i hosts`
will run all of the playbooks for the hosts listed in the hosts file

If you'd like to only run certain playbooks, you can use filter rules like:
`ansible-playbook -i hosts cloud-servers.yml  --limit cloud-servers -vvvv`

