<?php
declare(strict_types=1);

namespace Akid\PokeApi\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddPokemonNameAttribute implements DataPatchInterface
{
    public const string POKEMON_NAME_ATTRIBUTE_CODE = 'pokemon_name';

    private $moduleDataSetup;
    private $eavSetupFactory;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup, EavSetupFactory $eavSetupFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(Product::ENTITY, self::POKEMON_NAME_ATTRIBUTE_CODE, [
            'type' => 'varchar',
            'label' => 'Pokemon Name',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'used_in_product_listing' => true
        ]);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
