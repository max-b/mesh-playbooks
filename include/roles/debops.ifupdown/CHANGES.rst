Changelog
=========

v0.2.2
------

*Released: 2015-08-08*

- Streamline directory creation tasks and make sure required packages are
  installed. [le9i0nx]

- Make sure that Ansible does not stop if a variable is undefined. This change
  fixes issues with the missing variables in Ansible v2. [drybjed]

v0.2.1
------

*Released: 2015-06-01*

- Add a text block variable with options for bridge interfaces which becomes
  active when user does not specify any options for that bridge. By default
  these options will set forward delay to ``0`` to make DHCP queries work
  correctly on virtual machine boot. [drybjed]

v0.2.0
------

*Released: 2015-05-30*

- Expose path to reconfiguration script in a default variable, so that it can
  be changed if needed. [drybjed]

- Add variable with list of APT packages to install and automatically install
  certain packages depending on what interface types are present in the
  configuration. [drybjed]

- Add an option to ignore "static" configuration in
  ``/etc/network/interfaces``. [drybjed]

- Change reconfiguration script ``logger`` command to not cut the emitted
  string after first variable. And it looks cleaner now. [drybjed]

- Interface configuration overhaul.

  Most changes are related to configuration templates. Instead of having
  duplicate configuration in each of the templates, most of the configuration
  is now in ``interface.j2`` template; other templates extend this one.

  ``item.aliases`` list has been removed. Instead, there's now new parameter,
  ``item.addresses``. This is a list of IP addresses in the ``host/prefix``
  notation which should be set on a given interface. You can specify multiple
  IPv4 or IPv6 addresses this way, and role will generate correct configuration
  depending on if the interface is set in ``dhcp`` or ``static`` mode.

  You can "augment" current interface configuration using separate dict
  variables in Ansible inventory, in the format
  ``ifupdown_map_<type>_<variable>``, each dict should have an interface name
  as the key and list or string of parameters you want to add/change. For
  example, to add additional IP addresses to an interface using inventory, you
  can specify them as::

      ifudpdown_map_interface_addresses:
        'br0': [ '192.0.2.0/24', '2001:db8:dead:beef::1/64' ]

  List of possible dict variables will be added in the documentation in
  a separate commit. [drybjed]

v0.1.2
------

*Released: 2015-05-24*

- Check first argument in the delayed ifup script, if it's ``false``, specified
  interface won't be brought up at all. [drybjed]

- Remove management if ``ifup@.service`` unit symlinks for configured
  interfaces. ``ifupdown`` and ``/etc/init.d/networking`` scripts work just
  fine without them present. [drybjed]

- Split ``interface_enabled`` list into two to better track what types of
  interfaces are enabled. Additionally, send list of configured interfaces to
  the syslog for debugging purposes. [drybjed]

- Add ``item.port_active`` parameter to bridge configuration.

  If this parameter is set, specified ``item.port`` or ``item.port_present``
  must be in a given active state (``True`` / ``False``) to configure the
  bridge.

  This helps mitigate an issue where bridge with DHCP configuration is
  constantly running ``dhclient`` when its main interface is not connected to
  the network. [drybjed]

- Add a way to postpone interface configuration entirely using a separate
  temporary script, with optional pre- and post- commands. This script will be
  run at the end of the current play, or can be executed independently.
  [drybjed]

v0.1.1
------

*Released: 2015-05-12*

- Add ``item.port_present`` parameter in bridge configuration. It can be used
  to enable or disable specific bridge interface depending on presence of
  a given network interface in ``ansible_interfaces`` list, but does not affect
  the configuration of the bridge itself. [drybjed]

- Clean up ``allow-auto`` and ``allow-hotplug`` options in interface
  configuration. By default both of these parameters will be added
  automatically by ``debops.ifupdown`` to most of the interface types unless
  specifically disabled.

  This tells the system to start the interfaces at boot time, as well as allows
  to control specific interfaces by the hotplug events using ``ifup`` and
  ``ifdown`` commands or ``ifup@.service`` under ``systemd``. [drybjed]

- Add IPv6 SLAAC configuration on all default interfaces; this is required on
  Debian Jessie to enable IPv6 address autoconfiguration.  [drybjed]

- Rewrite network interface configuration logic.

  Generate interface configuration in a separate
  ``/etc/network/interfaces.config.d/`` directory instead of directly in
  ``/etc/network/interfaces.d/`` directory. Provide original configuration at
  first run of the role, which is required to properly shut down all network
  interfaces, when state of the networking configuration is undefined.

  Instead of disabling and enabling network interfaces directly using Ansible
  tasks and ``ifup`` / ``ifdown`` commands, delegate the reconfiguration
  process to an external script installed on the host. The script will properly
  disable and enable interfaces in systems using sysvinit, upstart and systemd.

  The ifupdown configuration script will shut down all network interfaces on
  the first run of the ``debops.ifupdown`` role, apply configuration changes
  from the ``/etc/network/interfaces.config.d/`` directory to
  ``/etc/network/interfaces.d/`` directory and then start only enabled
  interfaces using ``ifup`` command or ``ifup@.service`` systemd service. Only
  network interfaces which have been modified will be enabled/disabled on
  subsequent runs. [drybjed]

- Add a way to delay activation of specific network interface.

  A network interface can be prepared beforehand by ``debops.ifupdown`` role,
  then additional configuration can be performed (for example an OpenVPN/tinc
  VPN, GRE tunnel, etc.) and after that the other role can run the script
  prepared by ``debops.ifupdown`` in a known location to start the interface.

  This option is enabled by adding ``item.auto_ifup: False`` to interface
  configuration. [drybjed]

v0.1.0
------

*Released: 2015-04-20*

- First release, add Changelog. [drybjed]

