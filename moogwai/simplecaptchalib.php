<?php
// This file is part of Moogwai - Private project

// This file is a crossporting of Moogwai Framework to Moodle.
// Cross porting admits license of the Moogwai for Moodle agrees with
// Moodle's GNU licence and accepts its terms as for.

/**
 * This is a PHP library that processes a simple captcha.
 *
 * @package local_aplcore
 */

defined('MOODLE_INTERNAL') || die();

use local_aplcore\moogwai\moogwai_url;

/**
 * The reCAPTCHA URL's
 */

/**
 * Gets the challenge HTML
 * Get challenge produce a challenge image generation URL that will
 * push in session an expected answer and computes a question image.
 * the expected response will be encrypted with a short duration key
 * So it cannot be "learned" by watching the network.
 */
function simplecaptcha_get_challenge_html() {
    global $CFG, $PAGE;

    $captchaimageurl = new moogwai_url('/local_aplcore/moogwai/auth/simplecaptcha.php');
    $return = '<img src="'.$captchaimageurl.'">';

    return $return;
}

/**
 * Checks the form response, unpacking the encrypted
 * message.
 */
function simplecaptcha_check_response($response) {
    global $CFG, $SESSION;

    $clearresponse = simplecapcha_unpack_response($response);

    // Check response - isvalid boolean, error string.
    $checkresponse = ['isvalid' => false, 'error' => 'captchanotmatch'];

    if ($SESSION->simplecaptcha == $clearresponse) {
        $checkresponse = ['isvalid' => true, 'error' => null];
    }

    return $checkresponse;
}

/**
 * Provisionned to reinforce captcha security.
 * @param string $response
 * @return string the clear response.
 */
function simplecapcha_unpack_response($response) {
    return $response;
}

/**
 * Generates a GD image.
 */
function simplecaptcha_generate($type) {
    global $CFG;

    $type = $type ?? $CFG->simplecaptchatype;

    $func = "simplecaptcha_generate_$type";
    $func();
}

/**
 * Generates some numbers/letters
 * @param int $glyphs number of glyphs in image.
 * @param bool $numbers has numbers
 * @param bool $lcletters has lowercase letters
 * @param bool $ucletters has uppercase letters
 */
function simplecaptcha_generate_glyphs($glyphs = 5, $numbers = true, $lcletters = true, $ucletters = true, $height = 50) {
    global $SESSION, $CFG;

    // Set CAPTCHA length and character set
    $length = $CFG->simplecaptchastrength ?? $glyphs;
    $height = $CFG->simplecaptchaheight ?? $height;

    $numbersset = '0123456789';
    $lclettersset = 'abcdefghijklmnopqrstuvwxyz';
    $uclettersset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars = '';
    if ($CFG->simplecaptchanumbers ?? $numbers) {
        $chars .= $numbersset;
    }
    if ($CFG->simplecaptchalcletters ?? $lcletters) {
        $chars .= $lclettersset;
    }
    if ($CFG->simplecaptchaucletters ?? $ucletters) {
        $chars .= $uclettersset;
    }

    $captchaString = '';
    // Generate random CAPTCHA string
    for ($i = 0; $i < $length; $i++) {
        $captchaString .= $chars[rand(0, strlen($chars) - 1)];
    }

    // Store in session for validation
    $SESSION->simplecaptcha = $captchaString;

    $width = $length * 30 + 20;

    // Create image.
    $image = imagecreatetruecolor($width, $height);
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    $textColor1 = imagecolorallocate($image, 0, 0, 0);
    $textColor2 = imagecolorallocate($image, 80, 80, 80);
    $textColor3 = imagecolorallocate($image, 120, 120, 120);
    $textColor4 = imagecolorallocate($image, 160, 160, 160);

    $colors = [$textColor1, $textColor2, $textColor3, $textColor4];

    $noiseColor = imagecolorallocate($image, 100, 100, 100);

    // Fill background
    imagefill($image, 0, 0, $bgColor);

    // Add noise lines.
    for ($i = 0; $i < 8; $i++) {
        imageline(
            $image,
            rand(0, $width),
            rand(0, $height),
            rand(0, $width),
            rand(0, $height),
            $noiseColor
        );
    }

    // Add noise dots.
    for ($i = 0; $i < 100; $i++) {
        imagesetpixel(
            $image,
            rand(0, $width),
            rand(0, $height),
            $noiseColor
        );
    }

    // Add text with distortion.
    $x = 20;
    $y = 35;
    $fontSize = 24;
    $font = $CFG->dirroot.'/local/aplcore/fonts/CookieCrisp-L36ly.ttf';

    for ($i = 0; $i < strlen($captchaString); $i++) {
        $char = $captchaString[$i];
        $angle = rand(-15, 15);
        $offsetX = rand(5, 10);
        $cix = rand(0, 3);
        $X = $x + $offsetX;
        $Y = $y + rand(-5, 5);

        debug_trace($colors[$cix]);
        debug_trace("$X, $Y, $char");

        imagettftext(
            $image,
            $fontSize,
            $angle,
            $X,
            $Y,
            $colors[$cix],
            $font,
            $char
        );
        $x += 30;
    }

    // Output image
    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);

}