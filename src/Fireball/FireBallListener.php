<?php


namespace Fireball;


use pocketmine\entity\Entity;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\Player;

class FireBallListener implements Listener
{

    public function __construct()
    {
    }

    public function explosionPrime(ExplosionPrimeEvent $event){
        $event->setCancelled(Fireball::getInstance()->bomb);
    }

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getId() == 385){
            $player->getInventory()->removeItem(Item::get(Item::FIREBALL,0,1));
            $nbt = new CompoundTag ( "", [
                "Pos" => new ListTag ( "Pos", [
                    new DoubleTag ( "", $player->x ),
                    new DoubleTag ( "", $player->y + $player->getEyeHeight () ),
                    new DoubleTag ( "", $player->z )
                ] ),
                "Motion" => new ListTag ( "Motion", [
                    new DoubleTag ( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ),
                    new DoubleTag ( "", - \sin ( $player->pitch / 180 * M_PI ) ),
                    new DoubleTag ( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) )
                ] ),
                "Rotation" => new ListTag ( "Rotation", [
                    new FloatTag ( "", $player->yaw ),
                    new FloatTag ( "", $player->pitch )
                ] )
            ] );

            /**$entity = new FBallEntity($player->level,$nbt);*/
            $entity = Entity::createEntity("SmallFireball",$player->level,$nbt,$player);
            $entity->setMotion($entity->getMotion()->multiply(2.0));
            $entity->setScale(3.0);
            $entity->spawnToAll();

            $player->getLevel()->addSound(new BlazeShootSound(new Vector3($player->getX(),$player->getY(),$player->getZ())));
        }
    }

    public function ohHitBlock(ProjectileHitBlockEvent $event){
        $entity = $event->getEntity();
        $block = $event->getBlockHit();
        if ($entity instanceof FBallEntity){
            $radius = 4;
            $position = new Position($block->getX(), $block->getY(), $block->getZ(), $block->getLevel());
            $explosion = new Explosion($position, $radius);
            $explosion->explodeA();
            $explosion->explodeB();
        }
    }

    public function onHitEntity(ProjectileHitEntityEvent $event){
        $entity = $event->getEntity();
        $hit_entity = $event->getEntityHit();
        if ($entity instanceof FBallEntity){
            if ($hit_entity instanceof Player)
        $distance = 10;
            $x = ($hit_entity->x - $entity->x) * ($distance / 2);
            $z = ($hit_entity->z - $entity->z) * ($distance / 2);
            $hit_entity->setMotion(new Vector3($x, 0.8, $z));
    }
}
}