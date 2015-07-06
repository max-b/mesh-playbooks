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
pip install < requirements.txt
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

## Requirements

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

# Usage

`ansible all -m ping`
will do a connectivity test for all of the hosts listed in your inventory file

`ansible-playbook -i hosts`
will run all of the playbooks for the hosts listed in the hosts file

If you'd like to only run certain playbooks, you can use filter rules like:
`ansible-playbook -i hosts owncloud-servers.yml -vvvv`

