#!/bin/bash
cp config.php.dist config.php
cp admin/config.php.dist admin/config.php
chmod -R 777 image/
chmod -R 777 image/cache/
chmod -R 777 image/data/   
chmod -R 777 system/cache/
chmod -R 777 system/logs/
chmod -R 777 download/
chmod -R 777 config.php
chmod -R 777 admin/config.php
