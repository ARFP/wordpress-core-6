# Wordpress : Hooks

Pour comprendre WordPress, il faut imaginer que le code source est parsemé d'**ancres** (les Hooks). Un plugin ou un thème vient "accrocher" ses propres fonctions à ces ancres pour modifier le comportement de WordPress sans jamais toucher au code d'origine.

On distingue deux familles de Hooks : les **Actions** (faire quelque chose) et les **Filtres** (modifier une donnée).

---

## 1. Les Actions (`add_action`) : "Fais ça à ce moment précis"

Ces hooks servent à exécuter du code à une étape clé du chargement de la page.

* **`init` :** Le hook à tout faire. Il s'exécute une fois que WordPress est chargé mais avant que les en-têtes ne soient envoyés. C'est ici qu'on enregistre les Custom Post Types ou les taxonomies.
* **`wp_enqueue_scripts` :** C'est l'unique endroit propre pour charger les fichiers CSS et JavaScript. Ne jamais les mettre en dur dans le header.
* **`admin_menu` :** Utilisé pour ajouter des pages de réglages ou des sous-menus dans le tableau de bord (l'interface noire à gauche).
* **`wp_head` / `wp_footer` :** Pour insérer du code (pixel Facebook, Google Analytics, meta tags) directement dans les balises `<head>` ou juste avant `</body>`.
* **`save_post` :** Se déclenche dès qu'un article est créé ou mis à jour. Idéal pour envoyer une notification ou mettre à jour une donnée liée.
* **`widgets_init` :** Pour enregistrer des zones de widgets (sidebars) ou des widgets personnalisés.

---

## 2. Les Filtres (`add_filter`) : "Modifie cette variable avant de l'utiliser"

Les filtres reçoivent une donnée, la transforment, et **doivent impérativement la renvoyer** (`return`).

* **`the_content` :** Le plus célèbre. Il permet de modifier le texte d'un article avant qu'il ne soit affiché à l'écran (ex: ajouter un bouton de partage, censurer des mots, ajouter des publicités).
* **`the_excerpt` :** Pour modifier le résumé d'un article (souvent utilisé pour changer le "Lire la suite").
* **`wp_title` / `pre_get_document_title` :** Pour manipuler le titre de la page qui s'affiche dans l'onglet du navigateur (très utile pour le SEO).
* **`body_class` :** Permet d'ajouter une classe CSS spécifique à la balise `<body>` selon la page (ex: ajouter une classe "client-premium" si l'utilisateur est connecté).
* **`upload_mimes` :** Pour autoriser le téléchargement de types de fichiers interdits par défaut (ex: autoriser les fichiers .svg ou .json).
* **`excerpt_length` :** Un filtre simple pour changer le nombre de mots affichés dans les résumés.

---

## 3. Les Hooks d'Activation / Désactivation

Indispensables pour un plugin propre, ils ne se déclenchent qu'une seule fois.

* **`register_activation_hook` :** S'exécute au moment où l'utilisateur clique sur "Activer". C'est ici qu'on crée les tables dans la base de données si besoin.
* **`register_deactivation_hook` :** Pour "nettoyer" temporairement (arrêter des tâches planifiées par exemple).
* **`register_uninstall_hook` :** Le plus important pour l'éthique : il supprime toutes les données du plugin quand l'utilisateur clique sur "Supprimer".

---

### Conseils

Quand vous utilisez un hook, toujours nommer la fonction de manière unique (préfixez-la) pour éviter les conflits avec d'autres plugins. 

> **Mauvais exemple :** `add_action('init', 'setup');` (Trop générique, va planter si un autre plugin a une fonction `setup`).
> **Bon exemple :** `add_action('init', 'mdevoldere_plugin_setup_cpt');`


## Lier les fichiers CSS et JS

Il est recommandé d'ajouter le CSS et JS en passant par les fonctions wordpress (`wp_enqueue_style` et `wp_enqueue_script`) au lieu de les inclure directement dans le header du thème.

**Pourquoi ?**

C'est la question que tout développeur se pose au début. Techniquement, si on écris `<link rel="stylesheet">` dans le `header.php` du thème, ça va fonctionner. Mais sur WordPress, c'est une **mauvaise pratique**.

Voici pourquoi il faut passer par la fonction `wp_enqueue_scripts` dans ton plugin ou thème :

---

### 1. La gestion des dépendances (Le vrai pouvoir)

C'est l'argument n°1. WordPress est un écosystème.

* **Le problème du header :** Si le CSS a besoin d'un autre fichier (ou si le JS a besoin de jQuery) pour fonctionner, il faut gérer l'ordre d'affichage à la main. C'est l'enfer.
* **La solution WordPress :** Avec `wp_enqueue_style()`, on dit à WordPress : *"Charge mon style, mais attends que le style du thème parent soit chargé avant"*. WordPress calcule l'ordre tout seul.

### 2. Éviter les doublons (Conflits)

Imagine que vous installez deux plugins qui utilisent tous les deux la bibliothèque "FontAwesome".

* **Dans le header :** Vous allez vous retrouver avec deux fois le même lien vers FontAwesome. Le site ralentit et les styles peuvent s'écraser.
* **Avec Enqueue :** WordPress vérifie l'identifiant (le "handle"). Si deux plugins demandent `font-awesome`, WordPress ne le chargera qu'**une seule fois**.

### 3. La Performance (Mise en cache et Concaténation)

Les plugins de performance (comme WP Rocket ou Autoptimize) cherchent les fichiers enregistrés proprement.
* Si les liens sont "en dur" dans le header, ces outils ont du mal à les détecter pour les combiner ou les minifier (les rendre plus légers).
* En passant par la file d'attente officielle, on permet aux outils d'optimisation de faire leur travail correctement.

### 4. La flexibilité (Chargement conditionnel)

C'est crucial pour un thème ou un plugin.

* Vous ne voulez peut-être charger un CSS spécifique **que** sur un type de contenu, et pas sur tout le site. 
* Avec `wp_enqueue_scripts`, vous pouvez ajouter une condition simple (ex: `if (is_page('tarifs'))`) pour ne charger le fichier que quand c'est nécessaire. C'est impossible proprement si c'est codé en dur dans le header.

---

## Éviter le chargement des assets depuis les templates

Au lieu d'inclure le CSS dans le header, on utilisera l'approche Wordpress (à mettre dans le fichier du plugin ou le functions.php du thème) :

Exemple pour un plugin 

```php
/**
 * Chargement des assets pour un plugin
 */
function mon_plugin_enqueue_assets() {
    // Charger un style spécifique au plugin
    wp_enqueue_style(
        'mon-plugin-style', 
        plugins_url('/css/plugin-style.css', __FILE__), 
        array(), 
        '1.0.0'
    );

    // Charger un script JS
    wp_enqueue_script(
        'mon-plugin-script', 
        plugins_url('/js/plugin-script.js', __FILE__), 
        array('jquery'), 
        '1.0.0', 
        true
    );
}
add_action('wp_enqueue_scripts', 'mon_plugin_enqueue_assets');
```

Exemple pour un thème 

```php
/**
 * Chargement des scripts et styles pour le thème
 */
function mon_theme_enqueue_assets() {
    // Charger le CSS principal du thème (style.css à la racine)
    wp_enqueue_style(
        'mon-theme-main-style', 
        get_stylesheet_uri(), 
        array(), 
        '1.0.0'
    );

    // Charger un script JS personnalisé
    wp_enqueue_script(
        'mon-theme-navigation', 
        get_stylesheet_directory_uri() . '/js/navigation.js', 
        array('jquery'), // Dépendance : charge jQuery avant mon script (laisser le tableau vide si pas de dépendances)
        '1.0.0', 
        true // true = charger dans le footer (recommandé pour la performance)
    );
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_assets');
```

C'est "la méthode WordPress". C'est propre, c'est pro, et ça évite que le site ne casse quand tu mets à jour un thème.

#### Points clés à retenir :

**__FILE__ :** Dans un plugin, ce paramètre est crucial pour que WordPress calcule le chemin relatif correctement.

**Dépendances :** L'argument array('jquery') indique à WordPress de ne charger votre script que si jQuery est déjà présent. Si vous utilisez React (Gutenberg), vous mettriez array('wp-blocks', 'wp-element').

**Version :** Le '1.0.0' sert au "cache busting". Si vous modifiez votre fichier, changez la version pour forcer le navigateur des visiteurs à télécharger la nouvelle version.

**Footer (true) :** Il est presque toujours préférable de charger le JS en bas de page pour ne pas bloquer le rendu visuel du site.
