#!/bin/sh

# à installer dans un dossier à côté du repo xoomcoder
# et ajouter un crontab -e

# MAILTO=cron@xoomcoder.com

#  m h  dom mon dow   command
#  27 *  *   *   *    $HOME/xoomcoder.com/mysql/dbsave.sh > /dev/null

myphp=/usr/bin/php7.4-cli
todir=`dirname $0`
cd $todir

curdir=`pwd`

echo $curdir

now=`date +%F-%H%M`
echo $now
echo $myphp

$myphp $curdir/../xoomcoder/xoom/etc/xterm.php -s export-$now