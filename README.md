# Eichendorff – multisite (PL + DE)

Rebuild of [eichendorff.pl](https://eichendorff.pl) (GCKiS / OEKBZ, Łubowice) as a WordPress multisite
with a block (FSE) theme: the Polish site at `/` and the German site at `/de/`.

The block theme was first built on a single-site install (`eichendorff.local`); this repo continues it
on a multisite network. The theme's history is kept under `wp-content/themes/eichendorff`.

## What is in the repo

| Path | What |
| --- | --- |
| `wp-content/themes/eichendorff/` | Block theme: templates, parts, patterns, `theme.json`, events and landing logic, import tools |
| `wp-content/themes/eichendorff/openspec/` | OpenSpec specs and archived changes |
| `wp-content/plugins/network-media-library/` | Shared media library for the network |
| `wp-content/mu-plugins/network-media-library-site.php` | Makes the Polish site (ID 1) the shared library |
| `wp-config-multisite.sample.php` | Multisite constants for `wp-config.php` |

WordPress core, `wp-config.php`, `uploads/` and the database are not in the repo.

## Local setup

1. Create a site in Local (domain `eichendorffmultisite.local`) and turn it into a subdirectory multisite
   using the constants from `wp-config-multisite.sample.php`.
2. Copy `wp-content/` from this repo over the site's `wp-content/`.
3. Network-activate *Network Media Library*, activate the *Eichendorff* theme on both sites.
4. Add the German site at `/de/`, with site language `de_DE`.
5. Install the core translations `pl_PL` and `de_DE` into `wp-content/languages`
   (`wp language core install pl_PL de_DE`, or copy the `*pl_PL*` and `*de_DE*` files
   from another local install of the same WordPress version). Without them the Polish
   site shows English dates and month badges.
6. Content: the import scripts are in `wp-content/themes/eichendorff/tools/`.
