# PLUGINS Wordpress

### 1. Qu'est-ce qu'un plugin ?

C'est un "petit logiciel" qui se greffe à WordPress pour ajouter une fonctionnalité que le logiciel de base ne propose pas nativement (exemple : un formulaire de contact, une boutique en ligne, ou ici, une signature).

### 2. L'analogie du "Tapis Roulant"

WordPress est comme une **chaîne de montage** dans une usine. Les articles, les images et le texte passent sur un tapis roulant avant d'arriver sur l'écran du visiteur.

Le plugin est un **ouvrier** posté sur le côté du tapis : il attrape l'article, y ajoute quelque chose (comme un tampon), et le remet sur le tapis.

### 3. Les "Hooks" (Crochets) : Le point d'entrée

Pour agir, le plugin doit s'accrocher à un endroit précis du processus de WordPress. On appelle cela un **Hook**.

* `add_filter` : Permet de **modifier** quelque chose (ex: changer le texte d'un article).
* `add_action` : Permet de **déclencher** quelque chose (ex: envoyer un e-mail après une inscription).

### 4. La règle d'or : "Ne rien casser"

L'avantage majeur d'un plugin est qu'il est **non-destructif**. Si vous désactivez le plugin, WordPress retrouve immédiatement son état d'origine. C'est la manière la plus sûre de personnaliser un site sans toucher aux fichiers de base ou au thème.

### 5. La simplicité du fichier unique

Un plugin peut être extrêmement minimaliste : un simple fichier PHP contenant un commentaire en haut (le nom du plugin) et une ligne de code suffisent pour que WordPress le reconnaisse et l'active.

---

## Démonstration "pas à pas".

### Étape 1 : Créer le dossier

Dans votre installation locale de WordPress (dossier `wp-content/plugins/`), créez un nouveau dossier nommé `signature-demo`.

### Étape 2 : Créer le fichier

Dans ce dossier, créez un fichier `signature.php` et collez ce code :

```php
<?php
/**
 * Plugin Name: Signature Démo
 * Description: Ajoute une signature sous chaque article.
 * Author: Prénom NOM
 */

add_filter( 'the_content', 'ajouter_signature' );

function ajouter_signature( $content ) {
    if ( is_single() ) {
        return $content . '<p style="color: blue;">--- Article signé par mon Plugin Signature Démo ---</p>';
    }
    return $content;
}

```

### Étape 3 : La démonstration "Eurêka" (Le test)

1. **Avant l'activation** : Ouvrez un article de blog dans votre navigateur. Le texte de l'article s'arrête normalement.
2. **Activation** : Allez dans le tableau de bord WordPress -> **Extensions**. "Signature Démo" apparaîtra. Cliquez sur **Activer**.
3. **Résultat immédiat** : Rafraîchissez la page de l'article. La signature bleue apparaît instantanément.
4. **Désactivation** : Retournez dans les extensions et cliquez sur **Désactiver**. Rafraîchissez la page : la signature disparaît.

---

## Exemple 2 : avec shortcode

### Le code (`boite-pokemon.php`)

```php
<?php
/**
 * Plugin Name: Boîte Pokémon
 * Description: Ajoute une boîte d'information Pokémon via le shortcode [pokemon_info].
 * Author: Prénom NOM
 */

// On crée le shortcode [pokemon_info]
add_shortcode( 'pokemon_info', 'creer_boite_pokemon' );

function creer_boite_pokemon( $atts, $content = null ) {
    // La boîte stylisée
    $boite = '<div style="border: 2px solid #ffcb05; padding: 15px; background: #fffde7; border-radius: 8px; font-family: sans-serif;">';
    $boite .= '<strong style="color: #3b4cca;">Info Pokémon : </strong>' . esc_html($content);
    $boite .= '</div>';
    
    return $boite; // Toujours utiliser return pour les shortcodes
}
```

1. **Personnalisation** : Le nom du shortcode doit être unique pour éviter que WordPress ne se mélange les pinceaux ("Est-ce que ce `[pokemon_info]` appartient à ce plugin ou à un autre ?").
2. **Identité visuelle** : **le code peut influencer le design** du theme actif.

"Si vous écrivez `[pokemon_info]Pikachu est de type Électrik[/pokemon_info]` dans votre article, WordPress va afficher une boîte spéciale. Si vous écrivez autre chose, il affichera du texte brut. C'est vous qui avez appris à WordPress à comprendre ce nouveau mot !"

--- 


Pour clore cette suite logique, voici comment créer un **Custom Post Type (CPT)**. C'est le niveau "Expert débutant".

### Le concept

Jusqu'ici, on modifiait des articles existants. Avec un CPT, on crée un **nouveau type de contenu** (comme "Produit" ou "Pokémon") qui a son propre menu dans l'administration.

### Le code (`pokemon-cpt.php`)

```php
<?php
/**
 * Plugin Name: Gestion Pokémon
 */

// On enregistre le type de contenu "Pokémon"
add_action( 'init', 'enregistrer_pokemon_cpt' );

function enregistrer_pokemon_cpt() {
    register_post_type( 'pokemon', array(
        'labels'      => array( 'name' => 'Pokémon', 'singular_name' => 'Pokémon' ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon'   => 'dashicons-pets', // Icône sympa dans le menu
    ));
}

```

### Pourquoi c'est la suite logique :

1. **Séparation des données** : Vos Pokémon ne sont plus mélangés avec vos articles de blog.
2. **Structure** : Vous apprenez à WordPress que "Pokémon" est un objet avec des caractéristiques (titre, texte, image).
3. **Évolution** : Une fois le CPT créé, on pourra plus tard ajouter des "Custom Fields" (ex: Type, Niveau, PV) pour enrichir la fiche.

### Comment le présenter :

* **Démo** : Après activation, un nouveau menu **"Pokémon"** apparaît dans la colonne de gauche de leur tableau de bord.
* **Le déclic** : WordPress n'est plus juste un logiciel de blog, mais une **base de données personnalisable**. On vient de transformer WordPress en une "Encyclopédie Pokémon".

--- 

**Résumé du parcours pédagogique :**

1. **Signature** : Modifier le contenu existant (Filtre).
2. **Boîte Pokémon** : Ajouter des fonctions intelligentes dans le texte (Shortcode).
3. **Type Pokémon** : Créer une nouvelle section dédiée (CPT).

--- 

# FINAL : les META

Pour ajouter un champ "Type" à votre Pokémon (ex: Feu, Eau), nous allons utiliser les **Meta Boxes**. C'est le niveau au-dessus : on ajoute un formulaire dans l'écran d'édition du Pokémon pour enregistrer des données spécifiques.

### Le code (`pokemon-meta.php`)

```php
<?php
/**
 * Plugin Name: Gestion Pokémon (Meta)
 */

// 1. Ajouter le formulaire dans l'admin
add_action( 'add_meta_boxes', 'ajouter_meta_box_pokemon' );
function ajouter_meta_box_pokemon() {
    add_meta_box( 'pokemon_type', 'Type du Pokémon', 'afficher_formulaire_type', 'pokemon', 'side' );
}

function afficher_formulaire_type( $post ) {
    $valeur = get_post_meta( $post->ID, '_pokemon_type', true );
    echo '<input type="text" name="pokemon_type" value="' . esc_attr($val) . '" placeholder="Ex: Feu">';
}

// 2. Sauvegarder la donnée
add_action( 'save_post', 'sauvegarder_type_pokemon' );
function sauvegarder_type_pokemon( $post_id ) {
    if ( isset( $_POST['pokemon_type'] ) ) {
        update_post_meta( $post_id, '_pokemon_type', sanitize_text_field( $_POST['pokemon_type'] ) );
    }
}

```

### La suite pédagogique : "Afficher la donnée"

Maintenant que le type est enregistré en base de données, comment l'afficher ? On utilise un filtre `the_content` (reprise de votre première leçon) :

```php
add_filter( 'the_content', 'afficher_type_dans_contenu' );
function afficher_type_dans_contenu( $content ) {
    if ( is_singular( 'pokemon' ) ) {
        $type = get_post_meta( get_the_ID(), '_pokemon_type', true );
        $info = '<p><strong>Type : </strong>' . esc_html($type) . '</p>';
        return $info . $content;
    }
    return $content;
}

```

### Pourquoi c'est le "boss final" pour débutants :

1. **La boucle est bouclée** : Vous créez la donnée (Meta Box), vous la stockez (`save_post`), et vous l'affichez (`the_content`).
2. **Gestion de la donnée** : Ils apprennent que WordPress n'est pas juste du texte, mais une **base de données structurée**.
3. **Réutilisabilité** : Ils peuvent maintenant créer n'importe quel type de fiche (Recettes, Livres, Employés) en changeant simplement les noms.
