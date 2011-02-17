<?php

/**
 * Gettext Localization Provider class
 * @author M2Mobi, Heinz Wiesinger
 */
class L10nProviderGettext extends L10nProvider
{

    /**
     * Constructor
     * @param String $language POSIX locale definition
     */
    public function __construct($language)
    {
        $this->init($language);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {

    }

    /**
     * Initialization method for setting up the provider
     * @param String $language POSIX locale definition
     * @return void
     */
    private function init($language)
    {
        global $config;
        putenv('LANG=' . $language);
        setlocale(LC_ALL, "");
        setlocale(LC_MESSAGES, $language);
        setlocale(LC_CTYPE, $language);
        bindtextdomain($config['l10n']['domain'], $language);
        textdomain($config['l10n']['domain']);
    }

    /**
     * Return a translated string
     * @param String $identifier Identifier for the requested string
     * @param String $context Context information fot the requested string
     * @return String $string Translated string, identifier by default
     */
    public function lang($identifier, $context = "")
    {
        require_once("class.output.inc.php");
        if ($context == "")
        {
            $output = gettext($identifier);
            if ($output == $identifier)
            {
                Output::error("No translation found for string: $identifier");
            }
            return $output;
        }
        else
        {
            global $config;
            // Glue msgctxt and msgid together, with ASCII character 4 (EOT, End Of Text)
            $composed = "{$context}\004{$identifier}";
            $output = dcgettext($config['l10n']['domain'], $composed, LC_MESSAGES);
            if ($output == $composed)
            {
                Output::error("No translation found for string: $identifier");
                return $identifier;
            }
            else
            {
                return $output;
            }
        }
    }

    /**
     * Return a translated string, with proper singular/plural
     * form
     * @param String $singular Identifier for the singular version of the string
     * @param String $plural Identifier for the plural version of the string
     * @param Integer $amount The amount the translation should be based on
     * @param String $context Context information fot the requested string
     * @return String $string Translated string, identifier by default
     */
    public function nlang($singular, $plural, $amount, $context = "")
    {
        require_once("class.output.inc.php");
        if ($context == "")
        {
            $output = ngettext($singular, $plural, $amount);
            if (($output == $singular) || ($output == $plural))
            {
                Output::error("No translation found for string: $singular");
            }
            return $output;
        }
        else
        {
            global $config;
            // Glue msgctxt and msgid together, with ASCII character 4 (EOT, End Of Text)
            $composed = "{$context}\004{$singular}";
            $output = dcngettext($config['l10n']['domain'], $composed, $plural, $amount, LC_MESSAGES);
            if (($output == $composed) || ($output == $plural))
            {
                Output::error("No translation found for string: $singular");
                return ($amount == 1 ? $singular : $plural);
            }
            else
            {
                return $output;
            }
        }
    }

}

?>
