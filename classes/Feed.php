<?php

/* zKillboard
 * Copyright (C) 2012-2015 EVE-KILL Team and EVSCO.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Feed
{
	/**
	 * Returns kills in json format according to the specified parameters
	 *
	 * @static
	 * @param array $parameters
	 * @return array
	 */
	public static function getKills($parameters = array())
	{
		global $debug;
		$ip = IP::get();

		$userAgent = @$_SERVER["HTTP_USER_AGENT"];
		if(isset($parameters["limit"]) && $parameters["limit"] > 1000)
			$parameters["limit"] = 1000;
		if(isset($parameters["page"]))
			$parameters["limit"] = 1000;
		$kills = Kills::getKills($parameters, true, false);

		return self::getJSON($kills, $parameters);
	}

	/**
	 * Groups the kills together based on specified parameters
	 * @static
	 * @param array|null $kills
	 * @param array $parameters
	 * @return array
	 */
	public static function getJSON($kills, $parameters)
	{
		if ($kills == null) return array();
		$retValue = array();

		foreach ($kills as $kill) {
			$killID = $kill["killID"];
			$jsonText = Killmail::get($killID);
			$json = json_decode($jsonText, true);
			if (array_key_exists("no-items", $parameters))
				unset($json["items"]);
			if (array_key_exists("finalblow-only", $parameters))
			{
				$involved = count($json["attackers"]);
				$json["zkb"]["involved"] = $involved;
				$data = $json["attackers"];
				unset($json["attackers"]);
				foreach($data as $attacker)
					if($attacker["finalBlow"] == "1")
						$json["attackers"][] = $attacker;
			}
			if (array_key_exists("no-attackers", $parameters))
			{
				$involved = count($json["attackers"]);
				$json["zkb"]["involved"] = $involved;
				unset($json["attackers"]);
			}

			$retValue[] = json_encode($json);
		}
		return $retValue;
	}
}
