#!/bin/sh

# 実行させたいテストファイルを引数に渡す
execFile=`echo $1 | sed s/src/./`
php ./vendor/bin/phpunit $execFile
