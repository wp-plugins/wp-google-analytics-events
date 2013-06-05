<?php
/*
Plugin Name: WP Google Analytics Events
Plugin URI: http://steam.io
Description: Adds the Google Analytics code to your website and enables you to send events on scroll or click.
Version: 1.0
Author: Yuval Oren
Author URI: http://steam.io
License: GPLv2
*/

/* Copyright 2013 Yuval Oren (email : yuval@steam.io)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/



/*
 * Plugin Activation
 */

register_activation_hook( __FILE__, 'ga_events_install' );


function ga_events_install() {
    $ga_events_options = array(
        'id' => '',
        'divs' => array(array(id => '',type =>'', action => '', cat => '', label => '')),
        'click' => array(array(id => '',type =>'', action => '', cat => '', label => ''))
    );
    update_option( 'ga_events_options', $ga_events_options );

}

/*
 * Plugin Admin Settings
 */

add_action( 'admin_menu', 'ga_events_menu');

function ga_events_menu() {
    add_options_page('WP Google Analytics Settings','WP Google Analytics Events','manage_options', __FILE__, 'ga_events_settings_page' );
}

function ga_events_settings_page() {
?>
    <div class="wrap">
        <?php screen_icon( 'plugins' ); ?>
    <h2>GA Scroll Events Plugin</h2>

        <form method="post" action='options.php'>
            <?php settings_fields('ga_events_options'); ?>
            <?php do_settings_sections('ga_events'); ?>
            <input class="button-primary" type="submit" name="submit" value="Save Changes" />
        </form>

    </div>

<?php
}

add_action('admin_init', 'ga_events_admin_init');

function ga_events_admin_init() {
    register_setting('ga_events_options','ga_events_options','ga_events_validate');
    add_settings_section('ga_events_main','WP Google Analytics Events Settings', 'ga_events_section_text','ga_events');
    add_settings_field('ga_events_id', 'Google Analytics Identifier','ga_events_setting_input','ga_events','ga_events_main');
    add_settings_field('ga_events_divs', 'Scroll Events','ga_events_setting_divs_input','ga_events','ga_events_main');
    add_settings_field('ga_events_click', 'Click Events','ga_events_setting_click_input','ga_events','ga_events_main');


}

function ga_events_section_text() {
    echo "<p>Enter your settings</p>";
}

function ga_events_setting_input() {
    $options = get_option('ga_events_options');
    $id = $options['id'];
    echo "<input id='id' name='ga_events_options[id]' type='text' value='$id' />";

}

function ga_events_setting_divs_input() {
    $options = get_option('ga_events_options');
    $divs = $options['divs'];
    echo "<table class='widefat'><thead><th>Element Name</th><th>Type</th><th>Event Category</th><th>Event Action</th><th>Event Label</th><th></th></thead><tbody>";
    if (!($divs[0][0])){
        echo "<tr>";
        echo "<td><input id='divs' name='ga_events_options[divs][0][0]' type='text' value='".$divs[0][0]."' /></td>";
        echo "<td><select id='divs' name='ga_events_options[divs][0][1]'><option selected value='id' >id</option><option value='class'>class</option></select></td>";
        echo "<td><input id='divs' name='ga_events_options[divs][0][2]' type='text' value='".$divs[0][2]."' /></td>";
        echo "<td><input id='divs' name='ga_events_options[divs][0][3]' type='text' value='".$divs[0][3]."' /></td>";
        echo "<td><input id='divs' name='ga_events_options[divs][0][4]' type='text' value='".$divs[0][4]."' /></td>";
        echo "</tr>";

    }else{
        for ($i = 0; $i < sizeof($divs)+1; $i++){

                echo "<tr>";
                echo "<td><input id='divs' name='ga_events_options[divs][$i][0] type='text' value='".$divs[$i][0]."' /></td>";
                echo "<td><select id='divs' name='ga_events_options[divs][$i][1]'>";
                if ($divs[$i][1] == 'id'){
                    echo "<option selected value='id' >id</option><option value='class'>class</option></select></td>";
                }else {
                    echo "<option  value='id' >id</option><option selected value='class'>class</option></select></td>";
                }
                echo "<td><input id='divs' name='ga_events_options[divs][$i][2]' type='text' value='".$divs[$i][2]."' /></td>";
                echo "<td><input id='divs' name='ga_events_options[divs][$i][3]' type='text' value='".$divs[$i][3]."' /></td>";
                echo "<td><input id='divs' name='ga_events_options[divs][$i][4]' type='text' value='".$divs[$i][4]."' /></td>";
                echo "<td><a class='remove' href=''>Remove</a></td>";
                echo "</tr>";

        }

    }
    echo "</tbody></table>";
}


function ga_events_setting_click_input() {
    $options = get_option('ga_events_options');
    $click = $options['click'];
    echo "<table class='widefat'><thead><th>Element Name</th><th>Type</th><th>Event Category</th><th>Event Action</th><th>Event Label</th><th></th></thead><tbody>";
    if (!($click[0][0])){
        echo "<tr>";
        echo "<td><input id='click' name='ga_events_options[click][0][0]' type='text' value='".$click[0][0]."' /></td>";
        echo "<td><select id='click' name='ga_events_options[click][0][1]'><option selected value='id'>id</option><option value='class'>class</option></select></td>";
        echo "<td><input id='click' name='ga_events_options[click][0][2]' type='text' value='".$click[0][2]."' /></td>";
        echo "<td><input id='click' name='ga_events_options[click][0][3]' type='text' value='".$click[0][3]."' /></td>";
        echo "<td><input id='click' name='ga_events_options[click][0][4]' type='text' value='".$click[0][4]."' /></td>";
        echo "</tr>";

    }else{
        for ($i = 0; $i < sizeof($click)+1; $i++){

                echo "<tr>";
                echo "<td><input id='divs' name='ga_events_options[click][$i][0] type='text' value='".$click[$i][0]."' /></td>";
                echo "<td><select id='click' name='ga_events_options[click][$i][1]'>";
                if ($click[$i][1] == 'id'){
                    echo "<option selected value='id' >id</option><option value='class'>class</option></select></td>";
                }else {
                    echo "<option  value='id' >id</option><option selected value='class'>class</option></select></td>";
                }
                echo "<td><input id='click' name='ga_events_options[click][$i][2] type='text' value='".$click[$i][2]."' /></td>";
                echo "<td><input id='click' name='ga_events_options[click][$i][3] type='text' value='".$click[$i][3]."' /></td>";
                echo "<td><input id='click' name='ga_events_options[click][$i][4] type='text' value='".$click[$i][4]."' /></td>";
                echo "<td><a class='remove' href=''>Remove</a></td>";
                echo "</tr>";

        }

    }
    echo "</tbody></table>";
    echo "<script>
            jQuery('.remove').click(function (event) {
                event.preventDefault();
                jQuery(this).closest('tr').remove();
            });
            jQuery('.add').click(function (event) {
                event.preventDefault();
            });
          </script>
    ";

}


function ga_events_validate($form){
    $updated = array();
    $updated['id'] = $form['id'];

    for ($i = 0, $j = 0; $i< sizeof($form['divs']); $i++){
        if ($form['divs'][$i][0]){
            $updated['divs'][$j] = $form['divs'][$i];
            $j++;
        }
    }

    for ($i = 0, $j = 0; $i< sizeof($form['click']); $i++){
        if ($form['click'][$i][0]){
            $updated['click'][$j] = $form['click'][$i];
            $j++;
        }
    }

    return $updated;
}


/*
 * Init
 */

add_action('init','ga_events_scripts');

function ga_events_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('scrolldepth',plugins_url( '/js/ga-scroll-events.js' , __FILE__ ));
}


add_action( 'plugins_loaded', 'ga_events_setup');

function ga_events_setup() {
    add_action( 'wp_head', 'ga_events_header', 100 );
}


function ga_events_header() {
    $options = get_option('ga_events_options');
    $id = $options['id'];
    $domain = $_SERVER['SERVER_NAME'];

    echo "<script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

              ga('create', '<?php $id ?>', '$domain');
              ga('send', 'pageview');

        </script>

          <script type='text/javascript'>

              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', '$id']);
              _gaq.push(['_trackPageview']);

              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();

        </script>";

}

add_action( 'wp_footer', 'ga_events_footer', 100 ); 

function ga_events_footer() {
    $options = get_option('ga_events_options');
    $divs = $options['divs'];
    $click = $options['click'];
    echo "
    <script> 

                jQuery(document).ready(function() {
                    scroll_events.bind_events( {
                        scroll_elements: [";
                                        $i = 0;
                                        foreach ($divs as $div) {
                                            if ($i == 0) {
                                                echo ga_events_get_selector($div);
                                            }else{
                                                echo ",".ga_events_get_selector($div);
                                            }
                                            $i++;
                                        }

                        echo "],
                        click_elements: [";
                                        $i = 0;
                                        foreach ($click as $cl) {
                                            if ($i == 0) {
                                                echo ga_events_get_selector($cl);
                                            }else{
                                                echo ",".ga_events_get_selector($cl);
                                            }
                                            $i++;
                                        }
                        echo "],
                    });
                });

    </script>";


}

function ga_events_get_selector($element) {
    if ($element[0]){
        $selector = "{'select':'";
        $selector .= ($element[1] =='class') ? '.':'#';
        $selector .= str_replace(' ','',$element[0])."',";
        $selector .= "'category':'".$element[2]."',";
        $selector .= "'action':'".$element[3]."',";
        $selector .= "'label':'".$element[4]."'";
        $selector .= '}';
        return $selector;
    }else{
        return '';
    }

}

?>