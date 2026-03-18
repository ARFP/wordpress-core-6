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

/**
 * Ajoute un bloc à la fin de chaque article.
 * @param string $content le contenu de l'article au format HTML
 * @return string Le contenu de l'article avec le bloc généré
 */
function mde_ajouter_avertissement($content) {

    $nom = get_option('mde_plugin_nom_contact', 'Mike');

    $ajout = '<hr><div>Cet article peut contenir des erreurs. Si tel est le cas, contactez ' . $nom . '</div>';

    return $content . $ajout;
}

add_filter('the_content', 'mde_ajouter_avertissement');



/**
 * Ajoute un avertissement avant le contenu d'un article la date de celui-ci est antérieure à 30 jours
 * @param string $content le contenu de l'article au format HTML
 * @return string  le contenu de l'article avec le bloc d'avertissement si l'article est antérieur à 30 jours. Sinon le contenu non modifié si l'article est plus récent.
 */
function mde_ajouter_message_vieux_articles($content) {

    if(is_single()) : // est ce qu'on est sur une page single ?
        
        $dateArticle = get_the_date('U');
        $dateAuj = date('U');
        $ecart2dates = $dateAuj - $dateArticle; // secondes entre les 2 dates

        if($ecart2dates > (30 * 24 * 60 * 60)) : // 30 jours
            $avertissement = '<div style="border:1px solid red;">Attention, cet article date un peu... Les informations qu\'il contient peuvent être obsolètes</div>';
            return $avertissement . $content;
        endif;
    endif;

    return $content; // retourne le contenu original sans modification

}

add_filter('the_content', 'mde_ajouter_message_vieux_articles');



/**
 * Affiche un lien vers l'édition de l'article si l'utilisateur a les droits d'édition
 * @param string $content le contenu de l'article au format HTML
 * @return string  le contenu de l'article avec le lien vers lédition si l'utilisateur courant possède les droits. Dans le cas contraire, le contenu non modifié
 */
function mde_afficher_lien_edition($content) {
    // Est ce que l'utilisateur connecté a les droits d'édition ?
    // Si oui : afficher le lien vers l'édition de l'article
    if(current_user_can('edit_posts')) {
        $lien = get_edit_post_link();
        $a = '<a style="background: pink; border: 1px solid pink; border-radius:50%; padding: .3rem;" href="' .$lien. '">🖉</a>';
        return $content . ' ' . $a;
    }

    return $content;
}

add_filter('the_content', 'mde_afficher_lien_edition');




/**
 * Ajoute un élément dans le menu Réglages de l'administration Wordpress pour accéder aux réglages du plugin.
 */
function mde_menu_reglages() {
    add_options_page(
        'Paramètres du plugin Exemple', // Titre de la page de réglages
        'Plugin Exemple', // Libellé dans le menu ADMIN
        'manage_options', // Niveau de permission requis
        'mysignature', // slug
        'mde_menu_reglages_afficher', // fonction d'affichage de la page de réglages
    );
}

add_action('admin_menu', 'mde_menu_reglages');

/**
 * Affiche la page de réglages du plugin
 */
function mde_menu_reglages_afficher() {

    $option_nom = get_option('mde_plugin_nom_contact', 'Mike');
    ?>
        <h1>Réglages du plugin MySignature</h1>
        <div>
            <form method="post" action="options.php">
                <?php 
                    settings_fields('mde_settings_group');
                    do_settings_sections('mde_settings_group');
                ?>
                <div>
                    <label>Nom du contact: </label>
                    <input type="text" name="mde_plugin_nom_contact" value="<?= $option_nom ?>">
                </div>
                <div>
                    <?php submit_button(); ?>
                </div>
            </form>
        </div>
    <?php
}

/**
 * Sauvegarde des options en base de données
 */
function mde_sauvegarde_reglages() {
    register_setting('mde_settings_group', 'mde_plugin_nom_contact');
}

add_action('admin_init', 'mde_sauvegarde_reglages');

