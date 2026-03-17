# Plugin de "Réservation"

- Custom Post Type
- Shortcode

```php
<?php
/**
 * Plugin Name: Réservation Coiffure (Version mde_)
 * Description: Gestion des prestations avec prix, durée et bouton de réservation.
 */

// 1. Créer le type de contenu "Prestation"
add_action('init', 'mde_creer_cpt_prestation');
function mde_creer_cpt_prestation() {
    register_post_type('prestation', array(
        'labels' => array('name' => 'Prestations', 'singular_name' => 'Prestation'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-scissors',
        'supports' => array('title', 'editor', 'thumbnail')
    ));
}

// 2. Ajouter la Meta Box pour les détails
add_action('add_meta_boxes', 'mde_meta_details_prestation');
function mde_meta_details_prestation() {
    add_meta_box('details_presta', 'Réglages de la prestation', 'mde_render_details', 'prestation', 'side');
}

function mde_render_details($post) {
    $prix = get_post_meta($post->ID, '_presta_prix', true);
    $duree = get_post_meta($post->ID, '_presta_duree', true);
    
    echo '<p><label>Prix (€) : </label><br>';
    echo '<input type="number" name="presta_prix" value="' . esc_attr($prix) . '" style="width:100%"></p>';
    
    echo '<p><label>Durée (ex: 1h30) : </label><br>';
    echo '<input type="text" name="presta_duree" value="' . esc_attr($duree) . '" style="width:100%"></p>';
}

// 3. Sauvegarder les données
add_action('save_post', 'mde_save_details_prestation');
function mde_save_details_prestation($post_id) {
    if (isset($_POST['presta_prix'])) {
        update_post_meta($post_id, '_presta_prix', sanitize_text_field($_POST['presta_prix']));
    }
    if (isset($_POST['presta_duree'])) {
        update_post_meta($post_id, '_presta_duree', sanitize_text_field($_POST['presta_duree']));
    }
}

// 4. Affichage final sur le site
add_filter('the_content', 'mde_affiche_details_final');
function mde_affiche_details_final($content) {
    // On vérifie qu'on est bien sur une page de "prestation"
    if (is_singular('prestation')) {
        $prix = get_post_meta(get_the_ID(), '_presta_prix', true);
        $duree = get_post_meta(get_the_ID(), '_presta_duree', true);
        
        $html = '<div style="background:#fdf2f8; padding:20px; border:1px solid #f9a8d4; border-radius:10px; margin-top:20px;">';
        $html .= '<p><strong>⏱ Durée :</strong> ' . esc_html($duree) . '</p>';
        $html .= '<p><strong>💰 Tarif :</strong> ' . esc_html($prix) . ' €</p>';
        $html .= '<a href="/contact" style="background:#db2777; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;">Réserver ce créneau</a>';
        $html .= '</div>';
        
        return $content . $html;
    }
    return $content;
}

```
---

### 1. L'en-tête 

```php
/**
 * Plugin Name: Réservation Coiffure (Version mde_)
 * Description: Gestion des prestations avec prix, durée et bouton de réservation.
 * Author: Prénom NOM
 */

```

* **À quoi ça sert ?** C’est ce qui permet à WordPress de reconnaître que ce fichier est un plugin. Sans ces quelques lignes de commentaires, le plugin n'apparaîtra jamais dans la liste des extensions.

### 2. Le CPT 

```php
add_action('init', 'mde_creer_cpt_prestation');
function mde_creer_cpt_prestation() {
    register_post_type('prestation', array( ... ));
}

```

* **`add_action('init', ...)`** : On dit à WordPress : "Au démarrage, crée un nouveau tiroir de rangement".
* **`register_post_type`** : On définit le nom du tiroir ("Prestations") et son icône (les ciseaux).
* **L'intérêt** : Cela sépare les services de la coiffeuse des articles de blog classiques.

### 3. La Meta Box 

```php
add_action('add_meta_boxes', 'mde_meta_details_prestation');
function mde_render_details($post) { ... }

```

* **`add_meta_box`** : On demande à WordPress d'ajouter un petit cadre de saisie sur le côté de l'écran quand on édite une prestation.
* **`mde_render_details`** : C'est la fonction qui "dessine" le formulaire HTML. On y trouve les balises `<label>` (le texte) et `<input>` (la case à remplir).
* **`get_post_meta`** : Indispensable ! Cette fonction va chercher en base de données ce qui a été écrit précédemment pour l'afficher dans la case.

### 4. La Sauvegarde 

```php
add_action('save_post', 'mde_save_details_prestation');
function mde_save_details_prestation($post_id) { ... }

```

* **`add_action('save_post', ...)`** : Se déclenche quand on clique sur le bouton "Mettre à jour" ou "Publier".
* **`update_post_meta`** : C'est ici que la magie opère. On prend ce qui a été tapé dans le formulaire (`$_POST`) et on l'enregistre définitivement dans la base de données de WordPress.
* **`sanitize_text_field`** : Une sécurité vitale qui nettoie les données pour éviter qu'un pirate n'injecte du code malveillant.

### 5. L'affichage final 

```php
add_filter('the_content', 'mde_affiche_details_final');
function mde_affiche_details_final($content) { ... }

```

* **`is_singular('prestation')`** : Très important ! On vérifie qu'on est bien sur une page de prestation. On ne veut pas afficher le tarif "Coupe de cheveux" sur un article qui parle de météo !
* **`$content . $html`** : On prend le texte écrit par la coiffeuse (`$content`) et on vient "coller" notre bloc de réservation (`$html`) juste après.
* **`return`** : On renvoie l'ensemble à WordPress pour qu'il l'affiche sur l'écran du visiteur.

---

### Résumé de la circulation des données :

1. **Saisie** : La coiffeuse tape "30€" dans le champ (`mde_render_details`).
2. **Stockage** : WordPress enregistre "30" dans une petite boîte nommée `_presta_prix` (`mde_save_details_prestation`).
3. **Lecture** : Quand un client visite la page, le plugin va chercher le "30" dans la boîte (`get_post_meta`).
4. **Affichage** : Le plugin prépare un joli rectangle avec écrit "Tarif : 30 €" (`mde_affiche_details_final`).
