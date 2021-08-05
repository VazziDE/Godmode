<?php


namespace Vazzi\Godmode;


use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

class GodListener implements Listener
{

	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();

		if (!Main::getInstance()->getConfig()->getNested("admin-join")) {
			return;
		}
		if ($player->isOp()) {
			Main::getInstance()->enableGodmode($player);
		}
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();

		if (!Main::getInstance()->getConfig()->getNested("disable-on-leave")) {
			return;
		}

		if (!array_key_exists($player->getName(), Main::getInstance()->godList)) {
			return;
		}

		if (Main::getInstance()->godList[$player->getName()]) {
			Main::getInstance()->disableGodmode($player, false);
		}
	}


	public function onDamage(EntityDamageEvent $event)
	{

		if (!Main::getInstance()->getConfig()->getNested("no-damage-player")) {
			return;
		}
		if (!$event->getEntity() instanceof Player) {
			return;
		}

		$player = $event->getEntity();

		if (array_key_exists($player->getName(), Main::getInstance()->godList)) {
			$event->setCancelled();
		}
	}


}