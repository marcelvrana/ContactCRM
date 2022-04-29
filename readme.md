CRM contacts with XML storage
=================

Requirements
------------

- CRM on Nette 3.1 requires PHP 7.2 and more

Installation
------------
Install dependencies over composer and npm

	$ composer i
    $ npm i

When you have installed npm, you can run

    *** Development with watch files ***
    $ npm run dev

    *** Compile production files ***
    $ npm run prod


Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the welcome page.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.
