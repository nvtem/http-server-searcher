# http-server-searcher
Script that searches for active HTTP servers from IP range and takes screenshots of them.

## Requirements
Nmap and wkhtmltopdf

### Linux
    sudo apt install nmap wkhtmltopdf
or other

### Windows
Install [Nmap](https://nmap.org/) and [wkhtmltopdf](https://wkhtmltopdf.org/). Add location of program binaries to PATH environment  variable, e.g ```C:\Program Files (x86)\Nmap``` and ```C:\Program Files\wkhtmltopdf\bin```
 
## Usage
    php searcher.php --ip=X [--nmap-t=X] [--http-t=X] [--scr-dir=X]

--ip  
Nmap scan target. E.g., 10.100.\*.\*

--nmap-t
Nmap scan timeout. Possible values: 0..5. The default is 5. See [https://nmap.org/book/performance-timing-templates.html](https://nmap.org/book/performance-timing-templates.html)

--http-t
HTTP test timeout in seconds. Possible values: 1...∞. The default is 2.

--scr-dir 
Screenshot save directory. The default is ./screenshots
