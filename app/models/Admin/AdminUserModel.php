<?php
namespace MissionNext\Models\Admin;

use Cartalyst\Sentry\Users\Eloquent\User;


class AdminUserModel extends User
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminusers';


    /**
     * The Eloquent group model.
     *
     * @var string
     */
    protected static $groupModel = 'MissionNext\Models\Admin\AdminGroupModel';

    /**
     * The user groups pivot table name.
     *
     * @var string
     */
    protected static $userGroupsPivot = 'adminusers_admingroups';

    /**
     * @return bool
     * @throws \Exception
     */
    public function remove()
    {
        if (is_null($this->primaryKey))
        {
            throw new \Exception("No primary key defined on model.");
        }

        if ($this->exists)
        {
            if ($this->fireModelEvent('deleting') === false) return false;

            // Here, we'll touch the owning models, verifying these timestamps get updated
            // for the models. This will allow any caching to get broken on the parents
            // by the timestamp. Then we will go ahead and delete the model instance.
            $this->touchOwners();

            $this->performDeleteOnModel();

            $this->exists = false;

            // Once the model has been deleted, we will fire off the deleted event so that
            // the developers may hook into post-delete operations. We will then return
            // a boolean true as the delete is presumably successful on the database.
            $this->fireModelEvent('deleted', false);

            return true;
        }
    }

}