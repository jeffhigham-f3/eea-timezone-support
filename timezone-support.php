<?php defined('ABSPATH') || exit('No direct script access allowed');
/*
  Plugin Name: Event Espresso - Timezone Support 
  Plugin URI: https://github.com/jeffhigham-f3/eea-timezone-support
  Description: Modifies ICAL DTSTART and DTEND attributes based on values in and ACF field 'event_timezone' (pacific, mountain, central, eastern). The plugin is hard-coded to assume the server is on Mountain Time.
  Version: 0.0.1
  Author: Jeff Higham
  Author URI: https://www.github.com/jeffhigham-f3
  License: GPLv2
  Text Domain: f3_software
  GitHub Plugin URI: https://github.com/jeffhigham-f3/eea-timezone-support
  Copyright (c) 2008-2020 F3 Software All Rights Reserved.

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
/**
 * Event Espresso
 * Event Registration and Management Plugin for WordPress
 *
 * @package         Event Espresso - Timezone Support 
 * @author          Jeff HIgham
 * @copyright    (c) 2008-2020 F3 Software All Rights Reserved.
 * @since           4.0
 */

add_filter(
  'FHEE__EED_Ical__download_ics_file_ics_data',
  'eea_timezone_filter',
  10,
  2
);

function eea_timezone_filter($ics_data, $datetime)
{
  $event = $datetime->event();
  $tz = $event->get_post_meta('event_timezone', true);
  if ( !($event instanceof EE_Event) || !isset($tz) ){ return; }
  switch ($tz) {
    case 'pacific':
      $ics_data['DTSTART'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->start() + HOUR_IN_SECONDS
      );
      $ics_data['DTEND'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->end() + HOUR_IN_SECONDS
      );
      break;

    case 'mountain':
      $ics_data['DTSTART'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->start() + 0
      );
      $ics_data['DTEND'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->end() + 0
      );
      break;

    case 'central':
      $ics_data['DTSTART'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->start() - HOUR_IN_SECONDS
      );
      $ics_data['DTEND'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->end() - HOUR_IN_SECONDS
      );
      break;

    case 'eastern':
      $ics_data['DTSTART'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->start() - (HOUR_IN_SECONDS * 2)
      );
      $ics_data['DTEND'] = date(
        EED_Ical::iCal_datetime_format,
        $datetime->end() - (HOUR_IN_SECONDS * 2)
      );
      break;

    default:
      break;
  }

  return $ics_data;
}
