<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language file.
 *
 * @package   theme_recit2
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'RÉCIT v2';
$string['configtitle'] = 'RÉCIT v2';
$string['choosereadme'] = 'Recit est un thème moderne. Ce thème est destiné à être utilisé directement ou en tant que thème parent lors de la création de nouveaux thèmes utilisant Bootstrap 4.';

$string['login'] = 'Vous avez déjà un compte?';
$string['notenrolled'] = 'Vous n\'êtes pas inscrit(e) à ce cours';
$string['notloggedin'] = 'Vous n\'êtes pas connecté(e)';
$string['prev_section'] = 'Section précédente';
$string['next_section'] = 'Section suivante';
$string['showhideblocks'] = 'Montrer / cacher blocks';
$string['privacy:metadata'] = 'Le thème Recit ne conserve aucune données utilisateur.';
$string['coursehome'] = 'Accueil du cours';
$string['grade'] = 'Carnet des résultats';
$string['editmode'] = 'Mode édition';
$string['access'] = 'Accéder';
$string['region-side-pre'] = 'Droite';
$string['region-side-post'] = 'Centre';

// General settings tab.
$string['generalsettings'] = 'Général';
$string['logo'] = 'Logo';
$string['categoryliststyle'] = 'Apparence des catégories de cours';
$string['categoryliststyledesc'] = "Condensé : le résumé est révélé par un lien modal cliquable.
Étendu : toutes les informations sont affichées à l'intérieur d'une carte horizontale.";
$string['condensed'] = 'Condensé';
$string['extended'] = 'Étendu';
$string['logodesc'] = 'Le logo est affiché en entête.';
$string['favicon'] = 'favicon personnalisé';
$string['favicondesc'] = 'Téléverser votre favicon. Ce doit être un ficher .ico.';
$string['loginbgimg'] = 'Arrière-plan de la page de connexion';
$string['loginbgimg_desc'] = 'Téléverser votre image d\'arrère-plan de page de connexion.';
$string['enablebreadcrumb'] = "Activer la navigation dans le fil d'Ariane";
$string['enablebreadcrumbdesc'] = '';
$string['truncatesections'] = 'Tronquer le nom des sections';
$string['truncatesectionsdesc'] = '';
$string['recit2:accesshiddensections'] = 'Accès aux sections cachées';

// Frontpage settings tab.
$string['frontpagesettings'] = 'Page d\'accueil';
$string['headerimg'] = 'Image d\'entête';
$string['headerimgdesc'] = 'Téléchargez ici votre image d\'entête personnalisée si vous voulez l\'ajouter dans l\'entête. La taille de l\'image doit être de 1500px par 150px.';

$string['sliderenabled'] = 'Activer le diaporama';
$string['sliderenableddesc'] = 'Activer un diaporama en haut de votre page d\'accueil';
$string['slidercount'] = 'Nombre de diapositives';
$string['slidercountdesc'] = 'Sélectionnez le nombre de diapositives que vous souhaitez ajouter et <strong>cliquez sur ENREGISTRER</strong> pour charger les champs de saisie.';
$string['sliderimage'] = 'Image de la diapositive';
$string['sliderimagedesc'] = 'Ajoutez une image pour votre diaporama. La taille recommandée est 1500 px x 500 px ou plus.';
$string['slidertitle'] = 'Titre de la diapositive';
$string['slidertitledesc'] = 'Ajouter un titre à la diapositive.';
$string['slidercaption'] = 'Texte de la diapositive';
$string['slidercaptiondesc'] = 'Ajouter un titre à votre diapositive';

$string['featuredcourses'] = 'Cours en vedette';
$string['featuredcoursesdesc'] = 'Afficher les cours en vedette dans la page d\'accueil. Veuillez mettre l\'ID du cours séparé par une virgule. Example: 3,6,7,8';

// Footer settings tab.
$string['footersettings'] = 'Pied de page';
$string['footnote'] = 'Note de bas de page';
$string['footnotedesc'] = 'Ce que vous ajoutez dans cette zone de texte sera affiché dans le pied de page de votre site Moodle.';
$string['infolink'] = "Liens d'information";
$string['infolinkdefault'] = 'Moodle community|https://moodle.org
Moodle free support|https://moodle.org/support
Moodle Docs|http://docs.moodle.org|Moodle Docs
Moodle.com|http://moodle.com/';
$string['infolink_desc'] = "Vous pouvez configurer ici des liens d'information personnalisés qui seront affichés par les thèmes. Chaque ligne se compose d'un texte de menu, d'un lien URL (facultatif), d'un titre d'info-bulle (facultatif) et d'un code de langue ou d'une liste de codes séparés par des virgules (facultatif, pour afficher la ligne aux utilisateurs de la langue spécifiée uniquement), séparés par des caractères de séparation :
<pre> Moodle community|https://moodle.org
Moodle free support|https://moodle.org/support
Moodle development|https://moodle.org/development
Moodle Docs|http://docs.moodle.org|Moodle Docs
German Moodle Docs|http://docs.moodle.org/de|Documentation in German|de
Moodle.com|http://moodle.com/ </pre>";
$string['copyright_footer'] = "Droit d'auteur";
$string['copyright_default'] = '<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.fr"><img alt="Licence Creative Commons" style="border-width:0; float: left;" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>&nbsp;<span style="font-size: small; color: #fff;">Ces formations (sauf avis contraire) sont mises à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.fr">Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International</a>.</span>';
$string['website'] = 'URL du site web';
$string['websitedesc'] = 'URL principal de l\'organisation';
$string['mobile'] = 'Mobile';
$string['mobiledesc'] = 'Entrer numéro de téléphone';
$string['mail'] = 'E-Mail';
$string['maildesc'] = 'Entrer addresse courriel';
$string['facebook'] = 'Facebook URL';
$string['facebookdesc'] = 'Enter the URL of your Facebook. (i.e http://www.facebook.com/moodlehq)';
$string['twitter'] = 'Twitter URL';
$string['twitterdesc'] = 'Enter the URL of your twitter. (i.e http://www.twitter.com/moodlehq)';
$string['googleplus'] = 'Google Plus URL';
$string['googleplusdesc'] = 'Enter the URL of your Google Plus. (i.e http://www.googleplus.com/moodlehq)';
$string['linkedin'] = 'Linkedin URL';
$string['linkedindesc'] = 'Enter the URL of your Linkedin. (i.e http://www.linkedin.com/moodlehq)';
$string['youtube'] = 'Youtube URL';
$string['youtubedesc'] = 'Enter the URL of your Youtube. (i.e https://www.youtube.com/user/moodlehq)';
$string['instagram'] = 'Instagram URL';
$string['instagramdesc'] = 'Enter the URL of your Instagram. (i.e https://www.instagram.com/moodlehq)';
$string['footerlogo'] = 'Logo du footer';
$string['footerlogodesc'] = 'Upload your custom footer logo image here if you want to replace the default image.';
$string['termsurl'] = 'Politiques d\'utilisation';
$string['termsurldesc'] = 'Enter the URL of your Terms of Usage';
$string['policyurl'] = 'Politiques de confidentialité';
$string['policyurldesc'] = 'Enter the URL of your Privacy Policy page';

// advanced settings
$string['advancedsettings'] = 'Réglages avancés';
$string['rawscsspre'] = 'SCSS initial';
$string['rawscsspre_desc'] = "Dans ce champ, vous pouvez fournir du code SCSS d'initialisation, il sera injecté avant tout le reste. La plupart du temps, vous utiliserez ce paramètre pour définir des variables.";
$string['rawscss'] = 'SCSS extra';
$string['rawscss_desc'] = 'Utilisez ce champ pour fournir le code SCSS ou CSS qui sera injecté à la fin de la feuille de style.';

// color palette
$string['navcolor'] = 'Couleur de bas et haut de page';
$string['navcolor_desc'] = 'Couleur de bas et haut de page.';

$string['msgleavingmoodle'] = 'Êtes-vous sûr de vouloir quitter Moodle - Ce lien vous mène vers un site en dehors de Moodle?';
$string['showleavingsitewarning'] = 'Alerte si lien externe';
$string['timeout'] = 'La connexion a été perdu.';
$string['close'] = 'Fermer';
$string['msgtimeout'] = 'La connexion au serveur Moodle a été perdu. Veuillez vérifier votre connexion internet.';
$string['showleavingsitewarningdesc'] = 'Demander si l\'utilisateur veut quitter Moodle lorsqu\'un lien mène vers un site en dehors de Moodle.';

// Custom fields
$string['course-banner'] = 'Bannière du cours';
$string['course-banner-help'] = "Utiliser l'image du cours comme bannière.";
$string['course-banner-desc'] = "L'image bannière du cours par défaut";
$string['show-section-bottom-nav'] = 'Afficher la navigation par section';
$string['show-section-bottom-nav-help'] = 'Affiche en bas de page 2 boutons de navigation : section précédente et section suivante.';
$string['menu-model'] = 'Modèle de menu';
$string['menu-model-help'] = 'Le menu peut avoir un visuel différent. Vous pouvez choisir le modèle qui vous convient.';
$string['menu-m1'] = 'Horizontal 2 niveaux';
$string['menu-m2'] = 'Vertical à droite';
$string['menu-m3'] = 'Vertical à gauche';
$string['menu-m5'] = 'Horizontal 1 niveau';
$string['show-activity-nav'] = 'Afficher la navigation par activité';
$string['show-activity-nav-help'] = "Affiche en bas de page 2 boutons de navigation : activité précédente et activité suivante.";
$string['hide_restricted_section'] = "Ne pas afficher les sections qui possèdent des restrictions d'accès";
$string['hide_restricted_section_help'] = "Lorsque coché, les sections non disponibles ne sont pas affichées dans le menu.";
$string['prev_section'] = 'Section précédente';
$string['next_section'] = 'Section suivante';