# rewrite rules

nginx, try 404 to pages.php
```
location / {
    try_files $uri $uri/ /pages.php?$args;
}
```