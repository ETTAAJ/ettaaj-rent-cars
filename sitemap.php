<?php
require_once 'config.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = 'https://www.ettaajrentcars.com';
$currentDate = date('Y-m-d');

// Get all cars from database
$carsStmt = $pdo->query("SELECT id FROM cars ORDER BY id ASC");
$cars = $carsStmt->fetchAll(PDO::FETCH_ASSOC);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

// Homepage - all languages
$languages = ['en', 'fr', 'ar'];
foreach ($languages as $lang) {
    echo "  <url>\n";
    echo "    <loc>{$baseUrl}/index.php?lang={$lang}</loc>\n";
    echo "    <lastmod>{$currentDate}</lastmod>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>1.0</priority>\n";
    foreach ($languages as $altLang) {
        echo "    <xhtml:link rel=\"alternate\" hreflang=\"{$altLang}\" href=\"{$baseUrl}/index.php?lang={$altLang}\" />\n";
    }
    echo "  </url>\n";
}

// About page - all languages
foreach ($languages as $lang) {
    echo "  <url>\n";
    echo "    <loc>{$baseUrl}/about.php?lang={$lang}</loc>\n";
    echo "    <lastmod>{$currentDate}</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    foreach ($languages as $altLang) {
        echo "    <xhtml:link rel=\"alternate\" hreflang=\"{$altLang}\" href=\"{$baseUrl}/about.php?lang={$altLang}\" />\n";
    }
    echo "  </url>\n";
}

// Rental guide page - all languages
foreach ($languages as $lang) {
    echo "  <url>\n";
    echo "    <loc>{$baseUrl}/rental-guide.php?lang={$lang}</loc>\n";
    echo "    <lastmod>{$currentDate}</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.7</priority>\n";
    foreach ($languages as $altLang) {
        echo "    <xhtml:link rel=\"alternate\" hreflang=\"{$altLang}\" href=\"{$baseUrl}/rental-guide.php?lang={$altLang}\" />\n";
    }
    echo "  </url>\n";
}

// Car detail pages - all languages
foreach ($cars as $car) {
    $carId = (int)$car['id'];
    foreach ($languages as $lang) {
        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/car-detail.php?id={$carId}&amp;lang={$lang}</loc>\n";
        echo "    <lastmod>{$currentDate}</lastmod>\n";
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.9</priority>\n";
        foreach ($languages as $altLang) {
            echo "    <xhtml:link rel=\"alternate\" hreflang=\"{$altLang}\" href=\"{$baseUrl}/car-detail.php?id={$carId}&amp;lang={$altLang}\" />\n";
        }
        echo "  </url>\n";
    }
}

echo '</urlset>';
