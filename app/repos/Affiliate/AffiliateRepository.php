<?php

namespace MissionNext\Repos\Affiliate;

use MissionNext\Models\Affiliate\Affiliate;
use MissionNext\Repos\AbstractRepository;

class AffiliateRepository   extends AbstractRepository implements  AffiliateRepositoryInterface
{
    protected $modelClassName = Affiliate::class;

    /**
     * @return Affiliate
     */
    public function getModel()
    {

        return $this->model;
    }
} 