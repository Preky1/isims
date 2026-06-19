<?php

declare(strict_types=1);

final class HomeController extends BaseController
{
    public function index(): void
    {
        if (auth_user()) {
            redirect('/dashboard');
        }

        $cms     = new CmsContent();
        $setting = new Setting();

        try {
            $hero        = $cms->activeHero();
            $headerMenus = $cms->menuItems('header');
            $footerMenus = $cms->menuItems('footer');
            $footer      = $cms->allFooter();
            $sec         = self::sectionMap($cms->pageSections('home'));
            $cmsMissing  = false;
        } catch (PDOException $e) {
            // CMS tables not yet created — run /migrate.php first
            $hero = $headerMenus = $footerMenus = null;
            $footer = $sec = [];
            $cmsMissing = true;
        }

        $this->view('home/index', [
            'announcements' => array_slice((new Announcement())->visibleFor(null), 0, 6),
            'events'        => array_slice((new EventItem())->upcoming(), 0, 6),
            'hero'          => $hero,
            'settings'      => $setting->all(),
            'headerMenus'   => $headerMenus,
            'footerMenus'   => $footerMenus,
            'footer'        => $footer,
            'sec'           => $sec,
            'cmsMissing'    => $cmsMissing,
        ], 'public');
    }

    /** Convert sections array into key=>row map for easy view access. */
    public static function sectionMap(array $sections): array
    {
        $map = [];
        foreach ($sections as $s) {
            $map[$s['section_key']] = $s;
        }
        return $map;
    }
}
