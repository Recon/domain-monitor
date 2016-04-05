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

4. Visit `monitor.example.com` - at this point it should redirect you to the install page, which will display a form which asking 
for the MySQL credentials, default user and mail settings
> If the install page lists any issues with the requirements, please correct them and refresh the page.

5. To trigger the update, you will need to setup a cron job every **x** minutes (recomended: every two minutes) calling the 
`cli` script from the install directory with the `check` command:

``` 
*/2 * * * *  /usr/bin/php /full/path/to/install-directory/cli check
```

> After installation, you can login at `monitor.example.com/#/login`

-----

##### Apache Virtual Hosts

Locate the virtual host config file (usually in `/etc/apache2/sites-available`) and make sure that the `DocumentRoot` entry points to the 
`public_html` directory: `/full/path/to/install-directory/public_html`

> Make sure you have copied `public_html/.htaccess.example` to `public_html/.htaccess`

##### NginX Virtual Hosts
