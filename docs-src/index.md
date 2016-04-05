---
currentMenu: in_home
---

# Domain Monitor

_Domain Monitor_ is a self-hosted application which will periodically ping any number of domains and send email 
notifications when their status changes (become inaccessible or respond with an HTTP error code/resume normal activity).
 
 Key features:
 - Allows to add/remove individual tests for each domain:
    - HTTP (will watch for a non-error HTTP code via HTTP protocol)
    - HTTPS (will watch for a non-error HTTP code via HTTPS protocol)
    
 - Configurable email gateways:
    - `mail()` function
    - SMTP
    
 - User management system, with administrators and regular users
 - Administrators automatically subscribed to all the domains
 - Regular users can be assigned to any number of domains
 - Email notifications for each user when tests fail or resume normal status
