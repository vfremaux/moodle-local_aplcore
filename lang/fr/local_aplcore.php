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
 * Strings for local_aplcore
 *
 * @package     local_aplcore
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2014 onwards Valery Fremaux (https://www.activeprolearn.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Abusive rules for langage files. If fits for very simple plugins, does NOT fit for complex
// highly architectured plugins.
// Needed for separating string sections.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

$string['addelement'] = 'Ajouter un élément';
$string['configdocbaseurl'] = 'Url de base de la documentation';
$string['configdocbaseurl_desc'] = 'Url de base du volume de documentation source.';
$string['configdoccustomerid'] = 'ID d\'abonné à la documentation';
$string['configdoccustomerid_desc'] = 'Identifiant d\'abonné à la documentation';
$string['configdoccustomerpublickey'] = 'Clef publique de documentation';
$string['configdoccustomerpublickey_desc'] = 'La clef publique  d\'encodage des tickets d\'accès à la documentation. Cette clef vous est fournie par l\'éditeur de la documentation.';
$string['configeditorplugins'] = 'Catalogue de plugins pour documentation additionnelle';
$string['configeditorplugins_desc'] = '';
$string['courseselectorautoselectunique'] = 'Sélectionner le résultat unique';
$string['courseselectorpreserveselected'] = 'Préserver la sélection';
$string['courseselectorsearchanywhere'] = 'Chercher partout';
$string['dockeyfailure'] = 'La clef de documentation est vide ou n\'est pas une clef publique.';
$string['editname'] = 'Modifier le nom';
$string['helponblock'] = 'Aide pour le bloc ';
$string['helponmodule'] = 'Aide sur le module d\'activité ';
$string['less'] = 'Moins...';
$string['more'] = 'Plus...';
$string['nextstep'] = 'Etape suivante';
$string['nomatchingcourses'] = 'Aucun cours correspondant';
$string['nooptions'] = 'Pas d\'options';
$string['pluginname'] = 'Surcharges core pour les plugins APL';
$string['previouslyselectedcourses'] = 'Sélection précédente';
$string['previousstep'] = 'Etape précédente';
$string['privacy:metadata'] = 'Le plugin APLCore ne détient pas de données utilisateur.';

// APL Pro strings section.
$string['activate'] = 'Activer';
$string['activationoption'] = 'Option d\'activation';
$string['cachedef_pro'] = 'Stocke des données spécifiques de la zone "pro"';
$string['chooseoption'] = 'Choisir une option d\'activation...';
$string['continue'] = 'Continuer';
$string['emulatecommunity'] = '<a name="getsupportlicense"></a>Emuler la version communautaire';
$string['erroremptydistributorkey'] = 'Clef du distributeur non fournie';
$string['erroremptyprovider'] = 'Fournisseur non spécifié';
$string['errorjson'] = 'Erreur : La réponse JSON est vide ou n\'est pas interprétable.';
$string['errornodistributorkey'] = 'Clef distributeru non fournie';
$string['errornokeygenerated'] = 'Erreur : La clef n\'est pas générée ou n\'est pas conforme.';
$string['errornooptions'] = 'Erreur : Aucune option d\'activation trouvée.';
$string['errorresponse'] = 'Erreur : La réponse est valide mais en erreur : {$a}';
$string['forcingprofeature'] = 'Vous essayez d\'utiliser une fonctionnalité pro sans clef de licence';
$string['getlicensekey'] = 'Obtenir une clef de license support';
$string['licensekey'] = 'Clef de license pro';
$string['licensekey_desc'] = 'Entrez ici la clef de produit que vous avez reçu de votre distributeur.';
$string['licenseprovider'] = 'Fournisseur version Pro';
$string['licenseprovider_desc'] = 'Entrez la clef de votre fournisseur.';
$string['licensestatus'] = 'Etat de license pro';
$string['marketplaceonboarding'] = 'Vous avez acheté ce plugin sur la Marketplace Moodle, cliquez sur le lien ci-dessous pour<br><a target="_blank" href="https://ma.formation-enligne.com/local/marketboarding/boarding.php?lang={$a->lang}&plugin={$a->plugin}&wwwroot={$a->wwwroot}">obtenir la clef d\'activation de votre plugin</a>';
$string['noproaccess'] = 'Ceci est une partie "pro" limitée du plugin qui n\'est pas activée.';
$string['options'] = 'Options d\'activation';
$string['partnerkey'] = 'Clef distributeur partenaire';
$string['plugindist'] = 'Distribution du plugin';
$string['provider'] = 'Fournisseur de support';
$string['specificprosettings'] = 'Réglages spécifiques version "pro"';
$string['start'] = 'Identification du distributeur';

$string['plugindist_desc'] = '
<p>Ce plugin est distribué dans la communauté Moodle pour l\'évaluation de ses fonctions centrales
correspondant à une utilisation courante du plugin. Une version "professionnelle" de ce plugin existe et est distribuée
sous certaines conditions, afin de soutenir l\'effort de développement, amélioration; documentation et suivi des versions.</p>
<p>Contactez un distributeur pour obtenir la version "Pro" et son support.</p>
<p><a href="http://www.mylearningfactory.com/index.php/documentation/Distributeurs?lang=fr_utf8">Distributeurs MyLF</a></p>';

$string['emulatecommunity_desc'] = 'Bascule le code sur la version communautaire. Le résultat est plus compatible avec d\'autres installations,
mais certaines fonctionnalités avancées ne seront plus disponibles.';

$string['getlicensekey_desc'] = '<a name="getsupportlicense"></a>Si vous êtes distributeur partenaire, utilisez le lien ci-dessous pour enregistrer le plugin et générer la clef de licence :
<br><a href="{$a}">Enregistrer le plugin</a>';

$string['provider_help'] = 'Code du fournisseur du support. Ce code identifie le prestataire fournissant le support de niveau 3 et la garantie de continuité du plugin.';
$string['partnerkey_help'] = 'La clef partenaire a été fournie à l\'acteur désigné pour installer et activer le plugin.';

$string['emulatecommunity_desc'] = 'Si elle est activée, cette option force le composant à fonctionner en
version communautaire. Le fonctionnement sera plus compatible avec d\'autres installations, mais certaines
fonctionnalités ne seront plus disponibles.';

$string['activationoption_help'] = 'Ce plugin peut avoir plusieurs options d\'activation dans le catalogue du fournisseur. Choissisez celle qui contient le mieux à votre situation.';
