Voici une fiche de révision synthétique qui regroupe les fonctions essentielles utilisées. C'est un excellent mémo afin de comprendre quel outil utiliser pour quel besoin.

---

## 📝 Fiche Récapitulative : Développement de Plugin WordPress

### 1. Structure et Contenu (CPT)
* **`register_post_type()`** : La base de tout. Permet de créer un nouveau type de contenu (ex: Prestations, Réservations).
* **`add_action('init', ...)`** : Le crochet (hook) indispensable pour enregistrer vos CPT au démarrage de WordPress.

### 2. Gestion des Données (Meta Data)
* **`add_meta_box()`** : Crée le cadre visuel dans l'administration pour saisir des informations spécifiques.
* **`update_post_meta()`** : Enregistre une donnée en base de données (le prix, la durée, etc.).
* **`get_post_meta()`** : Récupère une donnée enregistrée pour l'afficher sur le site ou dans un calcul.
* **`sanitize_text_field()`** : **Sécurité !** Nettoie les données saisies par l'utilisateur avant de les enregistrer.

### 3. Affichage et Templates
* **`add_filter('the_content', ...)`** : Permet de modifier ou d'ajouter du texte/HTML à l'intérieur d'un article à la volée.
* **`add_filter('template_include', ...)`** : L'outil ultime pour dire à WordPress d'utiliser un fichier `.php` situé dans votre plugin plutôt que dans le thème.
* **`is_singular('type')`** : Teste si l'on regarde une page de contenu unique.
* **`is_post_type_archive('type')`** : Teste si l'on regarde la liste (archive) des contenus.

### 4. Requêtes et Tri (Query)
* **`pre_get_posts`** : Le hook pour modifier la liste des résultats (trier par prix, filtrer par catégorie) avant que la page ne se charge.
* **`$query->set('meta_key', '...')`** : Indique à WordPress sur quelle donnée personnalisée effectuer un tri ou un filtre.

### 5. Interaction et Automatisation
* **`add_shortcode()`** : Crée un code court (ex: `[mon_formulaire]`) que l'utilisateur peut placer n'importe où.
* **`wp_insert_post()`** : Permet de créer un nouvel article ou une réservation automatiquement via le code.
* **`wp_mail()`** : Envoie un e-mail au format texte ou HTML.
* **`admin_url('admin-post.php')`** : L'adresse sécurisée vers laquelle envoyer les formulaires personnalisés.

---

### 💡 Le conseil "Pro"

> "WordPress est un système d'**événements**. On ne lance pas une fonction n'importe quand : on attend que WordPress nous donne le signal (le Hook). Si tu veux changer le design, utilise un **Filter**. Si tu veux faire une action (sauver, envoyer), utilise une **Action**."
