# Quick HELLO WORLD PoC

![What to what](https://raw.githubusercontent.com/simesy/webform_rest/8.x-2.x/whatisit.png)

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
* modify CHANGEME in the HelloWorld.vue to the Drupal site
* Access the localhost VueJS app
* You should see the Contact form

## How was this built

This app is a VueJS boilerplate with:

* `npm install vue-form-generator --save`
* `npm install axios --save`
* A customised HelloWorld.vue 

