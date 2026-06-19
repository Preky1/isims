<?php

declare(strict_types=1);

final class CmsContent extends BaseModel
{
    // ── Hero ──────────────────────────────────────────────────────
    public function activeHero(): ?array
    {
        return $this->query(
            'SELECT * FROM cms_hero WHERE is_active = 1 ORDER BY sort_order LIMIT 1'
        )->fetch() ?: null;
    }

    public function allHero(): array
    {
        return $this->query('SELECT * FROM cms_hero ORDER BY sort_order')->fetchAll();
    }

    public function saveHero(int $id, array $d): void
    {
        $this->query(
            'UPDATE cms_hero SET eyebrow=?, title=?, subtitle=?, description=?,
             btn1_text=?, btn1_url=?, btn1_icon=?, btn2_text=?, btn2_url=?, btn2_icon=?,
             bg_image=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?',
            [$d['eyebrow'],$d['title'],$d['subtitle'],$d['description'],
             $d['btn1_text'],$d['btn1_url'],$d['btn1_icon'],
             $d['btn2_text'],$d['btn2_url'],$d['btn2_icon'],
             $d['bg_image'],$d['is_active'],$d['sort_order'],$id]
        );
    }

    public function createHero(array $d): void
    {
        $this->query(
            'INSERT INTO cms_hero (eyebrow,title,subtitle,description,
             btn1_text,btn1_url,btn1_icon,btn2_text,btn2_url,btn2_icon,
             bg_image,is_active,sort_order)
             VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)',
            [$d['eyebrow'],$d['title'],$d['subtitle'],$d['description'],
             $d['btn1_text'],$d['btn1_url'],$d['btn1_icon'],
             $d['btn2_text'],$d['btn2_url'],$d['btn2_icon'],
             $d['bg_image'],$d['is_active'],$d['sort_order']]
        );
    }

    public function deleteHero(int $id): void
    {
        $this->query('DELETE FROM cms_hero WHERE id=?', [$id]);
    }

    // ── Sections ──────────────────────────────────────────────────
    public function section(string $page, string $key): array
    {
        $row = $this->query(
            'SELECT * FROM cms_sections WHERE page=? AND section_key=? LIMIT 1',
            [$page, $key]
        )->fetch();
        return $row ?: ['heading'=>'','subheading'=>'','body'=>'','image'=>'','bg_color'=>'','is_active'=>1];
    }

    public function pageSections(string $page): array
    {
        return $this->query(
            'SELECT * FROM cms_sections WHERE page=? ORDER BY sort_order',
            [$page]
        )->fetchAll();
    }

    public function saveSection(string $page, string $key, array $d): void
    {
        $this->query(
            'INSERT INTO cms_sections (page,section_key,heading,subheading,body,image,bg_color,is_active,sort_order)
             VALUES(?,?,?,?,?,?,?,?,?)
             ON DUPLICATE KEY UPDATE
             heading=VALUES(heading), subheading=VALUES(subheading), body=VALUES(body),
             image=VALUES(image), bg_color=VALUES(bg_color),
             is_active=VALUES(is_active), sort_order=VALUES(sort_order), updated_at=NOW()',
            [$page,$key,$d['heading'],$d['subheading'],$d['body'],$d['image'],$d['bg_color'],$d['is_active'],$d['sort_order']]
        );
    }

    public function deleteSection(int $id): void
    {
        $this->query('DELETE FROM cms_sections WHERE id=?', [$id]);
    }

    public function reorderSections(array $ids): void
    {
        foreach ($ids as $order => $id) {
            $this->query('UPDATE cms_sections SET sort_order=? WHERE id=?', [$order, (int)$id]);
        }
    }

    public function reorderMenus(array $ids): void
    {
        foreach ($ids as $order => $id) {
            $this->query('UPDATE cms_menus SET sort_order=? WHERE id=?', [$order, (int)$id]);
        }
    }

    public function reorderHero(array $ids): void
    {
        foreach ($ids as $order => $id) {
            $this->query('UPDATE cms_hero SET sort_order=? WHERE id=?', [$order, (int)$id]);
        }
    }

    // ── Menus ─────────────────────────────────────────────────────
    public function menuItems(string $location = 'header'): array
    {
        return $this->query(
            'SELECT * FROM cms_menus WHERE location=? AND is_active=1 ORDER BY sort_order',
            [$location]
        )->fetchAll();
    }

    public function allMenuItems(): array
    {
        return $this->query('SELECT * FROM cms_menus ORDER BY location, sort_order')->fetchAll();
    }

    public function saveMenu(int $id, array $d): void
    {
        $this->query(
            'UPDATE cms_menus SET label=?,url=?,target=?,icon=?,location=?,sort_order=?,is_active=? WHERE id=?',
            [$d['label'],$d['url'],$d['target'],$d['icon'],$d['location'],$d['sort_order'],$d['is_active'],$id]
        );
    }

    public function createMenu(array $d): void
    {
        $this->query(
            'INSERT INTO cms_menus (label,url,target,icon,location,sort_order,is_active)
             VALUES(?,?,?,?,?,?,?)',
            [$d['label'],$d['url'],$d['target'],$d['icon'],$d['location'],$d['sort_order'],1]
        );
    }

    public function deleteMenu(int $id): void
    {
        $this->query('DELETE FROM cms_menus WHERE id=?', [$id]);
    }

    // ── Media ─────────────────────────────────────────────────────
    public function allMedia(string $type = ''): array
    {
        if ($type) {
            return $this->query(
                'SELECT * FROM cms_media WHERE mime_type LIKE ? ORDER BY created_at DESC',
                [$type . '%']
            )->fetchAll();
        }
        return $this->query('SELECT * FROM cms_media ORDER BY created_at DESC')->fetchAll();
    }

    public function addMedia(array $d): int
    {
        $this->query(
            'INSERT INTO cms_media (file_name,file_path,mime_type,file_size,alt_text,uploaded_by)
             VALUES(?,?,?,?,?,?)',
            [$d['file_name'],$d['file_path'],$d['mime_type'],$d['file_size'],$d['alt_text'],$d['uploaded_by']]
        );
        return (int) $this->db->lastInsertId();
    }

    public function deleteMedia(int $id): ?string
    {
        $row = $this->query('SELECT file_path FROM cms_media WHERE id=?', [$id])->fetch();
        if ($row) {
            $this->query('DELETE FROM cms_media WHERE id=?', [$id]);
            return $row['file_path'];
        }
        return null;
    }

    // ── Footer ────────────────────────────────────────────────────
    public function allFooter(): array
    {
        $rows = $this->query('SELECT `key`,`value` FROM cms_footer')->fetchAll();
        $out = [];
        foreach ($rows as $r) { $out[$r['key']] = $r['value']; }
        return $out;
    }

    public function saveFooterMany(array $data): void
    {
        foreach ($data as $k => $v) {
            $this->query(
                'INSERT INTO cms_footer (`key`,`value`) VALUES(?,?)
                 ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)',
                [$k, (string)$v]
            );
        }
    }
}
