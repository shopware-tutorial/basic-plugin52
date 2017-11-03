<?php


namespace FirstPlugin\Service;


interface CategoryCheckInterface
{

    /**
     * @param int $articleId
     * @return bool
     */
    public function isArticleInCategory(int $articleId): bool;
}