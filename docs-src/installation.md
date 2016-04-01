---
currentMenu: installation
---

#Requirements

> Before installing, consult the [requirements page](requirements.html) 


* The directory you'll be uploading the files to will be referred as `/full/path/to/install-directory`
* The (sub)domain you'll be using will be referred here as `monitor.example.com` 

-----

1. Upload the files to your `/full/path/to/install-directory`.  

2. Configure the document root of your virtual host to point to the `public_html` directory (`/full/path/to/install-directory/public_html`)
    - **Apache** users, [see here](#)
    - **NginX** users, [see here](#)

3. If you're using an **Apache** server, copy the `public_html/.htaccess.example` to `public_html/.htaccess`. A .htaccess file 
is not provided directly so any custom rules you might need to add won't be accidentally overwritten when performing an upgrade.

4. At this point, visiting `monitor.example.com` will redirect you to the install page.

5. If the install page lists any issues with the requirements, please correct them and refresh the page.

6. When all the requirements have been fullfilled, the install page will display a form which will ask for MySQL details, default user and mail settings
 
7. After installation, you can login at `monitor.example.com/#/login`

-----

##### Apache Virtual Hosts

##### NginX Virtual Hosts
