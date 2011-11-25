<?php
/*
 * MyDemographics 1.1
 * Author: Will Pillar
 * Copyright 2010 Will Pillar. All Rights Reserved.
*/

if(!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$lang->load("mydemographics");

$page->add_breadcrumb_item($lang->mydemo_title, "index.php?module=user-mydemographics");

$query = $db->query("SELECT COUNT(name) as count FROM ".TABLE_PREFIX."profilefields WHERE name='Sex' AND fid='3'");
$sex = $db->fetch_field($query, "count");

$query = $db->query("SELECT COUNT(name) as count FROM ".TABLE_PREFIX."profilefields WHERE name='Location' AND fid='1'");
$location = $db->fetch_field($query, "count");

$custfields = $db->query("SELECT * FROM ".TABLE_PREFIX."profilefields WHERE name != 'Sex' AND type LIKE 'select%' 
    OR type LIKE 'radio%' OR type LIKE 'multiselect%' OR type LIKE 'checkbox%'");

switch ($mybb->input['action']) {
    case "mydemographics_sex":
        $nav = "mydemographics_sex";
        break;
    case "mydemographics_location":
        $nav = "mydemographics_location";
        break;
    case "mydemographics_age":
        $nav = "mydemographics_age";
        break;
    default:
        $nav = "mydemographics_home";
}

$page->output_header($lang->mydemo_title);

$sub_tabs['mydemographics_home'] = array(
        'title' => $lang->mydemo_about,
        'link' => "index.php?module=user-mydemographics",
        'description' => $lang->mydemo_about_desc
);
$sub_tabs['mydemographics_age'] = array (
        'title' => $lang->mydemo_age,
        'link' => "index.php?module=user-mydemographics&amp;action=mydemographics_age",
        'description' => $lang->mydemo_age_desc
);
if($sex) {
    $sub_tabs['mydemographics_sex'] = array(
            'title' => $lang->mydemo_sex,
            'link' => "index.php?module=user-mydemographics&amp;action=mydemographics_sex",
            'description' => $lang->mydemo_sex_desc
    );
}
if($location) {
    $sub_tabs['mydemographics_location'] = array(
            'title' => $lang->mydemo_location,
            'link' => "index.php?module=user-mydemographics&amp;action=mydemographics_location",
            'description' => $lang->mydemo_location_desc
    );
}

while($field = $db->fetch_array($custfields)) {
    $fieldname = strtolower(str_replace(' ', '', $field['name']));
    if($mybb->input['action'] == "mydemographics_".$fieldname.'_'.$field['fid']) {
        $nav = "mydemographics_".$fieldname.'_'.$field['fid'];
    }

    $sub_tabs['mydemographics_'.$fieldname.'_'.$field['fid']] = array (
            'title' => ucwords($field['name']),
            'link' => "index.php?module=user-mydemographics&amp;action=mydemographics_".$fieldname.'_'.$field['fid'],
            'description' => ucwords($field['description'])
    );
}

$page->output_nav_tabs($sub_tabs, $nav);

if($page->active_action != "mydemographics") {
    return;
}

//$custfields2 = $db->query("SELECT * FROM ".TABLE_PREFIX."profilefields WHERE name != 'Sex' AND type LIKE 'select%' OR type LIKE 'radio%'");

if($mybb->input['action'] == "mydemographics_age") {
    $query = $db->query("SELECT birthday FROM ".TABLE_PREFIX."users");

    $ranges = array (
            "<20" => 0,
            "20-29" => 0,
            "30-39" => 0,
            "40-49" => 0,
            "50-59" => 0,
            "60+" => 0
    );

    while($ages = $db->fetch_array($query)) {
        $age = get_age($ages['birthday']);

        if($age > 0 && $age < 20) {
            $ranges['<20']++;
        }
        else if($age > 19 && $age < 30) {
            $ranges['20-29']++;
        }
        else if($age > 29 && $age < 40) {
            $ranges['30-39']++;
        }
        else if($age > 39 && $age < 50) {
            $ranges['40-49']++;
        }
        else if($age > 49 && $age < 60) {
            $ranges['50-59']++;
        }
        else if($age >= 60) {
            $ranges['60+']++;
        }
    }

    $content = "<!--Load the AJAX API-->
    <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create our data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Number');
        data.addRows([";

    $content .= "['<20', ".$ranges['<20']."],";
    $content .= "['20-29', ".$ranges['20-29']."],";
    $content .= "['30-39', ".$ranges['30-39']."],";
    $content .= "['40-49', ".$ranges['40-49']."],";
    $content .= "['50-59', ".$ranges['50-59']."],";
    $content .= "['60+', ".$ranges['60+']."],";

    $content .= "]);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 600, height: 400, is3D: true, title: '".$lang->mydemo_age."'});
      }
    </script>
    <!--Div that will hold the pie chart-->
    <div id=\"chart_div\"></div>";

    $form = new Form("index.php?module=user-mydemographics&amp;action=mydemographics_age", "post");

    $form_container = new FormContainer($lang->mydemo_age_heading);

    $form_container->output_row("","", $content);

    $form_container->construct_row();

    $form_container->end();

    $form->end();
}

else if($mybb->input['action'] == "mydemographics_location") {
    //SELECT fid1 FROM `mybb_userfields` GROUP BY fid1
    $query = $db->query("SELECT fid FROM ".TABLE_PREFIX."profilefields WHERE name='Location'");
    $fid = $db->fetch_field($query, "fid");
    $query = $db->query("SELECT fid".$fid." as loc FROM ".TABLE_PREFIX."userfields GROUP BY fid".$fid);
    $num_rows = $db->num_rows($query);

    if($num_rows <= 12) {

        $content = "<!--Load the AJAX API-->
    <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create our data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Location');
        data.addColumn('number', 'Number');
        data.addRows([";

        while($locations = $db->fetch_array($query)) {
            $sql = $db->query("SELECT COUNT(fid".$fid.") as count FROM ".TABLE_PREFIX."userfields WHERE fid".$fid."='".$locations['loc']."'");

            $count = $db->fetch_field($sql, "count");

            if($locations['loc'] != "") {
                $content.= "['".$locations['loc']."', ".$count."],";
            }
        }

        $content .= "]);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 600, height: 400, is3D: true, title: '".$lang->mydemo_location."'});
      }
    </script>
    <!--Div that will hold the pie chart-->
    <div id=\"chart_div\"></div>";
    }
    else {
        $content = "<p style=\"font-weight:bold;color:#AB1D2E;\">Userdata for this profile field is too diverse to visualise effectively.</p>";
    }

    $form = new Form("index.php?module=user-mydemographics&amp;action=mydemographics_location", "post");

    $form_container = new FormContainer($lang->mydemo_location_heading);

    $form_container->output_row("","", $content);

    $form_container->construct_row();

    $form_container->end();

    $form->end();
}

else if($mybb->input['action'] == "mydemographics_sex") {
    $query = $db->query("SELECT type FROM ".TABLE_PREFIX."profilefields WHERE name='Sex'");
    $type = $db->fetch_field($query, "type");
    $query = $db->query("SELECT fid FROM ".TABLE_PREFIX."profilefields WHERE name='Sex'");
    $fid = $db->fetch_field($query, "fid");

    $options = explode("\n", $type);
    $len = count($options);

    $content = "<!--Load the AJAX API-->
    <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create our data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Sex');
        data.addColumn('number', 'Number');
        data.addRows([";

    for($i = 1; $i<$len; $i++) {
        $query = $db->query("SELECT COUNT(fid".$fid.") as count FROM ".TABLE_PREFIX."userfields WHERE fid".$fid."='".$options[$i]."'");
        $count = $db->fetch_field($query, "count");

        $content.= "['".$options[$i]."', ".$count."],";
    }

    $content .= "]);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 600, height: 400, is3D: true, title: '".$lang->mydemo_sex."'});
      }
    </script>
    <!--Div that will hold the pie chart-->
    <div id=\"chart_div\"></div>";

    $form = new Form("index.php?module=user-mydemographics&amp;action=mydemographics_sex", "post");

    $form_container = new FormContainer($lang->mydemo_sex_heading);

    $form_container->output_row("","", $content);

    $form_container->construct_row();

    $form_container->end();

    $form->end();

}

//if action isn't any of the standard profile fields, then we have a custom one and need to deal with it.
else if($mybb->input['action'] == $nav) {
    //$fid = substr($nav, -1);
    $fid = substr(strrchr($nav, '_'), 1);

    $getfield = $db->query("SELECT * FROM ".TABLE_PREFIX."profilefields WHERE fid=".$fid);

    $fdata = $db->fetch_array($getfield);

    $options = explode("\n", $fdata['type']);
    $len = count($options);

    if($len <= 12) {

        $content = "<!--Load the AJAX API-->
    <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create our data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', '".ucwords($fdata['name'])."');
        data.addColumn('number', 'Number');
        data.addRows([";

        for($i = 1; $i<$len; $i++) {
            $query = $db->query("SELECT COUNT(fid".$fid.") as count FROM ".TABLE_PREFIX."userfields WHERE fid".$fid." LIKE '%".$options[$i]."%'");
            $count = $db->fetch_field($query, "count");

            $content.= "['".ucwords($options[$i])."', ".$count."],";
        }

        $content .= "]);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 600, height: 400, is3D: true, title: '".ucwords($fdata['name'])."'});
      }
    </script>
    <!--Div that will hold the pie chart-->
    <div id=\"chart_div\"></div>";
    }
    else {
        $content = "<p style=\"font-weight:bold;color:#AB1D2E;\">Userdata for this profile field is too diverse to visualise effectively.</p>";
    }

    $form = new Form("index.php?module=user-mydemographics&amp;action=".$nav, "post");

    $form_container = new FormContainer(ucwords($fdata['name']));

    $form_container->output_row("","", $content);

    $form_container->construct_row();

    $form_container->end();

    $form->end();
}

if(!$mybb->input['action']) {
    $form = new Form("index.php?module=user-mydemographics", "post");

    $form_container = new FormContainer($lang->mydemo_about." ".$lang->mydemo_title);

    $form_container->output_row($lang->mydemo_general_title,"", $lang->mydemo_general);

    $form_container->output_row($lang->mydemo_usage_title, "", $lang->mydemo_usage);

    $form_container->output_row($lang->mydemo_limits_title, "", $lang->mydemo_limits);

    $form_container->output_row($lang->mydemo_support_title, "", $lang->mydemo_support);

    $form_container->construct_row();

    $form_container->end();

    $form->end();
}

$page->output_footer();

?>