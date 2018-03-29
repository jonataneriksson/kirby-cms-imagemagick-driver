<?php

/**
* Modified ImageMagick Driver
*/
thumb::$drivers['imagemagick'] = function($thumb) {

  $command = array();

  $imagemagick = isset($thumb->options['bin']) ? $thumb->options['bin'] : 'convert';

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Use ImageMagick if is image */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  if($thumb->source->type() == 'image'):
    $command[] = $imagemagick;
    $command[] = '"' . $thumb->source->root() . '"';
    $command[] = '-colorspace sRGB';
  endif;

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Interlace */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  if($thumb->options['interlace']) {
    $command[] = '-interlace line';
  }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Grayscale */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  if($thumb->options['grayscale']) {
    $command[] = '-colorspace gray';
  }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Auto Orient */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  if($thumb->options['autoOrient']) {
    $command[] = '-auto-orient';
  }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Resizing */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  $command[] = '-resize';

  if($thumb->options['crop']) {
    $command[] = $thumb->options['width'] . 'x' . $thumb->options['height'] . '^';
    $command[] = '-gravity Center -crop ' . $thumb->options['width'] . 'x' . $thumb->options['height'] . '+0+0';
  } else {
    $dimensions = clone $thumb->source->dimensions();
    $dimensions->fitWidthAndHeight($thumb->options['width'], $thumb->options['height'], $thumb->options['upscale']);
    $command[] = $dimensions->width() . 'x' . $dimensions->height() . '!';
  }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Quality */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  if($thumb->options['quality']) {
    $command[] = '-quality ' . $thumb->options['quality'];
  }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Blur */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  if($thumb->options['blur']) {
    $command[] = '-blur 0x' . $thumb->options['blurpx'];
  }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Limit thread */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  $command[] = '-limit thread 1';
  $command[] = '"' . $thumb->destination->root . '"';
  $command[] = '2>&1';

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
  /* !Exec */
  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  $execstring = implode(' ', $command);
  exec($execstring);

};
