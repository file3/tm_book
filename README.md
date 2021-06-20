## Book Registry

## System Requirement

Server running PHP 5.0 or higher with PostgeSQL database extension, composer command installed

## Installation

*Under working directory*

$ git clone https://github.com/file3/tm_book.git

$ cd tm_book

*Or*

$ tar xzf book.tgz

$ cd transfermate

*Or*

$ unzip book.zip

$ cd transfermate

*Modify setup.sql, INPUT_DATA and DB_XXXX defines in file www/book/src/config.php according to the server environment*
*The directory www is intended to be under web-space*

$ psql -U pesysadmin template1 -p <setup.sql >setup.out 2>&1

$ composer update

## Running

$ wget -O - -q -t 1 http[s]://[www.]server[.hu|.com|...]/[dir/]book/ >/dev/null 2>&1

*Or if intended to run in console*

$ php www/book/index.php

*Setting up scheduler: copy the content of file crontab to user's crontab*

## Test

$ vendor/bin/phpunit www/book/tests/GetDataTest

## Demo

http://webmail.humankraft.hu/~fattila/transfermate/book/

## TODO and future consideration

While the item 3. says: "Result design requirements: rows should slide from left
to right one after another..." but the "Example result" shows that grid items
are aligned to left. I followed the "Example result", using slide from right to
left.

Add more error handling

Use spl_autoload_register() to load main class

Protect either main class member variables or member functions

Add more unit test cases

Isolate some program files not residing under web-space

In case of directory input, gather files recursively

Create setup.sql for MySQL database back-end

Add full CLI support by parsing command-line parameters with getopt()
