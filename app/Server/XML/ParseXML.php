<?php

namespace App\Server\XML;

use Illuminate\Support\Str;

class ParseXML
{
    public function __construct(private string $full_path)
    {}

    public function parse(): ?array
    {
        $xml = file_get_contents($this->full_path);

        $xml = Str::after($xml, "<types>") ?? $xml;
        $xml = Str::beforeLast($xml, "</types>") ?? $xml;

        // Incorrect opening tag, yes you paragon storage!
        if (Str::startsWith($xml, '</')) {
            $xml = preg_replace('~^\Q</\E([^>]+)>~', '', $xml);
        }

        $xml = trim($xml);

        $xml = str_replace("\type>", "\t</type>", $xml);

        // Incorrect closing tag, or missing one, yes you paragon storage!
        if (Str::contains($xml, 'type')
            && Str::startsWith($xml, '<type')
            && !Str::endsWith($xml, '</type>')) {
            $xml .= "\n</type>";
        }

        $xml = simplexml_load_string("<types>$xml</types>");

        return json_decode(json_encode($xml), true);
    }
}
