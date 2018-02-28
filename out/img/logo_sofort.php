<?php
    /**
     * The metadata->thumbnail property by OXID does not support URLs
     * So, we need to fetch binary from provided image url and render
     * the image.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */

    ###########################################################################
    // 1st: general configuration
    /**
     * List with images for different languages. Each image can be accessed
     * using GET param "language=[array-key]". Despite the language one can resize an
     * "png" or "jpeg/jpg" image using the GET params "columns" and "rows". The naming is
     * adapted from the Imagick constructor arguments.
     *
     * @var array $aImageUrls
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    $aImageUrls = [
        'be_en' => 'https://cdn.klarna.com/1.0/shared/image/generic/logo/en_gb/basic/logo_black.png',
        'be_de' => 'https://cdn.klarna.com/1.0/shared/image/generic/logo/de_de/basic/logo_black.png',
    ];

    /**
     * This image is displayed when no valid image from sofort can be fetched.
     *
     * @var string $sFallbackImage
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    $sFallbackImage = 'logo_sofort_fallback.png';

    /**
     * This language is used when either an invalid language is requested
     * using GET param "language" or no "language"-GET param is passed
     * at all. The available languages are maintained via array keys of
     * $aImageUrls.
     *
     * @var string $sDefaultLanguage
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    $sDefaultLanguage = 'en';

    ###########################################################################
    // 2nd: define functions
    /**
     * Simple helper function to figure out the file type of a given file path.
     * It does not matter whether it's a relative, absolute path or an url as
     * it searches the string using a RegEx. Files are provided by Sofort.
     * who we can trust.
     *
     * @param string $sFilePath
     *
     * @return mixed|null
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    function getFileType($sFilePath)
    {
        $sFileType = null;
        $aKnownFileTypes = [
            'png',
            'jpeg',
            'jpg',
        ];

        $sRecognizedFileType = preg_replace('/^(.*)\.([a-zA-Z]{1,})$/', '$2', $sFilePath);

        if (in_array($sRecognizedFileType, $aKnownFileTypes, true))
        {
            $sFileType = $sRecognizedFileType;
        }

        return $sFileType;
    }

    /**
     * Detects and returns requested language.
     *
     * @param array  $aAvailableLanguages
     * @param string $sDefaultLanguage
     *
     * @return string $sLanguage
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    function detectRequestedLanguage($aAvailableLanguages, $sDefaultLanguage)
    {
        $sLanguage = $sDefaultLanguage;

        if (array_key_exists('language', $_GET) && array_key_exists($_GET['language'], $aAvailableLanguages))
        {
            $sLanguage = $_GET['language'];
        }

        return $sLanguage;
    }

    /**
     * Creates an image based on $sImageUrl and resize if requested.
     *
     * As Imagick uses the terms "columns" and "rows" we use them in this context either.
     *
     * @param string $sImageUrl
     * @param string $sRecognizedFileType
     * @param array  $aResizeTo
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    function renderImage($sImageUrl, $sRecognizedFileType, $aResizeTo)
    {
        $oImage = new Imagick($sImageUrl);
        if (is_array($aResizeTo) && count($aResizeTo) === 2)
        {
            $oImage->resizeImage($aResizeTo['columns'], $aResizeTo['rows'], Imagick::FILTER_LANCZOS, 1);
        }

        switch ($sRecognizedFileType)
        {
            case 'png':
                $sContentType = 'Content-Type: image/png';
                break;

            case 'jpeg':
            case 'jpg':
                $sContentType = 'Content-Type: image/jpeg';
                break;

            default:
                $sContentType = 'Content-Type: image/png';
        }
        header($sContentType);
        echo $oImage->getImageBlob();
        exit;
    }

    /**
     * Checks whether current client requested a resized image. It then returns an associative array.
     *
     * @return array[columns : int, rows : int]
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    function getResizeTo()
    {
        $aResizeTo = [];
        if (isset($_GET['columns'], $_GET['rows']) && is_numeric($_GET['columns']) && is_numeric($_GET['rows']))
        {
            $aResizeTo['columns'] = (int) $_GET['columns'];
            $aResizeTo['rows'] = (int) $_GET['rows'];
        }

        return $aResizeTo;
    }

    ###########################################################################
    // 3rd: get requested language
    $sRequestedLanguage = detectRequestedLanguage($aImageUrls, $sDefaultLanguage);

    ###########################################################################
    // 4th: fetch information about current request
    $sImageUrl = $aImageUrls[$sRequestedLanguage];
    $sRecognizedFileType = getFileType($sImageUrl);
    if ($sRecognizedFileType === null)
    {
        $sImageUrl = __DIR__ . DIRECTORY_SEPARATOR . $sFallbackImage;
        $sRecognizedFileType = getFileType($sFallbackImage);
    }

    $aResizeTo = getResizeTo();

    ###########################################################################
    // 5th: render image
    renderImage($sImageUrl, $sRecognizedFileType, $aResizeTo);
