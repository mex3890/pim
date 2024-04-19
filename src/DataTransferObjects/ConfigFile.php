<?php

namespace Pim\DataTransferObjects;

final readonly class ConfigFile
{
    /**
     * Simple DTO to manage configuration files to be published.
     * @param string $origin_full_path
     * @param string $final_filename
     * @param string $group_key
     * @return ConfigFile
     */
    public static function make(string $origin_full_path, string $final_filename, string $group_key): ConfigFile
    {
        return new self($origin_full_path, $final_filename, $group_key);
    }

    private function __construct(
        public string $origin_full_path,
        public string $final_filename,
        public string $group_key
    )
    {
    }
}
