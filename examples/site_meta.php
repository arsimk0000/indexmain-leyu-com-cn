<?php

class SiteMetaHelper {
    private array $siteConfig = [];
    private array $metaData = [];

    public function __construct(array $config = []) {
        $this->siteConfig = $config;
        $this->metaData = $this->buildDefaultMeta();
    }

    private function buildDefaultMeta(): array {
        return [
            'site_name' => $this->siteConfig['site_name'] ?? '乐鱼体育',
            'site_url' => $this->siteConfig['site_url'] ?? 'https://indexmain-leyu.com.cn',
            'keywords' => $this->siteConfig['keywords'] ?? ['乐鱼体育'],
            'description' => $this->siteConfig['description'] ?? '乐鱼体育官方平台',
            'locale' => 'zh_CN',
        ];
    }

    public function setMetaField(string $key, $value): void {
        $this->metaData[$key] = $value;
    }

    public function getMetaField(string $key) {
        return $this->metaData[$key] ?? null;
    }

    public function generateDescription(int $maxLength = 160): string {
        $parts = [];

        $name = $this->metaData['site_name'] ?? '';
        if (!empty($name)) {
            $parts[] = $name;
        }

        $keywords = $this->metaData['keywords'] ?? [];
        if (!empty($keywords)) {
            $kwStr = implode('，', array_slice($keywords, 0, 3));
            $parts[] = $kwStr;
        }

        $desc = $this->metaData['description'] ?? '';
        if (!empty($desc)) {
            $parts[] = $desc;
        }

        $full = implode(' - ', $parts);
        $full = strip_tags($full);
        $full = trim(preg_replace('/\s+/', ' ', $full));

        if (mb_strlen($full) > $maxLength) {
            $full = mb_substr($full, 0, $maxLength - 3) . '...';
        }

        return htmlspecialchars($full, ENT_QUOTES, 'UTF-8');
    }

    public function getAllMeta(): array {
        return $this->metaData;
    }

    public function renderMetaTags(): string {
        $meta = $this->metaData;
        $tags = [];

        $tags[] = '<meta charset="UTF-8">';
        $tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

        if (!empty($meta['keywords'])) {
            $kw = is_array($meta['keywords']) ? implode(', ', $meta['keywords']) : $meta['keywords'];
            $tags[] = '<meta name="keywords" content="' . htmlspecialchars($kw, ENT_QUOTES, 'UTF-8') . '">';
        }

        if (!empty($meta['description'])) {
            $tags[] = '<meta name="description" content="' . htmlspecialchars($meta['description'], ENT_QUOTES, 'UTF-8') . '">';
        }

        if (!empty($meta['site_url'])) {
            $tags[] = '<link rel="canonical" href="' . htmlspecialchars($meta['site_url'], ENT_QUOTES, 'UTF-8') . '">';
        }

        return implode("\n", $tags);
    }
}

// 示例使用
$exampleConfig = [
    'site_name' => '乐鱼体育',
    'site_url' => 'https://indexmain-leyu.com.cn',
    'keywords' => ['乐鱼体育', '体育赛事', '在线娱乐'],
    'description' => '乐鱼体育提供丰富的体育赛事资讯与互动体验。',
];

$helper = new SiteMetaHelper($exampleConfig);

$description = $helper->generateDescription(140);
echo "Description: " . $description . "\n";

$metaTags = $helper->renderMetaTags();
echo "Meta Tags:\n" . $metaTags . "\n";