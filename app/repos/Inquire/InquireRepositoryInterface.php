<?php


namespace MissionNext\Repos\Inquire;

use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User;

interface InquireRepositoryInterface
{
    const KEY = 'inquire';

    public function inquire(User $user, Job $job);
} 