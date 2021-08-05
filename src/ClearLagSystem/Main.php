<?php

namespace ClearLagSystem;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\entity\object\ItemEntity;
use pocketmine\level\Level;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getLogger()->info("\n\n§c•>§a Plugin ClearLagSystem by QuyDrop §c<•\n");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    	public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool{
            if($command->getName() == "clearlag"){
                $memory = memory_get_usage();
                $entityCount = 0;
                foreach($this->getServer()->getLevels() as $level){
                    $level->doChunkGarbageCollection();
                    $level->unloadChunks(true);
                    $level->clearCache(true);
                    foreach($level->getEntities() as $entity){
                        if($entity instanceof ItemEntity){
                            $entity->close();
                            ++$entityCount;
                        }
                    }
                }
                $this->getLogger()->info("\n\n\n§l§c{$entityCount}MB§a Item(s) have/has been removed by system \n\n\n");
                $value = number_format(round((($memory - memory_get_usage()) / 1024) / 1024, 2));
                $this->getLogger()->info("\n\n\n§l§c{$value}MB§a Cache have/has been removed by system \n\n\n");
            }
		return true;
    	}
}
