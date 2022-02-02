#!/bin/bash

RED='\033[0;31m'
ORANGE='\033[0;33m'
NC='\033[0m'

function isRoot() {
	if [ "${EUID}" -ne 0 ]; then
		echo -e "Harap jalankan script ini sebagai root ${ORANGE}\"sudo !!\"${NC}"
		exit 1
	fi
}

function fileExists() {
    if [ ! -d "./html/" ]; then
        echo -e "folder ${ORANGE}html${NC} tidak ditemukan"
        exit 1
    fi

    if [ ! -f "./adminPanel.sql" ]; then
        echo "file ${ORANGE}adminPanel.sql${NC} tidak ditemukan"
        exit 1
    fi

    if [ ! -f "./controlPanel.conf" ]; then
        echo "file ${ORANGE}controlPanel.conf${NC} tidak ditemukan"
        exit 1
    fi

}

function initialCheck() {
	isRoot
	fileExists
}

function installQuestions() {
    echo -e "Selamat datang ke Instalasi FreeRadius + Adminpanel!"
    echo -e "Silahkan masukkan informasi dibawah ini:"
    echo ""

    read -s -p "Masukkan mysql Password Baru root: " rootPass
    echo ""

    read -p "Masukkan mysql Username Baru untuk Radius: " radiusUser
    read -s -p "Masukkan mysql Password Baru untuk Radius: " radiusPass
    echo ""

    read -p "Masukkan mysql Username Baru untuk Adminpanel: " adminUser
    read -s -p "Masukkan mysql Password Baru untuk Adminpanel: " adminPass
    echo ""

    read -p "Masukan IP Address Router Mikrotik: " routerIP
    read -s -p "Masukan Secret untuk Router Mikrotik: " routerSecret
    echo -e "\n\n"


    echo -e "${ORANGE}Baik, semua informasi sudah diterima, lanjutkan instalasi?${NC}"
    read -n1 -r -p "Tekan sembarang tombol untuk melanjutkan..."
}

function installMariaDB() {
    echo -e "${ORANGE}Memulai Instalasi MariaDB...${NC}"
    apt install mariadb-server mariadb-client -y
    mysql -e "CREATE DATABASE radius;"
    mysql radius < /etc/freeradius/3.0/mods-config/sql/main/mysql/schema.sql
    mysql radius < adminPanel.sql
    mysql -e "CREATE USER '"${radiusUser}"'@'localhost' IDENTIFIED BY '"${radiusPass}"';"
    mysql -e "CREATE USER '"${adminUser}"'@'localhost' IDENTIFIED BY '"${adminPass}"';"
    mysql -e "GRANT ALL PRIVILEGES ON radius.* TO '"${radiusUser}"'@'localhost';"
    mysql -e "GRANT ALL PRIVILEGES ON radius.* TO '"${adminUser}"'@'localhost';"
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${rootPass}';"
    mysql --password=${rootPass} -e "FLUSH PRIVILEGES;"

}

function installFreeRadius() {
    echo -e "${ORANGE}Memulai Instalasi FreeRadius...${NC}"
    apt install freeradius freeradius-mysql freeradius-utils -y

    sed -i 's/-sql/sql/g' /etc/freeradius/3.0/sites-available/default


    ln -s /etc/freeradius/3.0/mods-available/sql /etc/freeradius/3.0/mods-enabled/sql
    sed -i 's/dialect = "sqlite"/dialect = "mysql"/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/driver = "rlm_sql_null"/driver = "rlm_sql_mysql"/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's~ca_file = "/etc/ssl/certs/my_ca.crt"~#ca_file = "/etc/ssl/certs/my_ca.crt"~g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's~ca_path = "/etc/ssl/certs/"~#ca_path = "/etc/ssl/certs/"~g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's~certificate_file = "/etc/ssl/certs/private/client.crt"~#certificate_file = "/etc/ssl/certs/private/client.crt"~g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's~private_key_file = "/etc/ssl/certs/private/client.key"~#private_key_file = "/etc/ssl/certs/private/client.key"~g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/cipher = "DHE-RSA-AES256-SHA"/#cipher = "DHE-RSA-AES256-SHA"/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/tls_required = yes/#tls_required = yes/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/tls_check_cert = no/#tls_check_cert = no/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/tls_check_cert_cn = no/#tls_check_cert_cn = no/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/#\tserver = "localhost"/\tserver = "localhost"/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/#\tport = 3306/\tport = 3306/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/#\tlogin = "radius"/\tlogin = "'${radiusUser}'"/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/#\tpassword = "radpass"/\tpassword = "'${radiusPass}'"/g' /etc/freeradius/3.0/mods-available/sql
    sed -i 's/#\tread_clients = yes/\tread_clients = yes/g' /etc/freeradius/3.0/mods-available/sql

    cat <<EOF >> clients.conf
client mikrotik{
    ipaddr = ${routerIP}
    secret = ${routerSecret}
    proto = *
    require_message_authenticator = no
    nas_type = other
    limit {
            max_connections = 0
            lifetime = 0
            idle_timeout = 30
    }
}
EOF

}

function installAdminPanel() {
    echo -e "${ORANGE}Memulai Instalasi AdminPanel...${NC}"
    apt install nginx php-fpm php-mysql php-gd -y
    cp controlPanel.conf /etc/nginx/sites-available/
    unlink /etc/nginx/sites-enabled/default
    ln -s /etc/nginx/sites-available/controlPanel.conf /etc/nginx/sites-enabled/

    rm -rf /var/www/html/*
    cp -r html/* /var/www/html/
    chown -R www-data:www-data /var/www/html/
    chmod -R u=rwX,g=,o= /var/www/html/

    sed -i 's/$username = "";/$username = "'${adminUser}'";/g' /var/www/html/controllers/database.php
    sed -i 's/$password = "";/$password = "'${adminPass}'";/g' /var/www/html/controllers/database.php
}

function phpHardening() {
    long_version=$(php -r 'echo PHP_VERSION;')
    php_version=${long_version:0:3}

    sed -i 's/disable_functions = /disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source,/g' /etc/php/${php_version}/fpm/php.ini
    sed -i 's/allow_url_fopen = On/allow_url_fopen = Off/g' /etc/php/${php_version}/fpm/php.ini
    sed -i 's/allow_url_include = On/allow_url_include = Off/g' /etc/php/${php_version}/fpm/php.ini
}

function restartEverything() {
    systemctl restart mariadb
    systemctl enable --now mariadb
    systemctl restart freeradius
    systemctl enable --now freeradius

    systemctl restart nginx
    systemctl enable --now nginx

    long_version=$(php -r 'echo PHP_VERSION;')
    php_version=${long_version:0:3}
    systemctl restart php${php_version}-fpm
    systemctl enable --now php${php_version}-fpm
}

function install(){
    installQuestions
    echo -e "\n${ORANGE}Memulai Instalasi...${NC}"

    apt update
    installFreeRadius
    installMariaDB
    installAdminPanel
    phpHardening
    restartEverything

    echo -e "${ORANGE}Instalasi Selesai...${NC}"
}


initialCheck
install
