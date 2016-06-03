```
       __            _    __
  ___ / /___ _____  (_)__/ /__  _______ __ ____ __
 (_-</ __/ // / _ \/ / _  / _ \/ __/ _ \\ \ / // /
/___/\__/\_,_/ .__/_/\_,_/ .__/_/  \___/_\_\\_, /
            /_/         /_/                /___/
```
*A stupid reverse proxy*

## Installation

Clone the repo, put it somewhere web-accessible, then use the supplied .htaccess or nginx-example.conf configuration for your webserver. Copy `config-sample.php` to `config.php` and set the base URL you want to proxy to.

## Demo

My blog ([blog.phpizza.com](https://blog.phpizza.com/)) uses stupidproxy to pass requests to GitHub Pages. I would use the usual `CNAME` standard that GitHub offers, but I want SSL, so I wrote this silly thing :P
