<?php

namespace App\Services;

use Endroid\QrCode\Builder\BuilderInterface;

use Endroid\QrCode\QrCode;

class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode($query)
    {
        $qrCode = new QrCode($query);
        $qrCode->setSize(300);
        $dataUri = $this->builder->build($qrCode);
        return $dataUri;
    }
}