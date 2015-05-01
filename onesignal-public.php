<?php

class OneSignal_Public {

  public function __construct() {}

  public static function init() {
    define( 'ONESIGNAL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        add_action( 'wp_head', array( __CLASS__, 'onesignal_header' ), 1 );
  }

  public static function onesignal_header() {
    $onesignal_wp_settings = OneSignal::get_onesignal_settings();
?>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
    <link rel="manifest" href="<?php echo(  ONESIGNAL_PLUGIN_URL . 'sdk_files/manifest.json' ) ?>" />
    <script>
      var OneSignal = OneSignal || [];
      
      function initOneSignal() {
        OneSignal.SERVICE_WORKER_UPDATER_PATH = "OneSignalSDKUpdaterWorker.js.php";
        OneSignal.SERVICE_WORKER_PATH = "OneSignalSDKWorker.js.php";
        OneSignal.SERVICE_WORKER_PARAM = { scope: '/' };
        
        <?php if ($onesignal_wp_settings['default_title'] != "") {
          echo "OneSignal.setDefaultTitle(\"" . $onesignal_wp_settings['default_title'] . "\");\n";
        }
        else {
          echo "OneSignal.setDefaultTitle(\"" . get_bloginfo( 'name' ) . "\");\n";
        }
        ?>
        <?php if ($onesignal_wp_settings['default_icon'] != "") {
          echo "OneSignal.setDefaultIcon(\"" . $onesignal_wp_settings['default_icon'] . "\");\n";
        } ?>
        <?php
        if ($onesignal_wp_settings['default_url'] != "") {
          echo "OneSignal.setDefaultNotificationUrl(\"" . $onesignal_wp_settings['default_url'] . "\");";
        }
        else {
           echo "OneSignal.setDefaultNotificationUrl(\"" . get_site_url() . "\");";
        } 
        ?>
        
        OneSignal.init({appId: "<?php echo $onesignal_wp_settings["app_id"] ?>",
                        <?php if ($onesignal_wp_settings["subdomain"] != "") { echo "subdomainName: \"" . $onesignal_wp_settings["subdomain"] . "\",\n"; } ?>
                        path: "<?php echo ONESIGNAL_PLUGIN_URL . 'sdk_files' ?>/"});
      }
      
      window.onload = function() {
        OneSignal.push(initOneSignal);
        
        var oneSignal_elements = document.getElementsByClassName("OneSignal-prompt");
        for(var i = 0; i < oneSignal_elements.length; i++) {
          oneSignal_elements[i].onclick = function() {
            OneSignal.push(['registerForPushNotifications']);
            return false;
          };
        }
      }
    </script>

<?php
  }
}
?>