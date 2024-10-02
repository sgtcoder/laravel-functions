<?php

namespace SgtCoder\LaravelFunctions;

final class LaravelEmail
{
    /**
     * Holds To Emails
     */
    private static $to_emails;

    /**
     * Holds CC Emails
     */
    private static $cc_emails;

    /**
     * Holds BCC Emails
     */
    private static $bcc_emails;

    /**
     * Private Construct
     */
    private function __construct($to_emails)
    {
        self::$to_emails = $to_emails;
    }

    /**
     * Global Function Instantiator
     *
     * @return static
     */
    public static function send_laravel_email($to_emails)
    {
        return new self($to_emails);
    }

    /**
     * Chained CC
     *
     * @return static
     */
    public static function cc($cc_emails)
    {
        self::$cc_emails = $cc_emails;

        return new self(self::$to_emails);
    }

    /**
     * Chained BCC
     *
     * @return static
     */
    public static function bcc($bcc_emails)
    {
        self::$bcc_emails = $bcc_emails;

        return new self(self::$to_emails);
    }

    /**
     * Chained Send
     *
     * @return static
     */
    public static function send($mail_class)
    {
        $to_emails = self::$to_emails;
        $cc_emails = self::$cc_emails;
        $bcc_emails = self::$bcc_emails;

        // Filter Emails
        $filtered_emails = [];
        foreach ($to_emails as $email) {
            $domain = explode("@", $email)[1] ?? null;

            if (!in_array($domain, ['mailinator.com', 'noemail.com'])) {
                $filtered_emails[] = $email;
            }
        }

        $to_emails = $filtered_emails;

        $emailer = \Mail::to($to_emails);
        if ($cc_emails) $emailer->cc($cc_emails);
        if ($bcc_emails) $emailer->bcc($bcc_emails);

        return $emailer->send($mail_class);
    }
}
