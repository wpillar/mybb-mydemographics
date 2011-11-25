<?php
/*
 * MyDemographics 1.0
 * Author: Will Pillar
 */

$l['mydemo_title'] = "MyDemographics";
$l['mydemo_desc'] = "Shows you demographics for your forum based on standard and custom profile fields.";
$l['mydemo_about'] = "About";
$l['mydemo_about_desc'] = "Information about MyDemographics and how it works.";
$l['mydemo_age'] = "Age";
$l['mydemo_age_desc'] = "View stats about your member's ages.";
$l['mydemo_sex'] = "Sex";
$l['mydemo_sex_desc'] = "View stats for the Sex field.";
$l['mydemo_location'] = "Location";
$l['mydemo_location_desc'] = "View stats for the Location field.";
$l['mydemo_age_heading'] = "Age Groups on Your Forum";
$l['mydemo_location_heading'] = "User Locations on Your Forum";
$l['mydemo_sex_heading'] = "User Genders on Your Forum";

$l['mydemo_general'] = "MyDemographics is a plugin for MyBB 1.6. It allows the forum Administrator to visualise data about their members using
        the custom profile fields setup on their forum. It uses the Google Charts API to create the charts you see in the tabs above, this does
        add to the load time of each tab, so please be patient while the charts are drawn.";
$l['mydemo_usage'] = "MyDemographics will mostly take care of everything for you, it pulls in data from the custom profile fields that it
        can work with and draws the charts for you. There's no configuration for you to deal with. As you create new custom profile fields,
        the plugin will pull in the data and display it for you.<br><br>
        <b>The plugin only supports checkbox, select, multi-select and radio fields.</b> This is because other fields suchs as text, have
        a far too diverse data range to display using the charts API. You may also encounter an error message telling you about this on some
        of your profile fields.";
$l['mydemo_limits'] = "As stated above, the plugin only supports checkbox, select, multi-select and radio profile fields. Even with those kinds the plugin
        can only handle fields with less than 13 options to choose from. If you add a custom profile field with one of the above types and with more than 12
        options, on that tab you will be shown an error message telling you that the data is too diverse to display effectively.";
$l['mydemo_support'] = "For support on this plugin, please visit the <a href=\"http://mybb.willpillar.com\" target=\"_blank\">http://mybb.willpillar.com</a>
        where you can post support threads and get help.
        Alternatively you can visit the MyBB Community Forums and find me under the username 'Nitrus'.";

$l['mydemo_general_title'] = "General";
$l['mydemo_usage_title'] = "Usage";
$l['mydemo_limits_title'] = "Limitations";
$l['mydemo_support_title'] = "Support";


