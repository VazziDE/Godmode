<?php


namespace Vazzi\Godmode;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class GodmodeCommand extends Command implements PluginIdentifiableCommand
{
	public function __construct()
	{
		parent::__construct("godmode", "activate godmode to join", "godmode", ["god"]);
		$this->setPermission("godmode.use");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{

		if (!$sender instanceof Player) {
			return true;
		}

		if (!$this->testPermission($sender)) {
			return true;
		}

		if (!array_key_exists($sender->getName(), Main::getInstance()->godList)) {
			Main::getInstance()->enableGodmode($sender);
			return true;
		}

		if (Main::getInstance()->godList[$sender->getName()]) {
			Main::getInstance()->disableGodmode($sender);
			return true;
		}

		return true;
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(): Plugin
	{
		return Main::getInstance();
	}

}