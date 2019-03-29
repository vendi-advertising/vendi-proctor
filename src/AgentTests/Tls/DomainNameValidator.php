<?php

declare(strict_types=1);

namespace App\AgentTests\Tls;

use App\AgentTests\AgentTestInterface;
use App\Exception\Tls\CertDomainMismatchException;
use App\Exception\Tls\CertMissingDataException;
use App\Exception\Tls\CertStrangeSANException;

class DomainNameValidator extends CertificateValidatorBase
{
    protected function does_domain_match(string $test) : bool
    {
        //Get variables and make all lowercase
        $domain = \mb_strtolower($this->get_website()->getDomain());
        $test = \mb_strtolower($test) ;

        //We don't need this for the normal case, only the wildcard, but we're
        //just going to do it always
        $domain_parts = \explode('.', $domain);
        $test_parts = \explode('.', $test);

        //This should really never happen but I'm guarding for it, just in case.
        //Technically someone could include a local domain in their SAN like
        //localhost for testing purposes. To be safe, any local domains will
        //always fail hard.
        if (!count($domain_parts)) {
            return false;
        }

        if (!count($test_parts)) {
            return false;
        }

        //If we're testing a wildcard domain
        if ('*' === $test_parts[0]) {

            //Remove the wildcard from the array
            array_shift($test_parts);

            //Rejoin our test with the wildcard removed
            $test = implode('.', $test_parts);

            switch (count($domain_parts) - count($test_parts)) {

                //If, after removing the wildcard we have the same number
                //of domain parts, just match the strings. I'm actually
                //not 100% certain this is valid, because I think that means
                //*.example.com matches example.com, but I need to check
                //the spec
                case 0:
                    break;

                //Our domain has one extra subdomain, remove it
                case 1:
                    array_shift($domain_parts);
                    break;

                //Counts are too far off, fail
                default:
                    return false;
            }

            //Rejoin the domain parts
            $domain = implode('.', $domain_parts);
        }

        return 0 === strcmp($test, $domain);
    }

    public function run_test() : string
    {
        $cert_parts = $this->get_cert_parts();
        $result = $this->get_result();

        if (!array_key_exists('subject', $cert_parts)) {
            return $this->add_exception(CertMissingDataException::create_missing_key($this->get_translator(), 'subject'));
        }

        $subject = $cert_parts['subject'];
        if (!array_key_exists('CN', $subject)) {
            return $this->add_exception(CertMissingDataException::create_missing_key($this->get_translator(), 'subject/CN'));
        }

        $cn = (string) $subject['CN'];
        if ($this->does_domain_match($cn)) {
            return AgentTestInterface::STATUS_VALID;
        }

        if (!array_key_exists('extensions', $cert_parts)) {
            return $this->add_exception(CertMissingDataException::create_missing_key($this->get_translator(), 'extensions'));
        }

        $extensions = $cert_parts['extensions'];

        if (!array_key_exists('subjectAltName', $extensions)) {
            return $this->add_exception(CertMissingDataException::create_missing_key($this->get_translator(), 'extensions/subjectAltName'));
        }

        $subjectAltName = (string) $extensions['subjectAltName'];
        $parts = \explode(',', $subjectAltName);

        foreach ($parts as $part) {
            $sub_parts = \explode(':', trim($part));
            if (2 !== count($sub_parts)) {
                return $this->add_exception(CertStrangeSANException::create_strange_format($this->get_translator(), $part));
            }

            $type = array_shift($sub_parts);
            $domain = array_shift($sub_parts);

            if ('DNS' !== $type) {
                                return $this->add_exception(CertStrangeSANException::create_strange_type($this->get_translator(), $part));
            }

            if ($this->does_domain_match($domain)) {
                return AgentTestInterface::STATUS_VALID;
            }
        }

        return $this->add_exception(CertDomainMismatchException::create());
    }
}
