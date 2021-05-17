![AMPto11 logo](https://jasoncampbelldev.github.io/portfolio/screen-shots/ampTo11-logo.png)

A simple CMS that uses JSON files for a database (noSQL style) and outputs static content. 
It is designed for AMP pages but can be used for regular web pages too. 
The content editor is element based so your HTML stays minimal and clean.
The CMS also features databases and database templates to make database sites easy.

[Demo Site](http://jtc-art.com/ampTo11-demo/)

## Quick Start Guide
1. Drop "admin" and "images" folders into your root directory or folder.
2. Password protect "admin" folder (rename folder for extra security)
3. Log in to "admin" folder.
4. Enter root or folder domain into "URL Base" field (home >> settings).
5. Export every option in export section (home >> exports).
6. Go to the "URL Base" to see the demo site. 
7. Enjoy codeing!

Note: PHP module functions are located in admin/php_module_functions.php

## Lighthouse Score

![Image of page editor](https://jasoncampbelldev.github.io/portfolio/screen-shots/lighthouse-score.jpg)

---

# Basic Documentation

---

## Page Editor

![Image of page editor](https://jasoncampbelldev.github.io/portfolio/screen-shots/page-editor.jpg)

- Use HTML elements or Modules (explained below) to form your page.
- The page can be previewed in the iframe (or use the arror icon to open preview in a new tab).
- A WYSIWYG editor is also an option to edit an element's content.
- Add SEO tags and JSON-LD mark up.
- Add inline CSS or JS (not recommended for AMP pages).
- Add taxonamy for post type pages.

---

## HTML Module Editor

![Image of HTML module editor](https://jasoncampbelldev.github.io/portfolio/screen-shots/html-module-edit.jpg)

- Use HTML elements to form your module.
- Add variables and add them to your content using handlebar. syntax *example: {{first_name}}*

---

## PHP Module Editor

![Image of PHP function module editor](https://jasoncampbelldev.github.io/portfolio/screen-shots/php-module-edit.jpg)

- Use a PHP function as a module
- Add the function to admin/php_module_functions.php (refer to the premade functions for examples).
- Add variables that will be passed into the function through the module.
- The child HTML content of the module comes in the variable "%content%". *example: $vars['%content%']*
  
    ``function example($vars) { return "<p>" . $vars['first_name'] . "</p> <div>" . $vars['%content%'] . "</div>"; }``

---

## Database Editor

![Image of database editor](https://jasoncampbelldev.github.io/portfolio/screen-shots/database-editor.jpg)

- Add Database fields and entries.
- Order the database by a field.
- The field "url" is added automatically and doesn't allow duplicates. (this can be deleted and readded)

---

## Database Add Entry

![Image of adding an entry to database](https://jasoncampbelldev.github.io/portfolio/screen-shots/database-add-entry.jpg)

---

## Database Page Template Editor

![Image of database page template editor](https://jasoncampbelldev.github.io/portfolio/screen-shots/db-page-editor.jpg)

- Create a database page template using elements and modules.
- Use handlebar notation to add database fields to the template. *example: {{first_name}}*
- All of the pages can be toggled in the preview iframe.

---

## Image Admin

![Image of image admin](https://jasoncampbelldev.github.io/portfolio/screen-shots/image-admin.jpg)

- Upload multi images to create various image sizes. *Note: this is very basic so there isn't a progress bar or anything at this time*
- Or upload to the "images" file through FTP and click the "Generate Sizes" button to generate various sizes.
- If you have a lot of images the second method would be best

---

---

__This is meant to be a jumping off point. Hopefully you can customize the code to fit your needs.__

__I hope other developers can colaborate with me to make this an awesome CMS.__
