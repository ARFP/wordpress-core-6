Par défaut, si vous souhaitez que VsCode reconnaisse les fonctions de wordpress, vous devez ouvrir le dossier avec l'installation de Wordpress.

Pour que VS Code reconnaisse les fonctions WordPress sans polluer votre dépôt Git avec l'installation complète du CMS, suivre la procédure suivante.


1. Installer les estensions VsCode:
-  **[PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)** dans VS Code pour que les paramètres de ce document soient pris en compte.
- **[PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)** pour le debogage en live.

2.  **[Téléchargez WordPress](https://fr.wordpress.org/download/)** (le dossier `.zip`) et décompressez-le quelque part sur votre ordinateur (par exemple dans `C:/dev/wordpress` ou `~/dev/wordpress`).

2. Dans le projet (le dossier qui contient le thème ou plugin à développer), créez un dossier `.vscode` s'il n'existe pas.

3. Dans ce dossier, créer un fichier `settings.json` à l'intérieur et collez-y ceci :


```json
{
    "php.suggest.basic": false,
    "intelephense.environment.includePaths": [
        "C:/dev/wordpress/wordpress-6.4.9"
    ],
    "intelephense.environment.phpVersion": "8.4.0",
    "intelephense.stubs": [
        "wordpress",
        "apache",
        "bcmath",
        "bz2",
        "calendar",
        "com_dotnet",
        "Core",
        "ctype",
        "curl",
        "date",
        "dba",
        "dom",
        "enchant",
        "exif",
        "fileinfo",
        "filter",
        "fpm",
        "ftp",
        "gd",
        "gettext",
        "gmp",
        "hash",
        "iconv",
        "imap",
        "interbase",
        "intl",
        "json",
        "ldap",
        "libxml",
        "mbstring",
        "mcrypt",
        "mssql",
        "mysql",
        "mysqli",
        "oci8",
        "odbc",
        "openssl",
        "pcntl",
        "pcre",
        "PDO",
        "pdo_ibm",
        "pdo_mysql",
        "pdo_pgsql",
        "pdo_sqlite",
        "pgsql",
        "Phar",
        "posix",
        "pspell",
        "readline",
        "recode",
        "Reflection",
        "regex",
        "session",
        "shmop",
        "SimpleXML",
        "snmp",
        "soap",
        "sockets",
        "sodium",
        "SPL",
        "sqlite3",
        "standard",
        "sybase",
        "sysvmsg",
        "sysvsem",
        "sysvshm",
        "tidy",
        "tokenizer",
        "wddx",
        "xml",
        "xmlreader",
        "xmlrpc",
        "xmlwriter",
        "xsl",
        "Zend OPcache",
        "zip",
        "zlib"
    ]
}
```


Avec cette configuration, le dossier de travail Wordpress ressemblera à ceci : 

```
/mon-projet-wordpress/
├── .vscode/             <-- settings.json & launch.json (WP)
├── wp-content/          <-- Votre code (Themes/Plugins)
├── docker-compose.yml   <-- Uniquement WP + DB
└── Dockerfile           <-- Dockerfile (WP 6.9.4 + MariaDB + Xdebug)

/mon-projet-api/
├── .vscode/             <-- settings.json & launch.json (Symfony)
├── src/                 <-- Votre code Symfony 8.4
├── docker-compose.yml   <-- Uniquement Symfony + DB
└── symfony/             <-- Dockerfile (PHP 8.4 + Apache + VHost)
```