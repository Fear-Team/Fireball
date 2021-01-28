<?php


namespace Fireball;


use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\level\Explosion;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\MainLogger;

class Fireball extends PluginBase
{

    /** @var $api */
    protected static $api;

    /** @var bool $bomb */
    public $bomb = true;

    public function onEnable()
    {
        Entity::registerEntity(FBallEntity::class, false, ['SmallFireball', 'minecraft:small_fireball']);
        Item::addCreativeItem(ItemFactory::get(ItemIds::FIREBALL));
        static::$api = $this;
       MainLogger::getLogger()->info("Plugin activated.");
       Server::getInstance()->getPluginManager()->registerEvents(new FireBallListener(),$this);
    }

    public static function getInstance(): Fireball{
        return static::$api;
    }
}