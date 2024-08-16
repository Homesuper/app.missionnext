<?php

namespace MissionNext\Models\Observers;


interface ModelObserverInterface
{
     const CREATED = 'created';

     const SAVED = 'saved';

     const UPDATED = 'updated';

     const DELETED = 'deleted';
} 