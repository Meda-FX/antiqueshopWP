<?php
/**
 * Start of system options (settings, pages, widgets, metaboxs, etc.)
 *
 * @since   1.0     2019-09-07      Release
 * @since   1.6     2019-11-25      Menu was added
 * @since   1.7     2020-02-19      Integracion fue agregada
 *
 */
// ───────────────────────────
use FDC as fdc;
use FDC\Admin\Fdc_Admin as fdcAdmin;
// ───────────────────────────

// ─── Registrer new plugin ────────
PF::new_plugin();

// ─── Start of call for options (Backend & Manager Login) ────────
if( fdcAdmin::isUserAllow() ){
    require_once fdc\PATH . 'admin/options/settings/options-meta.php';
    require_once fdc\PATH . 'admin/options/settings/options-user.php';
    require_once fdc\PATH . 'admin/options/settings/options-term.php';
    require_once fdc\PATH . 'admin/options/settings/options-post.php';
    require_once fdc\PATH . 'admin/options/settings/options-comment.php';
    require_once fdc\PATH . 'admin/options/settings/options-menu.php';
    require_once fdc\PATH . 'admin/options/settings/options-delete.php';
    require_once fdc\PATH . 'admin/options/settings/options-integration.php';
    require_once fdc\PATH . 'admin/options/settings/options.php';
}

// ─── Add plugin to framework ────────
PF::addPlugin( fdc\ID );