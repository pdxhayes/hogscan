<?php

use Drupal\hs\Controller\HsBaseController;
use Drupal\views\ViewExecutable;
use Drupal\Core\File\FileSystemInterface;


/**
 * Implements hook_cron().
 */
function hs_cron() {

  Drupal::logger("hogscan")->info('hs_cron Executed');
  $expired_members = HsBaseController::getExpiredMembers();

}

/**
* Implements hook_page_attachments
*/
function hs_page_attachments(array &$page) {
  $page['#attached']['library'][] = 'hs/hs_global_styles';

  if(1 != Drupal::currentUser()->id()) {
    $page['#attached']['library'][] = 'hs/hs_hide_stuff';
  }

}

/**
* Implements hook_views_pre_render().
*
* Adds a library with JS and CSS to color expired dates red.
*/
function hs_views_pre_render(ViewExecutable $view) {

  if ($view->storage->id() == 'hs_member_reports') {
    $view->element['#attached']['library'][] = 'hs/hs_date_styles';
  }

}



/**
 *  Implements hook_user_presave()
 *
 *  may consider moving this to hook_user_insert
 *
 * @noinspection PhpUnused*/
function hs_user_presave($account) {
  return;
  // generate a QR code file
  $file = hs_generate_member_qr_code($account->get('field_member_hog_id')->value);
  // set the file field
  $account->set('field_member_qr', $file);


  if($account->isBlocked()) {
    // set notification group to expired members (6)
    $account->field_ref_notification_groups->target_id = 6;
  }


}



/**
 * Generate user qr code and upload to s3://public/member_qr/
 *
 */
function hs_generate_member_qr_code($hogid) {

  /*
   *  TODO: I got frustrated trying to load a library via Drupal,
   *  so the library is included in the module.  It should probably be moved.
   */

  // include_once(drupal_get_path("module","hs") . "/lib/phpqrcode/qrlib.php");
  $path = Drupal::service('extension.list.module')->getPath('node') . "/lib/phpqrcode/grlib.php";
  include_once( $path );


  // Set the QR options.
  $error_correction_level = 'M';
  $matrix_point_size = 6;

  // define the url to encode in the QR.  There is no action associated to this url it is just to
  // direct a casual code scan to the HS website. The app will extract the HOG ID from the URL.
  $qr_data = "https://www.hogscan.com?id=" . $hogid;

  // local temp file, then file_save_data will copy to s3://public/member_qr/
  $temp_dir = Drupal::service('file_system')->getTempDirectory();
  $target_dir = "public://member_qr/";
  $filename = $hogid . '.png';

  // generate the qr code using the phpqrcode library
  QRcode::png($qr_data, $temp_dir . $filename, $error_correction_level, $matrix_point_size, 2);

  // prepare the directory
  $ret = Drupal::service('file_system')->prepareDirectory($target_dir, FileSystemInterface::CREATE_DIRECTORY);
  // save the file to Drupal & s3:// using EXISTS_REPLACE (1)

  return file_save_data(file_get_contents($temp_dir . $filename), $target_dir . $filename, 1);
  // attempt to update this to the writeData service, errors because it calls Drupal\s3fs\S3fsFileService::writeData()
  // return Drupal::service('file_system')->writeData(file_get_contents($temp_dir . $filename), $target_dir . $filename, 1);




}
