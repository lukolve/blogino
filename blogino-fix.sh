#!/bin/sh
#
# provede zmenu vlastnika a skupiny na root:wheel
# a prav v adresari /var/www/html
# adresarum prideli prava 755
# souborum prideli prava 644
#
# lze zaradit do tabulky "cron" uzivatele "root"

chown -R lukas:users /var/www/html
find /var/www/html -type d -exec chmod 755 {} \;
find /var/www/html -type f -exec chmod 644 {} \;
