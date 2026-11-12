# moodle-local_aplcore

Installs a central strategy for "pro" addition management and common usefull libraries for all
APL plugins.

Official plugin documentation at : https://ma.formation-enligne.com/course/view.php?id=2158

X.X.0003
#######################################

Add support to multi language distribution

X.X.0005 (2026051600)
#######################################

Transform to fit Moodle.org MarketPlace validation rules.

X.X.0006 (2026071600)
#######################################

Add a generic classloader extension to load any 'pro' class discovered in 'pro' section of
a protected plugin.

X.X.0008 (2026071600)
#######################################
Get rid of all old prolib.php calls.

X.X.0009 (2026071601)
#######################################
Add style provision for visualselector selected state.
X.X.0010 (2026111202)
#######################################
Licensing : negative cache in license_manager::set_and_check_license_key(). A failed license check (license refused, or router unreachable) is remembered 5 minutes (NEGATIVE_CACHE_TTL) in the component "pro" cache, bound to the key/provider/host checked, so unlicensed or unreachable installs no longer call the license router (with its 20s/200s timeouts) each time a pro feature is tested. A new key is checked immediately; explicit admin checks (ajax license service) clear the negative cache (clear_license_failure()). Applies to all components whose pro_manager extends license_manager.

X.X.0011 (2026111203)
#######################################
Licensing : self token. Sites owned by the license distributor can declare a shared secret ($CFG->aplcore_selfsecret in config.php) : license_manager::get_self_token() builds a HMAC token (host, component, service, UTC day) sent as "selftoken" with the check request, so such sites are granted without being checked, and without trusting the host they declare (which is client given).

X.X.0012 (2026111204)
#######################################
Licensing : the last valid license status is kept for a 30 days grace period (GRACE_PERIOD, after the normal 10 days validity) when the license router can not be reached or gives no license decision (provider/proxy errors, empty answer), instead of dropping the pro features of a licensed site. A refusal ("CHECK FAILURE : nnn") still invalidates the status at once, and a NEW key never inherits the status of the former one. Fixed license_manager::get_plugin_cached() : reversed get_config() arguments, undefined $matches, state analysis done on the license key instead of the last failure.
