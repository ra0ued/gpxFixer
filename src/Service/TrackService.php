<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrackService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param UploadedFile $file
     * @return null|string
     */
    public function handleFile(UploadedFile $file)
    {
        $config = [
            'indent' => true,
            'input-xml' => true,
            'output-xml' => true,
            'wrap' => false
        ];

        $filePath = $file->getRealPath();

        $tmpFile = file_get_contents($filePath);

        // Cut the last line (usually broken)
        file_put_contents($filePath, substr($tmpFile, 0, strrpos($tmpFile, PHP_EOL)));

        $fixedTrack = '/tmp/Fixed_' . $file->getClientOriginalName();

        $tidy = new \tidy($filePath, $config);

        if ($tidy->cleanRepair()) {
            file_put_contents($fixedTrack, (string)$tidy);

            $this->logger->info($file->getClientOriginalName() . ' was successfully fixed.');

            return $fixedTrack;
        }

        $this->logger->error($file->getClientOriginalName() . ' was not fixed.');

        return null;
    }
}