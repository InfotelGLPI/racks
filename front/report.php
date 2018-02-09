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

// Options for GLPI 0.71 and newer : need slave db to access the report
$USEDBREPLICATE = 1;
$DBCONNECTION_REQUIRED = 1;

// Initialization of the variables
include ('../../../inc/includes.php');

$output_type = Search::HTML_OUTPUT;

if (isset($_REQUEST['list_limit'])) {
   $_SESSION['glpilist_limit'] = $_REQUEST['list_limit'];
   unset($_REQUEST['list_limit']);
}
if (!isset($_REQUEST['sort'])) {
   $_REQUEST['sort']  = "entity";
   $_REQUEST['order'] = "ASC";
}

$limit = $_SESSION['glpilist_limit'];

if (isset($_REQUEST["display_type"])) {
   $output_type = $_REQUEST["display_type"];
   if ($output_type < 0) {
      $output_type = - $output_type;
      $limit       = 0;
   }
}

$pReport = new PluginRacksReport();

if ($output_type == Search::HTML_OUTPUT) {
   Html::header(__("Report - Bays management", "racks"), $_SERVER['PHP_SELF'], "utils", "report");
   Report::title();
   $pReport->showForm($_REQUEST);
}

if (isset($_REQUEST['result_search_reports'])) {
   $pReport->showResult($output_type, $limit, $_REQUEST);
}

if ($output_type == Search::HTML_OUTPUT) {
   Html::footer();
}
