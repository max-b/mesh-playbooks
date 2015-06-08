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

## OpenVPN Server
I'm using a cheap, $5/mo droplet from digital ocean. It's ubuntu 14.04 lts, although I assume that debian would
work as well. All you have to do is make sure that your ssh pub key is in the authorized_keys file. Ansible *should*
take care of the rest

# Usage

`ansible all -m ping`
will do a connectivity test for all of the hosts listed in your inventory file

`ansible-playbook -i hosts`
will run all of the playbooks for the hosts listed in the hosts file

If you'd like to only run certain playbooks, you can use filter rules like:
`ansible-playbook -i hosts cloud-servers.yml  --limit cloud-servers -vvvv`

