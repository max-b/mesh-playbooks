<?php
$AUTOCONFIG = array(
  "dbtype"        => "mysql",
  "dbname"        => "owncloud",
  "dbuser"        => "owncloud",
  "dbpass"        => "{{ owncloud_owncloud_mysql_password }}",
  "dbhost"        => "localhost",
  "dbtableprefix" => "",
  "adminlogin"    => "root",
  "adminpass"     => "{{ owncloud_owncloud_root_password }}",
  "directory"     => "{{ owncloud_owncloud_data_dir }}", 
);
