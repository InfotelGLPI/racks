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

function plugin_init_racks() {
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['racks']   = true;
   //load changeprofile function
   $PLUGIN_HOOKS['change_profile']['racks']   = array('PluginRacksProfile',
                                                                'initProfile');

   $plugin = new Plugin();
   if ($plugin->isInstalled('racks') && $plugin->isActivated('racks')) {

      //Ability to add a rack to a project
      $CFG_GLPI["project_asset_types"][] = 'PluginRacksRack';

      $PLUGIN_HOOKS['assign_to_ticket']['racks'] = true;
      Plugin::registerClass('PluginRacksRack',
                            array('document_types'       => true,
                                  'location_types'       => true,
                                  'unicity_types'        => true,
                                  'linkgroup_tech_types' => true,
                                  'linkuser_tech_types'  => true,
                                  'infocom_types'        => true,
                                  'ticket_types'         => true));
      Plugin::registerClass('PluginRacksProfile',
                            array('addtabon' => 'Profile'));

      $types = array('PluginAppliancesAppliance',
                     'PluginManufacturersimportsConfig',
                     'PluginTreeviewConfig',
                     'PluginPositionsPosition');
      foreach ($types as $itemtype) {
         if (class_exists($itemtype)) {
            $itemtype::registerType('PluginRacksRack');
         }
      }

      //If treeview plugin is installed, add rack as a type of item
      //that can be shown in the tree
      if (class_exists('PluginTreeviewConfig')) {
         $PLUGIN_HOOKS['treeview']['PluginRacksRack'] = '../racks/pics/racks.png';
      }

      if (Session::getLoginUserID()) {

         include_once (GLPI_ROOT."/plugins/racks/inc/rack.class.php");

         if (PluginRacksRack::canView()) {
            //Display menu entry only if user has right to see it !
            $PLUGIN_HOOKS["menu_toadd"]['racks'] = array('assets'  => 'PluginRacksMenu');
            $PLUGIN_HOOKS['use_massive_action']['racks'] = 1;
         }

         if (PluginRacksRack::canCreate()
            || Config::canUpdate()) {
            $PLUGIN_HOOKS['config_page']['racks'] = 'front/config.form.php';
         }

         $PLUGIN_HOOKS['add_css']['racks']   = "racks.css";
         $PLUGIN_HOOKS['post_init']['racks'] = 'plugin_racks_postinit';

         $PLUGIN_HOOKS['reports']['racks']   =
            array('front/report.php' => __("Report - Bays management","racks"));
      }

   }
}

function plugin_version_racks() {
   return array ('name'           => _n('Rack enclosure management',
                                        'Rack enclosures management',
                                        2, 'racks'),
                  'version'        => '1.7.1',
                  'oldname'        => 'rack',
                  'license'        => 'GPLv2+',
                  'author'         => 'Philippe Béchu, Walid Nouh, Xavier Caillaud',
                  'homepage'       => 'https://github.com/InfotelGLPI/racks',
                  'minGlpiVersion' => '0.90');
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_racks_check_prerequisites() {
   if (version_compare(GLPI_VERSION,'0.90', 'lt')
      || version_compare(GLPI_VERSION,'9.2', 'ge')) {
      _e('This plugin requires GLPI >= 0.90', 'racks');
      return false;
   }
   return true;
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_racks_check_config() {
   return true;
}

?>