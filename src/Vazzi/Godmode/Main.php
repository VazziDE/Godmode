<?php

declare(strict_types=1);

namespace Vazzi\Godmode;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{


	public $godList = [];

	/**
	 * @var Main
	 */
	private static $instance;

	/**
	 * @return Main
	 */
	public static function getInstance(): Main
	{
		return self::$instance;
	}

	public function onEnable()
	{
		self::$instance = $this;

		$this->saveDefaultConfig();
		$this->getServer()->getCommandMap()->register("Godmode", new GodmodeCommand());
		$this->getServer()->getPluginManager()->registerEvents(new GodListener(), $this);
	}

	public function enableGodmode(Player $player)
	{
		Main::getInstance()->godList[$player->getName()] = true;

		if ($this->getConfig()->getNested("heal-player")) {
			$player->setHealth($player->getMaxHealth());
		}
		if ($this->getConfig()->getNested("feed-player")) {
			$player->setFood($player->getMaxFood());
		}
		if ($this->getConfig()->getNested("fly-player")) {
			$player->setFlying(true);
			$player->setAllowFlight(true);
		}
		if ($this->getConfig()->getNested("creative-player")) {
			$player->setGamemode(Player::CREATIVE);
		}

		$player->sendMessage($this->getConfig()->getNested("activate-godmode"));
	}


	public function disableGodmode(Player $player, $textplayer = true)
	{
		if (!isset(Main::getInstance()->godList[$player->getName()])) return;

		if ($this->getConfig()->getNested("fly-player")) {
			$player->setFlying(false);
			$player->setAllowFlight(false);
		}
		if ($this->getConfig()->getNested("creative-player")) {
			$player->setGamemode($this->getServer()->getDefaultGamemode());
		}

		unset(Main::getInstance()->godList[$player->getName()]);

		if ($textplayer) {
			$player->sendMessage($this->getConfig()->getNested("deactivate-godmode"));
		}

	}


}
