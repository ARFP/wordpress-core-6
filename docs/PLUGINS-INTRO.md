# Wordpress : PLUGINS - Introduction

Créer un plugin WordPress, c'est comme ajouter une option personnalisée sur une machine bien huilée : on ne touche pas au moteur (le Core de Wordpress), on ajoute des fonctionnalités par-dessus.

Voici les principales possibilités et types de fonctionnalités que l'on peut développer avec un plugin Wordpress.

---

## 1. La manipulation du contenu (Hooks)

C'est la base de WordPress. Intercepter le contenu avant qu'il ne s'affiche ou au moment où il est enregistré.

* **Filtres de texte :** Ajouter automatiquement une signature, un avertissement légal ou des liens d'affiliation à la fin de chaque article.
* **Shortcodes :** Créer des codes personnalisés (ex: `[mon_bouton]`) que l'utilisateur place dans l'éditeur pour afficher un élément complexe (formulaire, galerie, prix dynamique).
* **Blocs Gutenberg :** Créer tes propres blocs visuels pour l'éditeur moderne de WordPress en utilisant React.

## 2. L'extension de la base de données

WordPress est limité par défaut à quelques types de contenus. Un plugin peut briser ces limites.

* **Custom Post Types (CPT) :** Créer des sections dédiées comme "Portfolio", "Témoignages"...
* **Custom Taxonomies :** Créer des systèmes de tris personnalisés (ex: classer les chantiers par "Ville" ou par "Type de prestation").
* **Metadata :** Ajouter des champs personnalisés à des articles ou des utilisateurs (ex: ajouter un champ "Numéro de téléphone" au profil des membres).

## 3. L'automatisation et les tâches de fond

Un plugin peut faire travailler le serveur sans intervention humaine.

* **WP-Cron :** Planifier des tâches automatiques, comme envoyer un rapport d'activité par mail tous les lundis matin ou vider une base de données temporaire.
* **API REST :** Créer des "portes d'entrée" pour que le site Wordpress communique avec une application mobile ou un logiciel externe.

## 4. L'administration et le tableau de bord

Tu peux modifier l'expérience de celui qui gère le site.
* **Pages de réglages :** Créer un menu spécifique dans l'admin pour configurer le plugin (couleurs, options, clés API).
* **Widgets de tableau de bord :** Afficher des statistiques ou des alertes directement sur la page d'accueil de l'administration WordPress.
* **Gestion des rôles :** Créer de nouveaux types d'utilisateurs avec des permissions spécifiques (ex: un rôle "Commercial" qui ne voit que ses rendez-vous).

## 5. L'interaction avec l'écosystème (Add-ons)

Beaucoup de plugins servent uniquement à en améliorer d'autres.

* **Extensions pour WooCommerce :** Ajouter un mode de calcul de frais de port spécifique ou un nouveau moyen de paiement.
* **Extensions de formulaires :** Ajouter une action spécifique (ex: envoyer un SMS) après qu'un formulaire de contact a été envoyé.

---

> La règle d'or d'un bon plugin est : **"Une seule fonction, mais faite parfaitement."**.


## PHP ou React

WordPress utilise principalement le **PHP**, mais il est possible de créer des interfaces modernes dans l'administration en utilisant **JavaScript (React)**.

Quand on parle de **React** dans WordPress, on parle presque exclusivement de **Gutenberg** (l'éditeur de blocs introduit avec la version 5.0).

### 1. Pourquoi React ?

Avant, l'administration de WordPress était gérée en PHP "classique" (rendu côté serveur). Avec Gutenberg, WordPress est passé à une interface de type **SPA (Single Page Application)**. 

React permet de manipuler les blocs en temps réel (drag & drop, prévisualisation immédiate) sans recharger la page.

### 2. Le fonctionnement de Gutenberg

* **Côté Admin (JS)** : Le développeur créé des blocs en React. WordPress fournit une bibliothèque de composants (boutons, sélecteurs de couleur, champs texte) via le package `@wordpress/components`.
* **Côté Base de données (HTML)** : React génère du HTML avec des commentaires spécifiques. C'est ce qui est stocké dans la table `wp_posts`.
* **Côté Visiteur (PHP)** : WordPress lit ces commentaires et affiche le HTML.

### 3. Faut-il être un expert React ?

Pour créer des blocs personnalisés, oui, il faut manipuler du JSX et les "Hooks" WordPress. Cependant, WordPress propose deux approches :
* **Blocs Natifs (JS/React)** : Très performants, mais demandent une étape de compilation (Babel/Webpack).
* **Blocs ACF (PHP)** : En utilisant l'extension *Advanced Custom Fields Pro*, il est possible de créer des blocs Gutenberg en pur PHP, sans toucher à React.

### 4. L'outil indispensable : `@wordpress/scripts`

Pour ne pas s'embêter avec la configuration Webpack, WordPress fournit un package qui gère tout. Dans le dossier plugins de Wordpress, lancer la commande :

`npx @wordpress/create-block`

Cela génère une structure prête à l'emploi avec React déjà configuré.


---

## Docker

Si vous souhaitez développer des blocs React, il faudra avoir **Node.js** installé (soit sur la machine Windows, soit dans un conteneur dédié) pour "compiler" le JavaScript vers le dossier `build/` du plugin.

En ajoutant **Node.js** et **npm** à l'image WordPress, vous pourrez compiler vos blocs React (Gutenberg) directement depuis le conteneur sans rien installer sur votre Windows.

### 1. Mise à jour du Dockerfile WordPress

Modifier le `Dockerfile` pour inclure Node.js (version LTS) et l'outil de build de WordPress.

```dockerfile
# Installation de Node.js et NPM (via le dépôt Nodesource)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Installation globale de l'outil de création de blocs (optionnel mais pratique)
RUN npm install -g @wordpress/create-block
```

Ces lignes sont à ajouter juste avant `WORKDIR /var/www/html`.

---

### 2. Comment créer et compiler un bloc React ?

Une fois le conteneur relancé (`docker compose up --build`), vous pouvez entrer dans le conteneur pour générer un bloc :

#### Étape A : Créer le bloc

Ouvrez un terminal dans votre dossier de projet et tapez :

```bash
docker compose exec wordpress bash
cd wp-content/plugins
npx @wordpress/create-block mon-super-bloc
```
Cela va créer un dossier `mon-super-bloc` avec toute la structure React.

#### Étape B : Compiler en temps réel (Watch)

Pour que vos modifications JS soient prises en compte immédiatement pendant que vous codez :

```bash
docker compose exec wordpress bash
cd wp-content/plugins/mon-super-bloc
npm start
```

*Le script `npm start` surveille vos fichiers `.js` et les compile automatiquement dans un dossier `build/` que WordPress sait lire.*

---

### 3. Pourquoi Gutenberg ?

Gutenberg sépare le code en deux parties dans votre plugin :

1.  **`src/`** : Vos fichiers React modernes (JSX, ESNext). C'est ce que vous éditez.
2.  **`build/`** : Le code compilé en JavaScript "standard" que le navigateur peut lire. C'est ce que WordPress charge.
