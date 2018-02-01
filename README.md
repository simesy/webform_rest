# Quick HELLO WORLD PoC

## Drupal

Note that this module was based on webform_rest and the README.txt is in the same
directory with some instrustions. These instructions are very basic.

* Enable webform_vue module
* Enable RESTful endpoints at /admin/config/services/rest
* Give appropriate perms so you can access endpoint
* Create a webform with the id "contact"
* Now you should be able to access /webform_vue/contact/elements?_format=json as anonymous

## VueJS

In the ./example directory

* npm install
* npm run dev
* Access the site that
* You should see the Contact form