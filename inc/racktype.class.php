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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

// Class for a Dropdown
class PluginRacksRackType extends CommonDropdown {

   static $rightname = "plugin_racks";
   var $can_be_translated  = true;
   
   static function getTypeName($nb=0) {
      return _n('Type','Types', $nb);
   }

   static function transfer($ID, $entity) {
      global $DB;

      if ($ID > 0) {
         // Not already transfer
         // Search init item
         foreach ($DB->request('glpi_plugin_racks_racktypes', 
                               array('id' => $ID)) as $data) {
            $data                  = Toolbox::addslashes_deep($data);
            $input['name']         = $data['name'];
            $input['entities_id']  = $entity;
            $temp                  = new self();
            $newID                 = $temp->getID($input);
            if ($newID < 0) {
               $newID = $temp->import($input);
            }
           return $newID;
         }
      }
      return 0;
   }
}
?>