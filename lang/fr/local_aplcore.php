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
 * @author      Valery Fremaux <valery.fremaux@gmail.com> (ActiveProLearn.com)
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com>, Florence Labord <labord.florence@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['activate'] = 'Activer';
$string['activationoption'] = 'Option d\'activation';
$string['activationoption_help'] = 'Ce plugin peut avoir plusieurs options d\'activation dans le catalogue du fournisseur. Choissisez celle qui contient le mieux à votre situation.';
$string['chooseoption'] = 'Choisir une option d\'activation...';
$string['configdocbaseurl'] = 'Url de base de la documentation';
$string['configdocbaseurl_desc'] = 'Url de base du volume de documentation source.';
$string['configdoccustomerid'] = 'ID d\'abonné à la documentation';
$string['configdoccustomerid_desc'] = 'Identifiant d\'abonné à la documentation';
$string['configdoccustomerpublickey'] = 'Clef publique de documentation';
$string['configdoccustomerpublickey_desc'] = 'La clef publique  d\'encodage des tickets d\'accès à la documentation. Cette clef vous est fournie par l\'éditeur de la documentation.';
$string['configeditorplugins'] = 'Catalogue de plugins pour documentation additionnelle';
$string['continue'] = 'Continuer';
$string['editname'] = 'Modifier le nom';
$string['emulatecommunity'] = '<a name="getsupportlicense"></a>Emuler la version communautaire';
$string['emulatecommunity_desc'] = 'Bascule le code sur la version communautaire. Le résultat est plus compatible avec d\'autres installations, mais certaines fonctionnalités avancées ne seront plus disponibles.';
$string['erroremptydistributorkey'] = 'Clef du distributeur non fournie';
$string['erroremptyprovider'] = 'Fournisseur non spécifié';
$string['errorjson'] = 'Erreur : La réponse JSON est vide ou n\'est pas interprétable.';
$string['errornodistributorkey'] = 'Clef distributeru non fournie';
$string['errornokeygenerated'] = 'Erreur : La clef n\'est pas générée ou n\'est pas conforme.';
$string['errornooptions'] = 'Erreur : Aucune option d\'activation trouvée.';
$string['errorresponse'] = 'Erreur : La réponse est valide mais en erreur : {$a}';
$string['getlicensekey'] = 'Obtenir une clef de license support';
$string['getlicensekey_desc'] = '<a name="getsupportlicense"></a>Dans certains cas, les intégrateurs (ou administrateurs) peuvent obtenir directement une clef de license support auprès d\'un fournisseur pour activer les parties "pro" du plugin. <br><a href="{$a}">Enregistrer le plugin</a>';
$string['helponblock'] = 'Aide pour le bloc ';
$string['helponmodule'] = 'Aide sur le module d\'activité ';
$string['licensekey'] = 'Clef de license pro';
$string['licensekey_desc'] = 'Entrez ici la clef de produit que vous avez reçu de votre distributeur.';
$string['licenseprovider'] = 'Fournisseur version Pro';
$string['licenseprovider_desc'] = 'Entrez la clef de votre distributeur.';
$string['licensestatus'] = 'Etat de license pro';
$string['nextstep'] = 'Etape suivante';
$string['noproaccess'] = 'Ceci est une partie "pro" limitée du plugin qui n\'est pas activée.';
$string['options'] = 'Options d\'activation';
$string['partnerkey'] = 'Clef distributeur partenaire';
$string['partnerkey_help'] = 'La clef partenaire a été fournie à l\'acteur désigné pour installer le plugin.';
$string['pluginname'] = 'Surcharges core pour les plugins APL';
$string['previousstep'] = 'Etape précédente';
$string['privacy:metadata'] = 'Le plugin APLCore ne détient pas de données utilisateur.';
$string['provider'] = 'Fournisseur de support';
$string['provider_help'] = 'Code du fournisseur du support. Ce code identifie le prestataire fournissant le support de niveau 3 et la garantie de continuité du plugin.';
$string['specificprosettings'] = 'Réglages spécifiques version "pro"';
$string['start'] = 'Identification du distributeur';

require(__DIR__.'/pro_additional_strings.php');
