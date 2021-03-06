<?php

namespace QuyDrop\ClearLagSystem;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\object\ItemEntity;
use pocketmine\level\Level;


class Main extends PluginBase{

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
                $value = number_format(round((($memory - memory_get_usage()) / 1024) / 1024, 2));
                $this->getLogger()->info("\n\n§l§c{$value}MB§a Cache have/has been removed by system \n§l§c{$entityCount}MB§a Item(s) have/has been removed by system\n");
            }
		return true;
    	}
}
