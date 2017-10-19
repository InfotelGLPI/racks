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

class PluginRacksConfig extends CommonDBTM {

   static $rightname = "plugin_racks";

   //Metric unit, for more information see : 
   //http://en.wikipedia.org/wiki/Metric_system
   const METRIC_UNIT  = 1;
   //No metric unit
   const NON_METRIC_UNIT = 2;

   static function getTypeName($nb=0) {
      return __('Setup');
   }

   function showForm() {
      $this->getfromDB(1);
      $target = self::getFormURL();
      echo "<div align='center'><form method='post'  action=\"$target\">";
      echo "<table class='tab_cadre' cellpadding='5'><tr ><th colspan='2'>";
      echo self::getTypeName(0)."</th></tr>";
      echo "<tr class='tab_bg_1'><td>".__('Configuration of units', 'racks')."</td><td>";
      echo "<select name=\"unit\" size=\"1\"> ";
      echo "<option ";
      if ($this->isMetricUnit()) {
         echo "selected ";
      }
      echo "value='".self::METRIC_UNIT."'>".__('metric', 'racks')."</option>";
      echo "<option ";
      if (!$this->isMetricUnit()) {
         echo "selected ";
      }
      echo "value='".self::NON_METRIC_UNIT."'>".__('English', 'racks')."</option>";
      echo "</select> ";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __("Copy rack's location when adding a new asset in the rack", "racks");
      echo "</td>";
      echo "<td style='width: 150px;'>";
      Dropdown::showYesNo('add_location_on_new_item', $this->fields['add_location_on_new_item']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __("Forward rack's location to linked assets on change", "racks");
      echo "</td>";
      echo "<td style='width: 150px;'>";
      Dropdown::showYesNo('forward_location_on_change', $this->fields['forward_location_on_change']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      echo Html::hidden('id', array('value' => 1));
      echo Html::submit(_sx('button', 'Post'), array('name' => 'update'));
      echo "</table>";
      Html::closeForm();
      echo "</div>";

   }

   function canForwardLocation() {
      return $this->fields['forward_location_on_change'];
   }

   function canAddLocationOnNewItem() {
      return $this->fields['add_location_on_new_item'];
   }

   function isMetricUnit() {
      return ($this->fields["unit"] == self::METRIC_UNIT);
   }

   //TODO : this method should return a value, that is diplayed in a form
   //it should not echo the value directly
   function getUnit($field) {
      $units[self::METRIC_UNIT] = array('weight'      => __('kg', 'racks'),
                                        'dissipation' => __('btu/h', 'racks'),
                                        'rate'        => __('m3/h', 'racks'),
                                        'size'        => __('mm', 'racks'));

      $units[self::NON_METRIC_UNIT] = array('weight'      => __('lbs', 'racks'),
                                            'dissipation' => __('watts', 'racks'),
                                            'rate'        => __('CFM', 'racks'),
                                            'size'        => __('pouces', 'racks'));

      $this->getFromDB(1);
      echo $units[$this->getField('unit')][$field];
   }

   static function getConfig() {
      $config = new self();
      $config->getFromDB(1);
      return $config;
   }
}