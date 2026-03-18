En-têtes de commentaires (headers) complets pour WordPress. Ils permettent au CMS de lire les métadonnées et d'afficher correctement votre création dans le tableau de bord.

---

### 1. Pour un Thème (`style.css`)
Le fichier doit impérativement se nommer `style.css` et être à la racine de votre dossier de thème.

```css
/*
Theme Name:   Mon Thème Exemple
Theme URI:    https://votre-site.com/mon-theme
Author:       Prénom NOM
Author URI:   https://votre-site.com
Description:  Un thème léger créé à partir de zéro pour l'apprentissage.
Version:      1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.3
License:      GNU General Public License v3 or later
License URI:  http://www.gnu.org/licenses/gpl-3.0.html
Text Domain:  theme-exemple
Tags:         custom-menu, featured-images, accessibility-ready
*/
```

---

### 2. Pour une Extension / Plugin (`mon-plugin.php`)
L'en-tête doit se trouver au début du fichier PHP principal de votre extension.

```php
<?php
/**
 * Plugin Name:       Mon Plugin Exemple
 * Plugin URI:        https://votre-site.com/mon-plugin
 * Description:       Une extension personnalisée pour ajouter des fonctions spécifiques.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.3
 * Author:            Prénom NOM
 * Author URI:        https://votre-site.com
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       plugin-exemple
 * Domain Path:       /languages
 */
```

---

### Points clés à retenir :

* **Minimum requis :** `Theme Name` pour un thème et `Plugin Name` pour un plugin sont les seules lignes strictement obligatoires pour que WordPress les détecte, mais il est recommandé de tout remplir.

* **Text Domain :** Indispensable pour rendre votre thème ou plugin traduisible plus tard. Il doit correspondre à l'identifiant (slug) de votre dossier.

* **Version :** Très utile pour forcer le rafraîchissement du cache des navigateurs lorsque vous modifiez votre CSS (via `wp_enqueue_style`).

