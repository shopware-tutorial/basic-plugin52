<?php


namespace FirstPlugin\Service;


class CategoryCheck implements CategoryCheckInterface
{
    /**
     * @var array
     */
    private $articleInfo = [];

    /**
     * @var \Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    private $db;

    /**
     * @var \Shopware_Components_Config
     */
    private $config;

    /**
     * @param \Enlight_Components_Db_Adapter_Pdo_Mysql $db
     * @param \Shopware_Components_Config $config
     */
    public function __construct(\Enlight_Components_Db_Adapter_Pdo_Mysql $db, \Shopware_Components_Config $config)
    {
        $this->db = $db;
        $this->config = $config;
    }


    /**
     * @param int $articleId
     * @return bool
     */
    public function isArticleInCategory(int $articleId): bool
    {
        if (!isset($this->articleInfo[$articleId])) {
            $catId = $this->getCatId();
            $this->articleInfo[$articleId] = (bool) $this->db->fetchOne(
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
        return (int)$this->config->getByNamespace('FirstPlugin', 'catId');
    }

}