

Pour que VS Code reconnaisse les fonctions WordPress sans polluer votre dépôt Git, la méthode la plus propre consiste à modifier les paramètres de l'espace de travail.

Configurer un fichier `.vscode/settings.json` à la racine de votre projet :

1.  **Téléchargez WordPress** (le dossier `.zip`) et décompressez-le quelque part sur votre ordinateur (par exemple dans `C:/dev/wordpress` ou `~/dev/wordpress`).
2. Dans le projet (le dossier qui contient le thème ou plugin à développer), créez un dossier `.vscode` s'il n'existe pas.
3. Crée un fichier `settings.json` à l'intérieur et collez-y ceci :
4. Installer l'extension **PHP Intelephense** de Ben McNamara dans VS Code pour que ces paramètres soient pris en compte

```json
{
    "php.suggest.basic": false,
    "intelephense.environment.includePaths": [
        "C:/Users/mdevoldere/wordpress/wordpress-6.9.4"
    ],
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
