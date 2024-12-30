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
     * Holds Blacklist Email Domains
     */
    private static $blacklist_email_domains;

    /**
     * Private Construct
     */
    private function __construct($to_emails)
    {
        self::$to_emails = $to_emails;

        /** @phpstan-ignore-next-line */
        $blacklist_email_domains = explode(',', strtolower(env('BLACKLIST_EMAIL_DOMAINS')));
        $blacklist_email_domains[] = 'mailinator.com';
        self::$blacklist_email_domains = $blacklist_email_domains;
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
     * @param  mixed $mail_class
     * @return mixed
     */
    public static function send($mail_class)
    {
        $to_emails = self::$to_emails;
        $cc_emails = self::$cc_emails;
        $bcc_emails = self::$bcc_emails;
        $blacklist_email_domains = self::$blacklist_email_domains;

        if ($to_emails && !is_array($to_emails)) $to_emails = [$to_emails];
        if ($cc_emails && !is_array($cc_emails)) $cc_emails = [$cc_emails];
        if ($bcc_emails && !is_array($bcc_emails)) $bcc_emails = [$bcc_emails];

        // Filter Emails
        $filtered_emails = [];
        foreach ($to_emails as $email) {
            $email = strtolower($email);
            $domain = explode("@", $email)[1] ?? null;

            if (!in_array($domain, $blacklist_email_domains)) {
                $filtered_emails[] = $email;
            }
        }

        $to_emails = $filtered_emails;

        if ($to_emails) {
            $emailer = \Mail::to($to_emails);
            if ($cc_emails) $emailer->cc($cc_emails);
            if ($bcc_emails) $emailer->bcc($bcc_emails);

            return $emailer->send($mail_class);
        }

        return false;
    }
}
