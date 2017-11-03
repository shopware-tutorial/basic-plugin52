<?php


namespace FirstPlugin\Service;


class CategoryCheck implements CategoryCheckInterface
{
    /**
     * @var array
     */
    private $articleInfo = [];

    /**
     * @param int $articleId
     * @return bool
     */
    public function isArticleInCategory(int $articleId): bool
    {
        if (!isset($this->articleInfo[$articleId])) {
            $catId = $this->getCatId();
            $this->articleInfo[$articleId] = (bool) Shopware()->Db()->fetchOne(
                'SELECT id FROM s_articles_categories WHERE articleID = ? AND categoryID = ? LIMIT 1',
                [
                    $articleId, $catId
                ]
            );
        }
        return $this->articleInfo[$articleId];
    }

    /**
     * @return int
     */
    private function getCatId(): int
    {
        return (int)Shopware()->Config()->getByNamespace('FirstPlugin', 'catId');
    }

}