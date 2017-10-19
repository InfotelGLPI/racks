<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 racks plugin for GLPI
 Copyright (C) 2014-2016 by the racks Development Team.

 https://github.com/InfotelGLPI/racks
 -------------------------------------------------------------------------

 LICENSE

 This file is part of racks.

 racks is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 racks is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with racks. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

include ('../../../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();
Session::checkLoginUser();

if (isset($_POST["update_server"])) {
   $PluginRacksRack      = new PluginRacksRack();
   $PluginRacksOther     = new PluginRacksOther();
   $PluginRacksRack_Item = new PluginRacksRack_Item();
   if ($PluginRacksRack->canCreate()) {
      $vartype     = "type";
      $varspec     = "plugin_racks_itemspecifications_id";
      $varname     = "name";
      $varitems_id = "items_id";
      if ($_POST[$vartype] == 'PluginRacksOtherModel') {
         $PluginRacksOther->updateOthers($_POST[$varitems_id], $_POST[$varname]);
      }
      $varpos = "position";

      $space_left = $PluginRacksRack_Item->updateItem($_POST['id'],
                                                      $_POST[$vartype],
                                                      $_POST[$varspec],
                                                      $_POST['plugin_racks_racks_id'],
                                                      $_POST['rack_size'],
                                                      $_POST['faces_id'],
                                                      $_POST[$varitems_id],
                                                      $_POST[$varpos]);
   }
   if ($space_left < 0) {
      Session::addMessageAfterRedirect(__('No more place for insertion', 'racks'), false, ERROR);
   }
   echo true;
}




