<?php

declare(strict_types=1);

function seo_title(string $pageTitle): string
{
    return $pageTitle . ' | Guia Local';
}

function seo_description(string $description, int $limit = 155): string
{
    $description = trim(strip_tags($description));
    if (mb_strlen($description, 'UTF-8') <= $limit) {
        return $description;
    }
    return mb_substr($description, 0, $limit, 'UTF-8') . '...';
}
