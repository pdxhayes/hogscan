<?php


/**
 * Implements hook_token_info().
 */
function hs_token_info() {
  $type = array(
    'name' => t('HOGSCAN'),
    'description' => t('HOG Chapter tokens.'),
  );

  $hogscan['chapter_name'] = array(
    'name' => t("Chapter Name"),
  );
  $hogscan['chapter_number'] = array(
    'name' => t("Chapter Number"),
  );
  $hogscan['chapter_city'] = array(
    'name' => t("Chapter City"),
  );
  $hogscan['chapter_state'] = array(
    'name' => t("Chapter State"),
  );
  $hogscan['chapter_country'] = array(
    'name' => t("Chapter Country"),
  );
  $hogscan['chapter_domain'] = array(
    'name' => t("Chapter Domain"),
  );
  $hogscan['chapter_website'] = array(
    'name' => t("Chapter Website"),
  );
  $hogscan['chapter_email'] = array(
    'name' => t("Chapter Email"),
  );
  $hogscan['chapter_logo_url'] = array(
    'name' => t("Chapter Logo"),
  );
  $hogscan['current_year'] = array(
    'name' => t("Current year"),
  );
  $hogscan['dealer_name'] = array(
    'name' => t("Sponsor Name"),
  );
  $hogscan['dealer_website'] = array(
    'name' => t("Sponsor Website"),
  );
  $hogscan['dealer_formatted_address'] = array(
    'name' => t("Sponsor Formatted Address"),
  );
  $hogscan['dealer_address'] = array(
    'name' => t("Sponsor Address"),
  );
  $hogscan['dealer_city'] = array(
    'name' => t("Sponsor City"),
  );
  $hogscan['dealer_state'] = array(
    'name' => t("Sponsor State / Province"),
  );
  $hogscan['dealer_postal'] = array(
    'name' => t("Sponsor Postal"),
  );
  $hogscan['dealer_country'] = array(
    'name' => t("Sponsor Country"),
  );
  $hogscan['dealer_phone'] = array(
    'name' => t("Sponsor Phone"),
  );
  $hogscan['handbook_url'] = array(
    'name' => t("HOG Chapter Handbook URL"),
  );
  $hogscan['charter_url'] = array(
    'name' => t("HOG Chapter Charter URL"),
  );
  $hogscan['member_release_url'] = array(
    'name' => t("HOG Chapter Membership and Release URL"),
  );
  $hogscan['event_release_url'] = array(
    'name' => t("HOG Event Release for Adults URL"),
  );
  $hogscan['minors_release_url'] = array(
    'name' => t("HOG Event Release for Minors URL"),
  );
  $hogscan['minors_risk_url'] = array(
    'name' => t("HOG Minors Assumption of Risk URL"),
  );
  $hogscan['insurance_open_url'] = array(
    'name' => t("Insurance Application for Non Approved or Open Events URL"),
  );
  $hogscan['insurance_parade_url'] = array(
    'name' => t("Insurance Application for Parades URL"),
  );
  $hogscan['insurance_injury_url'] = array(
    'name' => t("Insurance Injury Report"),
  );

  return array(
    'types' => array('hogscan' => $type),
    'tokens' => array('hogscan' => $hogscan),
  );
}

/**
 * Implements hook_tokens().
 */
function hs_tokens($type, $tokens, array $data = array(), array $options = array()) {
  global $config;
  
  $replacements = array();
  $sanitize = !empty($options['sanitize']);

  if ($type == 'hogscan') {
    foreach ($tokens as $name => $original) {
      switch ($name) {
        
        case 'chapter_name':
          $replacements[$original] = $config['hog_chapter_name'];
          break;

        case 'chapter_number':
          $replacements[$original] = $config['hog_chapter_number'];
          break;

        case 'chapter_city':
          $replacements[$original] = $config['hog_chapter_city'];
          break;

        case 'chapter_state':
          $replacements[$original] = $config['hog_chapter_state'];
          break;

        case 'chapter_country':
          $replacements[$original] = $config['hog_chapter_country'];
          break;

        case 'chapter_domain':
        $replacements[$original] = $config['hog_chapter_domain'];
        break;

        case 'chapter_website':
          $replacements[$original] = $config['hog_chapter_website'];
          break;

        case 'chapter_email':
          $replacements[$original] = $config['hog_chapter_email'];
        break;

        case 'chapter_logo_url':
          $replacements[$original] = $config['hog_chapter_logo_url'];
          break;

        case 'current_year':
          $replacements[$original] = date('Y');
          break;

        case 'dealer_name':
          $replacements[$original] = $config['hog_sponsor_name'];
          break;

        case 'dealer_website':
          $replacements[$original] = $config['hog_sponsor_website'];
          break;

        case 'dealer_address_formatted':
          $replacements[$original] = 
            $config['hog_sponsor_address'] . "<br>" . 
            $config['hog_sponsor_city'] . ", " . $config['hog_sponsor_state'] . " " . $config['hog_sponsor_postal'];
          break;

        case 'dealer_address':
          $replacements[$original] = $config['hog_sponsor_address'];
        break;

        case 'dealer_city':
          $replacements[$original] = $config['hog_sponsor_city'];
        break;

        case 'dealer_state':
          $replacements[$original] = $config['hog_sponsor_state'];
        break;

        case 'dealer_postal':
          $replacements[$original] = $config['hog_sponsor_postal'];
        break;

        case 'dealer_country':
          $replacements[$original] = $config['hog_sponsor_country'];
        break;

        case 'dealer_phone':
          $replacements[$original] = $config['hog_sponsor_phone'];
          break;

        case 'handbook_url':
          $replacements[$original] = $config['hog_handbook_url'];
          break;

        case 'charter_url':
          $replacements[$original] = $config['hog_charter_url'];
          break;

        case 'member_release_url':
          $replacements[$original] = $config['hog_member_release_url'];
          break;

        case 'event_release_url':
          $replacements[$original] = $config['hog_event_release_url'];
          break;

        case 'minors_release_url':
          $replacements[$original] = $config['hog_minors_release_url'];
          break;

        case 'minors_risk_url':
          $replacements[$original] = $config['hog_minors_risk_url'];
          break;

        case 'insurance_open_url':
          $replacements[$original] = $config['hog_insurance_open_url'];
          break;

        case 'insurance_parade_url':
          $replacements[$original] = $config['hog_insurance_parade_url'];
          break;

        case 'insurance_injury_url':
          $replacements[$original] = $config['hog_insurance_injury_url'];
          break;
      }
    }
  }

  return $replacements;
}
