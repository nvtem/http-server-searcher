# http-server-searcher
PHP script that looks for active HTTP servers from IP range and takes screenshots of them.

## Requirements
Nmap and Chrome. Make sure ```nmap``` and ```chrome```  can be called globally.

## Usage
    php searcher.php --ip X [--nmap-t X] [--http-t X] [--scr-dir X]

--ip  
Nmap scan target. E.g., 10.100.\*.\*

--nmap-t  
Nmap scan timeout. Possible values: 0..5. The default is 5. See [https://nmap.org/book/performance-timing-templates.html](https://nmap.org/book/performance-timing-templates.html)

--http-t  
HTTP test timeout in seconds. Possible values: 1...∞. The default is 2.

--scr-dir  
Screenshot save directory. The default is ./screenshots
