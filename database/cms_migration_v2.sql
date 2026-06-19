-- ═══════════════════════════════════════════════════════════════════
-- ISIMS CMS Migration v2 — run once against the isims database
-- Adds missing settings keys; no new tables required.
-- ═══════════════════════════════════════════════════════════════════
USE isims;

-- Ensure site_tagline and favicon_path exist (already in v1 migration;
-- this INSERT IGNORE is safe to re-run)
INSERT IGNORE INTO settings (`key`, `value`) VALUES
('site_tagline', 'Empowering International Students at UNILAK Nyanza'),
('favicon_path', '');

-- Ensure uploads subdirectory setting used by MediaLibrary
INSERT IGNORE INTO settings (`key`, `value`) VALUES
('uploads_path', 'assets/uploads/');
