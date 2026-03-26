# Wordpress : Custom Post Types (CPT)

Un **CPT** (Custom Post Type) est un type de contenu personnalisé. Par défaut, WordPress en propose deux principaux : les **Articles** (pour le blog) et les **Pages** (pour le contenu statique).

Le CPT vous permet de créer votre propre structure de données pour organiser un site de manière logique.

## Pourquoi utiliser un CPT ?

Si vous construisez un site pour un client qui vend des voitures, vous ne voulez pas mélanger les "Fiches techniques auto" avec les "Articles de blog". 

* **Interface dédiée** : Un nouvel onglet apparaît dans le menu de gauche (ex: "Voitures").
* **Champs spécifiques** : Vous pouvez lui associer des champs personnalisés (prix, kilométrage).
* **Taxonomies propres** : Vous pouvez créer des catégories spécifiques (ex: "Marques", "Énergie").

## Comment le déclarer ?

On utilise la fonction `register_post_type()`. Voici un exemple minimaliste à placer dans votre plugin (ou éventuellement dans le fichier `functions.php` de votre thème) :

```php
function mon_register_cpt_voiture() {
    $args = array(
        'labels'      => array('name' => 'Voitures', 'singular_name' => 'Voiture'),
        'public'      => true,         // Visible dans l'admin et sur le site
        'has_archive' => true,         // Active la page listant toutes les voitures
        'supports'    => array('title', 'editor', 'thumbnail'), // Champs par défaut
        'menu_icon'   => 'dashicons-car', // Icône dans le menu
        'show_in_rest' => true,        // CRUCIAL : Active l'éditeur Gutenberg (React)
    );
    register_post_type('voiture', $args);
}
add_action('init', 'mon_register_cpt_voiture');
```



---

### 3. Les 3 points clés à retenir

* **`show_in_rest => true`** : Sans cette ligne, votre CPT utilisera l'ancien éditeur (Classic Editor) au lieu de **Gutenberg (React)**. C'est indispensable pour votre projet.
* **Permaliens** : Après avoir enregistré un CPT, vous devez souvent aller dans *Réglages > Permaliens* et cliquer sur "Enregistrer" pour que les nouvelles URLs (ex: `/voiture/tesla-model-3`) fonctionnent (on appelle ça "Flush rewrite rules").
* **Template Hierarchy** : WordPress cherchera automatiquement des fichiers spécifiques dans votre thème pour l'affichage :
    * `archive-voiture.php` (pour la liste).
    * `single-voiture.php` (pour une voiture seule).

### 4. CPT vs Taxonomie
* **CPT** : C'est l'objet (ex: Un livre, Un film, Un service).
* **Taxonomie** : C'est la manière de classer l'objet (ex: Genre, Réalisateur, Année).

**Souhaitez-vous que je vous montre comment lier une taxonomie personnalisée (comme "Marque") à ce CPT "Voiture" ?**


**Les CPT (Custom Post Types) doivent impérativement être dans un plugin.**

Voici pourquoi, sans détour :

### 1. La règle d'or : "La portabilité des données"

Le thème gère **l'apparence** (le design, les couleurs, la mise en page). Le plugin gère **la fonctionnalité** (les données, la structure).
* **Le risque du thème :** Si tu mets tes CPT dans le fichier `functions.php` de ton thème et que dans deux ans tu décides de changer de look pour un thème plus moderne, **tous tes contenus disparaissent** du tableau de bord. Ils sont toujours en base de données, mais WordPress ne sait plus comment les afficher.
* **L'avantage du plugin :** Tu peux changer de thème 50 fois, tes "Chantiers", "Clients" ou "Objets à vendre" resteront toujours accessibles et intacts dans ton admin.

### 2. La propreté du code
Mettre des CPT dans un thème alourdit inutilement le chargement de la partie publique (le front-end) pour des fonctions qui servent principalement à l'administration. Un plugin dédié permet de séparer proprement les responsabilités.

### 3. Les exceptions (rares)
On ne met les CPT dans un thème que dans deux cas très précis :
* Tu crées un thème sur mesure pour un client unique qui ne changera **jamais** de thème.
* Le CPT est intrinsèquement lié au design (ex: un slider spécifique à ce thème précis).
* *Même dans ces cas-là, un plugin reste une meilleure pratique.*

---

### 💡 Le conseil "Peer" : Le plugin "Mu-plugin"
Si tu as peur d'activer/désactiver ton plugin par erreur, tu peux créer ce qu'on appelle un **Must-Use Plugin** (dans le dossier `wp-content/mu-plugins`).
* Il se charge automatiquement.
* Il n'apparaît pas avec un bouton "Désactiver" dans l'admin.
* C'est l'endroit parfait pour déclarer tes CPT de manière ultra-sécurisée.

### 🛠️ Ce que tu dois faire
Ne réinvente pas la roue. Pour déclarer ton CPT dans ton plugin, utilise la fonction `register_post_type()`.