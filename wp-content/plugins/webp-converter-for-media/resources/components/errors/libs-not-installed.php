<?php

  use WebpConverter\Settings\Page;

?>
<p>
  <?= wp_kses_post(sprintf(
    __('On your server is not installed %sGD%s or %sImagick%s library. Please read %sthe plugin FAQ%s and check your server configuration %shere%s. Compare it with the configuration given in the requirements of plugin in the FAQ. Please contact your server administrator.', 'webp-converter-for-media'),
    '<strong>',
    '</strong>',
    '<strong>',
    '</strong>',
    '<a href="https://wordpress.org/plugins/webp-converter-for-media/#faq" target="_blank">',
    '</a>',
    '<a href="' . sprintf('%s&action=server', Page::getSettingsPageUrl()) . '">',
    '</a>'
  )); ?>
</p>