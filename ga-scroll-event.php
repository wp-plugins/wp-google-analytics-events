<?php
/*
Plugin Name: WP Google Analytics Events
Plugin URI: http://wpflow.com
Description: Adds the Google Analytics code to your website and enables you to send events on scroll or click.
Version: 1.4
Author: Yuval Oren
Author URI: http://wpflow.com
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
        'exclude_snippet' => '0',
        'universal' => '0',
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
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <div id="ga_main" class="wrap">
        <?php screen_icon( 'plugins' ); ?>
    <h2>GA Scroll Events Plugin</h2>

        <form method="post" action='options.php'>
            <?php settings_fields('ga_events_options'); ?>
            <?php do_settings_sections('ga_events'); ?>
            <input class="button-primary" type="submit" name="submit" value="Save Changes" />
        </form>

    </div>
    <div class="wrap ga_events_sidebar">
        <table class="form-table widefat" >
            <thead>
                <th>Upgrade to Pro</th>
            </thead>
            <tbody>
                <tr class="features">
                    <td>
                        <ul>
                            <li><i class="fa fa-check-square-o fa-lg"></i><strong>Assign Event Value</strong></li>
                            <li><i class="fa fa-check-square-o fa-lg"></i><strong>Shortcode support</strong></li>
                            <li><i class="fa fa-check-square-o fa-lg"></i><strong>HTML Tags support</strong></li>
                            <li><i class="fa fa-check-square-o fa-lg"></i><strong>Bounce Rate Control</strong></li>
                            <li><i class="fa fa-check-square-o fa-lg"></i><strong>Improved link tracking</strong></li>
                            <li><i class="fa fa-check-square-o fa-lg"></i><strong>Pro Support</strong></li>
                        </ul>
                    </td>
                </tr>
                <tr class="tfoot">
                    <td>
                        <div class="wpcta">
                            <a class="btn btn-subscribe" href="http://wpflow.com/upgrade/">
                                <span class="btn-title ">
                                    Starting from
                                    <span class="btn-data">
                                        <span class="price">$29</span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Begin MailChimp Signup Form -->
        <link href="//cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            #mc_embed_signup{background:#fff; clear:left; font:13px Helvetica,Arial,sans-serif;  width:194px;}
                /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
        </style>
        <div id="mc_embed_signup">
            <form action="http://wpflow.us6.list-manage.com/subscribe/post?u=3a1990ecd0eee6198c11e5fc1&amp;id=7e01c30e7f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div class="form-content">
                    <h4>Receive plugin updates, news, promo codes and more</h4>
                    <label for="mce-EMAIL">Subscribe to our mailing list</label>
                    <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_3a1990ecd0eee6198c11e5fc1_7e01c30e7f" value=""></div>
                </div>
                <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn-subscribe "></div>
            </form>
        </div>

        <!--End mc_embed_signup-->
    </div>


<?php
}

function load_custom_wp_admin_style() {
    wp_register_style( 'custom_wp_admin_css', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style( 'custom_wp_admin_css' );
}

add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

add_action('admin_init', 'ga_events_admin_init');

function ga_events_admin_init() {
    register_setting('ga_events_options','ga_events_options','ga_events_validate');
    add_settings_section('ga_events_main','WP Google Analytics Events Settings', 'ga_events_section_text','ga_events');
    add_settings_field('ga_events_id', '','ga_events_setting_input','ga_events','ga_events_main');
    add_settings_field('ga_events_exclude_snippet', '','ga_events_setting_snippet_input','ga_events','ga_events_main');
    add_settings_field('ga_events_universal', '','ga_events_setting_uni_input','ga_events','ga_events_main');
    add_settings_field('ga_events_divs', '','ga_events_setting_divs_input','ga_events','ga_events_main');
    add_settings_field('ga_events_click', '','ga_events_setting_click_input','ga_events','ga_events_main');
    add_settings_field('ga_events_sidebar', '','ga_events_setting_sidebar','ga_events','ga_events_main');


}

function ga_events_section_text() {
    echo "<a href='http://wpflow.com/documentation'>Plugin Documentation</a>";
}

function ga_events_setting_input() {
    $options = get_option('ga_events_options');
    $id = $options['id'];
    echo "<label>Google Analytics Identifier</label>";
    echo "<span class='ga_intable'><input class='inputs' id='id' name='ga_events_options[id]' type='text' value='$id' /></span>";

}


function ga_events_setting_snippet_input() {
    $options = get_option('ga_events_options');
    $id = $options['exclude_snippet'];
    echo "<label>Don't add the GA tracking code</label>";
    echo "<span class='ga_intable'><input id='snippet' name='ga_events_options[exclude_snippet]' type='checkbox' value='1' " . checked( $id , 1,false) . " /></span>";

}

function ga_events_setting_uni_input() {
    $options = get_option('ga_events_options');
    $id = $options['universal'];
    echo "<label>Universal Tracking Code</label>";
    echo "<span class='ga_intable'><input id='universal' name='ga_events_options[universal]' type='checkbox' value='1' " . checked( $id , 1,false) . " /></span>";

}

function ga_events_setting_divs_input() {
    $options = get_option('ga_events_options');
    $divs = $options['divs'];
    echo "<label>Scroll Events</label><br />";
    echo "<table class='widefat inputs inner_table'><thead><th>Element Name</th><th>Type</th><th>Event Category</th><th>Event Action</th><th>Event Label</th><th></th></thead><tbody>";
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
    echo "<label>Click Events</label><br />";
    echo "<table class='widefat inputs inner_table'><thead><th>Element Name</th><th>Type</th><th>Event Category</th><th>Event Action</th><th>Event Label</th><th></th></thead><tbody>";
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

function ga_events_setting_sidebar(){
}

function ga_events_validate($form){
    $updated = array();
    $updated['id'] = $form['id'];
    $updated['exclude_snippet'] = $form['exclude_snippet'];
    $updated['universal'] = $form['universal'];

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
    if (!isset($options['exclude_snippet']) || $options['exclude_snippet'] != '1' ) {
    if (isset($options['universal']) && $options['universal']) {
        echo "<script>
                if (typeof ga === 'undefined') {
                  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                  ga('create','$id', '$domain');
                  ga('send', 'pageview');
								}
            </script>";
    } else {
		 echo "<script type='text/javascript'>
		 if (typeof _gaq === 'undefined') {
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '$id']);
			_gaq.push(['_setDomainName', '$domain']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		 }


</script>";
    }
	}
}

add_action( 'wp_footer', 'ga_events_footer', 100 ); 

function ga_events_footer() {
    $options = get_option('ga_events_options');
    $divs = $options['divs'];
    $click = $options['click'];
    $universal = $options['universal'];
    if ($universal == ""){
        $universal = 0;
    }
    echo "
    <script> 

                jQuery(document).ready(function() {
                    scroll_events.bind_events( {
                        universal: ".$universal.",
                        scroll_elements: [";
                                        $i = 0;
                                        if (is_array($divs)){
                                            foreach ($divs as $div) {
                                                if ($i == 0) {
                                                    echo ga_events_get_selector($div);
                                                }else{
                                                    echo ",".ga_events_get_selector($div);
                                                }
                                                $i++;
                                            }
                                        }

                        echo "],
                        click_elements: [";
                                        $i = 0;
                                         if (is_array($click)){
                                            foreach ($click as $cl) {
                                                if ($i == 0) {
                                                    echo ga_events_get_selector($cl);
                                                }else{
                                                    echo ",".ga_events_get_selector($cl);
                                                }
                                                $i++;
                                            }
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
