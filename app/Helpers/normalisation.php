<?php

function normaliser(string $texte): string
{
    $texte = mb_strtolower(trim($texte));
    $texte = str_replace(
        ['é','è','ê','ë','à','â','ï','î','ô','ù','û','ç'],
        ['e','e','e','e','a','a','i','i','o','u','u','c'],
        $texte
    );
    return preg_replace('/\s+/', ' ', $texte);
}